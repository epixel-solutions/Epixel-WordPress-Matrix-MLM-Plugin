<?php 
 require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

 class  Eps_payout_in_remitance_datatable extends WP_List_Table {
/**
	 * Default number of items to show per page
	 *
	 * @var string
	 * @since 1.0
	*/
		public $per_page = 30;
  /**
	 * Total number of affiliates found
	 *
	 * @var int
	 * @since 1.0
	*/
		public $total_count;
	/**
	 * Number of active affiliates found
	 *
	 * @var string
	 * @since 1.0
	*/
		public $active_count;
	/**
	 *  Number of inactive affiliates found
	 *
	 * @var string
	 * @since 1.0
	*/
		public $inactive_count;
	/**
	 * Number of Blocked affiliates found
	 *
	 * @var string
	 * @since 1.0
	*/
		public $blocked_count;
	/**
	 * EPS-Databse class object
	 *
	 * @var Object
	 * @since 1.0
	*/
		public $db_obj;

	/**
	 * Get things started
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @see WP_List_Table::__construct()
	 *
	 * @param array $args Optional. Arbitrary display and query arguments to pass through
	 *                    the list table. Default empty array.
	*/
		public function __construct( $args = array() ) {
			$args = wp_parse_args( $args, array(
				'singular' => 'affiliate',
				'plural'   => 'affiliates',
			) );

			parent::__construct( $args );

			$this->get_affiliates_counts();
		}
	/**
	 * Retrieve the discount code counts
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	*/
		public function get_affiliates_counts() {

			// $search = isset( $_GET['s'] ) ? $_GET['s'] : '';

			$this->active_count = 10;

			$this->inactive_count = 5;

			$this->blocked_count = 5;

			$this->total_count = $this->active_count + $this->inactive_count + $this->blocked_count;
		}

		function prepare_items() {
			$per_page = 5;

		  $columns = $this->get_columns();

		  $hidden = array();

		  $sortable =	 $this->get_sortable_columns();

		  $this->_column_headers = array($columns, $hidden, $sortable);

		  $this->get_column_info();

			$this->process_bulk_actions();

		  $this->items = $this->affiliate_data();

		  /*$current_page = $this->get_pagenum();

		  $total_items = count($this->example_data);*/

		}

		/**
	 * Retrieve the table columns
	 *
	 * @access public
	 * @since 1.0
	 * @return array $columns Array of all the list table columns
	 */
		function get_columns(){
			$tab 			= isset($_GET['tab']) 	? $_GET['tab'] : 'active_payouts';
			if($tab == 'active_payouts'){
					$columns = array(
				  	'cb'        			=> '<input type="checkbox" />',
				  	'payout_id'				=> __( 'Payout Id'),
				  	'name'						=> __( 'Name', 'affiliate-eps' ),
				  	'payment_type'		=> __('Payment Type', 'affiliate-eps'),
				  	'processed_date'	=> __('Processed Date	','affiliate-eps'),
				  	'payout_method'		=> __('Payout Method', 'affliate-eps'),
				  	'payment_details' => __('Payment Details','affliate-eps'),
				  	'request_amount'	=> __('Requested Amount', 'affliate-eps'),
				  	'charges'					=> __('Charges', 'affliate-eps'),
				  	'payable_amount'	=> __('Payable Amount', 'affliate-eps'),
		  		);
				}else{
					$columns = array(	
				  	'payout_id'				=> __( 'Payout Id'),
				  	'name'						=> __( 'Name', 'affiliate-eps' ),
				  	'payment_type'		=> __('Payment Type', 'affiliate-eps'),
				  	'processed_date'	=> __('Processed Date	','affiliate-eps'),
				  	'payout_method'		=> __('Payout Method', 'affliate-eps'),
				  	'payment_details' => __('Payment Details','affliate-eps'),
				  	'request_amount'	=> __('Requested Amount', 'affliate-eps'),
				  	'charges'					=> __('Charges', 'affliate-eps'),
				  	'notes'						=> __('Notes',''),
				  	'payable_amount'	=> __('Payable Amount', 'affliate-eps'),
		  		);
				}	

		  return apply_filters('affiliate_eps_member_data_table_colums',$columns);
		}



		/**
	 * Retrieve all the data for all the Affiliates
	 *
	 * @access public
	 * @since 1.0
	 * @return array $affiliate_data Array of all the data for the Affiliates
	*/
		public function affiliate_data() {

			
			$page    	= isset( $_GET['paged'] )    ? absint( $_GET['paged'] ) : 1;
			$status  	= isset( $_GET['status'] )   ? $_GET['status']          : '';
			$search 	 = isset( $_GET['s'] )        ? $_GET['s']               : '';
			$order   	= isset( $_GET['order'] )    ? $_GET['order']           : 'DESC';
			$orderby 	= isset( $_GET['orderby'] )  ? $_GET['orderby']         : 'affiliate_id';

			$tab 			= isset($_GET['tab']) 	? $_GET['tab'] : 'active_payouts';
			
			$per_page = $this->get_items_per_page( 'affwp_edit_affiliates_per_page', $this->per_page );

			$args = wp_parse_args( $this->query_args, array(
				'number'  => $per_page,
				'offset'  => $per_page * ( $page - 1 ),
				'status'  => $status,
				'search'  => $search,
				'orderby' => sanitize_text_field( $orderby ),
				'order'   => sanitize_text_field( $order )
			) );
			$query['#select'] = 'wp_afl_payout_requests';
   		$query['#join']  = array(
      'wp_users' => array(
        '#condition' => '`wp_users`.`ID`=`wp_afl_payout_requests`.`uid`'
      )
    );
   	switch ($tab) {
   		case 'active_payouts':
   			$paid_status = 1;
   			break;
   		case 'canceled_payouts':
   			$paid_status = -99;
   			break;
   		case 'payout_history':
   			$paid_status = 2;
   			break;
   		default:
   			$paid_status = 2;
   			break;
   	}
   	$query['#where'] = array(
      '`wp_afl_payout_requests`.`paid_status`='.$paid_status,
      '`wp_afl_payout_requests` . `category` = "WITHDRAWAL"'
    );
    $affiliates = db_select($query, 'get_results');
    // pr($affiliates);exit();
			// Retrieve the "current" total count for pagination purposes.
			$args['number']      = -1;
			// $this->current_count = $db_obj->get_members( $args , TRUE);

			return $affiliates;
		}

		function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="payout_id[]" value="%s" />', $item->afl_payout_id
        );
    }
    public function column_payout_id($item) {
			  $value = $item->afl_payout_id;
				return $value;
		}
	/**
	 * Get he column member value
	 *
	 * @access public
	**/
		public function column_name($item) {
		  $text = $item->display_name.'<span class="label label-primary">'.$item->uid.'</span>';
		  return $text;
		}
		public function column_payment_type($item) {
			$value = ucwords(strtolower($item->category) );
			return $value;
		}
		public function column_processed_date($item) {
			  $value = afl_date_combined(afl_date_splits($item->modified));
				return $value;
		}

		public function column_payout_method($item) {
			$payout_methods = list_extract_allowed_values(afl_variable_get('payout_methods'),'list_text',FALSE);
			  $value = $payout_methods[$item->payout_method];
				return $value;
		}

		public function column_payment_details($item) {
			$value = afl_get_payment_method_details($item->uid, $item->payout_method);
			return $value;
		}

	  public function column_request_amount($item) {
			  $value = afl_get_commerce_amount($item->amount_requested) .$item->currency_code;
				return $value;
		}

		public function column_charges($item) {
			  $value = afl_get_commerce_amount($item->charges) .$item->currency_code;
				return $value;
		}
	
		
		public function column_payable_amount($item) {
			  $value = afl_get_commerce_amount($item->amount_paid) .$item->currency_code;
				return $value;
		}
		public function column_notes($item) {
			  $value = ($item->notes);
				return $value;
		}


	/** 
	 * Retrieve the bulk actions 
	 *
	 * @access public
	 * @return array $actions Array of the bulk actions
	*/
		public function get_bulk_actions() {
			$tab 			= isset($_GET['tab']) 	? $_GET['tab'] : 'active_payouts';
			// pr($tab);
			if($tab == 'active_payouts'){
				$actions = array(
					'mark_as_paid'     => __( 'Marks as paid'),
				);
				return apply_filters( 'eps_affiliats_bulk_action', $actions );
			}
		}
	/**
	 * Process the bulk actions
	 *
	 * @access public
	 * @return void
	 */
		public function process_bulk_actions() {
			if ( empty( $_REQUEST['_wpnonce'] ) ) {
				return;
			}
			if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-affiliates' ) && ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'affiliate-nonce' ) ) {
				return;
			}
			$ids = isset( $_GET['payout_id'] ) ? $_GET['payout_id'] : false;
			if ( ! is_array( $ids ) ) {
				$ids = array( $ids );
			}
			$ids = array_map( 'absint', $ids );
			if ( empty( $ids ) ) {
				return;
			}
				
			foreach ( $ids as $id ) {

				if ( 'mark_as_paid' === $this->current_action() ) {

					// pr($ids);

					$table 				= _table_name('afl_payout_requests');
	
					$query 						=   array();
				 	$query['#select'] = $table;
				 	$query['#fields'] = array(
				 		$table => array('payout_method')
				 		);
				 	$query['#where'] 	= array(
				 		'afl_payout_id ='.$id
				 	);
				 	$row = db_select($query, 'get_row');

				 	$hyper_wallet_status = TRUE;
				 	if(isset($row->payout_method) && $row->payout_method == 'method_hyperwallet') {
						$hyper_wallet_status = apply_filters('afl_hyper_wallet_payout', $id);
				 	}
					// var_dump($hyper_wallet_status);
					// pr('here',1);
					if($hyper_wallet_status)	{
						$response = apply_filters('eps_affiliates_payout_paid', $id);
						if ( $response ) {
							echo wp_set_message('Payout successfully Completed', 'success');
						} else {
							echo wp_set_message('Some error occured', 'error');
						}
					}
				}
			}

		}

 }
