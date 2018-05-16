<?php 
 require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

 class  Eps_user_ewallet_my_withdrawal_datatable extends WP_List_Table {
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
			$tab 			= isset($_GET['tab']) 	? $_GET['tab'] : 'active_requestes';
			if($tab == 'active_requestes'){
					$columns = array(
				  	'cb'        			=> '<input type="checkbox" />',
				  	'payout_id'				=> __("Payout ID"),
				  	'member'					=> __( 'Member'),
				  	'request_amount'	=> __('Requested Amount', 'affliate-eps'),
				  	'charges'					=> __('Charges', 'affliate-eps'),
				  	'requested_date'	=> __('Requested Date	','affiliate-eps'),
				  	'notes'						=> __( 'Notes', 'affiliate-eps' ),
				  	'payout_method'		=> __('Payout Method', 'affliate-eps'),
				  	'payable_amount'	=> __('Payable Amount', 'affliate-eps'),
				  	'paid_date'				=> __('Paid Date', 'affiliate-eps'),
				  	'paid_status' 		=> __('Paid Status','affliate-eps'),
		  		);
				}
				else{
					$columns = array(
				  	'payout_id'				=> __("Payout ID"),
				  	'member'					=> __( 'Member'),
				  	'request_amount'	=> __('Requested Amount', 'affliate-eps'),
				  	'charges'					=> __('Charges', 'affliate-eps'),
				  	'requested_date'	=> __('Requested Date	','affiliate-eps'),
				  	'notes'						=> __( 'Notes', 'affiliate-eps' ),
				  	'payout_method'		=> __('Payout Method', 'affliate-eps'),
				  	'payable_amount'	=> __('Payable Amount', 'affliate-eps'),
				  	'paid_date'				=> __('Paid Date', 'affiliate-eps'),
				  	'paid_status' 		=> __('Paid Status','affliate-eps'),
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

			$uid = get_current_user_id();
			// pr($uid);
			$page    	= isset( $_GET['paged'] )    ? absint( $_GET['paged'] ) : 1;
			$status  	= isset( $_GET['status'] )   ? $_GET['status']          : '';
			$search 	 = isset( $_GET['s'] )        ? $_GET['s']               : '';
			$order   	= isset( $_GET['order'] )    ? $_GET['order']           : 'DESC';
			$orderby 	= isset( $_GET['orderby'] )  ? $_GET['orderby']         : 'affiliate_id';

			$tab 			= isset($_GET['tab']) 	? $_GET['tab'] : 'active_requestes';	
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
   		$query['#where'] = array( 
      '`wp_afl_payout_requests` . `category` = "WITHDRAWAL"',
      '`wp_afl_payout_requests` . `uid` = '.$uid
    );
   		// pr($tab);
   	switch ($tab) {
   		case 'active_requestes':
   			$request_status = 1;
   		
   			$query['#where'][] = '`wp_afl_payout_requests`.`request_status`='.$request_status;
   			// pr($condition);
   			// pr($query['#where']);
   			break;
   		case 'approved_requests':
   			$request_status = 2;
   			$paid_status 		= 1;
   			$query['#where'][] = '`wp_afl_payout_requests`.`request_status`='.$request_status;
   			$query['#where'][] = '`wp_afl_payout_requests`.`paid_status`='.$paid_status;
   			break;
   		case 'rejected_requests':
   			$paid_status 		= -99;
   			$query['#where'][] = '`wp_afl_payout_requests`.`paid_status`='.$paid_status;
   			break;
   		case 'completed_requests':
   			$request_status = 3;
   			$paid_status 		= 2;
   			$query['#where'][] = '`wp_afl_payout_requests`.`request_status`='.$request_status;
   			$query['#where'][] = '`wp_afl_payout_requests`.`paid_status`='.$paid_status;
   			break;
   		default:
   			$request_status = 2;
   			break;
   	}
   // pr($query);
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
		public function column_member($item) {
		  $text = $item->display_name.'<span class="label label-primary">'.$item->uid.'</span>';
		  return $text;
		}
	
 		public function column_request_amount($item) {
			  $value = afl_get_commerce_amount($item->amount_requested) .$item->currency_code;
				return $value;
		}

		public function column_charges($item) {
			  $value = afl_get_commerce_amount($item->charges) .$item->currency_code;
				return $value;
		}

		public function column_requested_date($item) {
			  $value = afl_date_combined(afl_date_splits($item->created));
				return $value;
		}

		public function column_notes($item) {
			  $value = $item->notes;
				return $value;
		}

		public function column_payout_method($item) {
			$payout_methods = list_extract_allowed_values(afl_variable_get('payout_methods'),'list_text',FALSE);
			  $value = $payout_methods[$item->payout_method];
				return $value;
		}

		public function column_payable_amount($item) {
			  $value = afl_get_commerce_amount($item->amount_paid) .$item->currency_code;
				return $value;
		}

		public function column_paid_status($item) {
			$paid_status = list_extract_allowed_values(afl_variable_get('paid_status'),'list_text',FALSE);		
			  $value = $paid_status[$item->paid_status];
				return $value;
		}

		public function column_paid_date($item) {
			if(!empty($item->paid_date))
			 $value = afl_date_combined(afl_date_splits($item->paid_date));
				else
					$value = NULL;
				return $value;
		}
		
	/**
	 * Retrieve the bulk actions
	 *
	 * @access public
	 * @return array $actions Array of the bulk actions
	*/
		public function get_bulk_actions() {
			$tab 			= isset($_GET['tab']) 	? $_GET['tab'] : 'active_requestes';
			// pr($tab);
			if($tab == 'active_requestes'){
				$actions = array(
					'withdrawal_cancel'     => __( 'Cancel The Request'),
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

				if ( 'withdrawal_cancel' === $this->current_action() ) {
					$response = apply_filters('eps_affliate_user_cancel_withdraw', $id);
					if ( $response ) {
						echo wp_set_message('Cancel Withdrawal successfully Completed', 'success');
					} else {
						echo wp_set_message('Some error occured', 'error');
					}
				}
			}

		}

 }
