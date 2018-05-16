<?php 
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

 class  Eps_user_epin_history_datatable extends WP_List_Table {
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
				'singular' => 'epin-affiliate',
				'plural'   => 'epin-affiliates',
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
			
					$columns = array(
				  	'pin_number'			=> __('Pin Number'),
				  	'user_details'		=> __( 'Used By'),
				  	'date'						=> __('Used Date'),
				  	'amount'					=> __('Amount	'),
		  		);
			
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
			$uid 					 = get_current_user_id();		
			$page    	= isset( $_GET['paged'] )    ? absint( $_GET['paged'] ) : 1;
			$status  	= isset( $_GET['status'] )   ? $_GET['status']          : '';
			$search 	 = isset( $_GET['s'] )        ? $_GET['s']               : '';
			$order   	= isset( $_GET['order'] )    ? $_GET['order']           : 'DESC';
			$orderby 	= isset( $_GET['orderby'] )  ? $_GET['orderby']         : 'affiliate_id';
			$per_page = $this->get_items_per_page( 'affwp_edit_affiliates_per_page', $this->per_page );

			$table_his 	= _table_name('afl_epin_history');
			$table_epin 	= _table_name('afl_epin');
			$table_users = _table_name('users');

			$args = wp_parse_args( $this->query_args, array(
				'number'  => $per_page,
				'offset'  => $per_page * ( $page - 1 ),
				'status'  => $status,
				'search'  => $search,
				'orderby' => sanitize_text_field( $orderby ),
				'order'   => sanitize_text_field( $order )
			) );
			$query['#select'] = _table_name('afl_epin_history');
			$query['#fields'] = array(
				_table_name('afl_epin_history') => array(
					'pin_id',
					'pin',
					'uid',
					'created_date',
					'amount'
				),
				_table_name('users') => array(
					'display_name'
					)

			);
   		$query['#join']  = array(
       _table_name('users') => array(
       		'#condition' => $table_users.'.`ID`'.'='.$table_his.'.`uid`',
      	),
       _table_name('afl_epin') =>array(
       		'#condition' => $table_epin.'.`pin_id`'.'='.$table_his.'.`pin_id`',

       	)
    	);
   		$query['#where'] = array( 
   			$table_his.'.`uid`='.$uid
    ); 	


    $affiliates = db_select($query, 'get_results');	
     // pr($affiliates);exit();
			// Retrieve the "current" total count for pagination purposes.
			$args['number']      = -1;
			// $this->current_count = $db_obj->get_members( $args , TRUE);

			return $affiliates;
		}
	/**
	 * Get he column member value
	 *
	 * @access public
	**/
		public function column_user_details($item) { 
		  $text = $item->display_name.' <span class="label label-primary">'.$item->uid.'</span>';
		  return $text;
		}
		public function column_pin_number($item) {
			  $pin = $item->pin;
			  $value = '<a href = ""> <span id = "epin'.$pin.'"class ="label label-primary lead bg-info bigger-lable">'.$pin.
			  '</span> </a>';
				return $value;
				
		}
		public function column_date($item) {
			  $value = afl_date_combined(afl_date_splits($item->created_date));
				return $value;
		}
		public function column_amount($item) {
			  $value = afl_currency_symbol() . afl_get_commerce_amount($item->amount);
				return $value;
		}

// Class end here
 }














/**
* menu call back for users my epins
*
**/
function afl_epin_history(){
		do_action('eps_affiliate_page_header');
	do_action('afl_content_wrapper_begin');
		afl_user_epin_history_view();
	do_action('afl_content_wrapper_end');
}

function afl_user_epin_history_view(){
	$epin_affiliates_table = new Eps_user_epin_history_datatable();
	?>
			<div class="wrap">
			<?php
			/**
			 * Manage epin 
			 *
			 * Use this hook to add content to this section of AffiliateWP.
			 */
				do_action( 'eps_affiliates_page_top' );

				?>
				<form id="eps-affiliates-filter" method="get" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<?php $epin_affiliates_table->search_box( __( 'Search', 'eps-epin-affiliates' ), 'eps-epin-affiliates' ); ?>

					<input type="hidden" name="page" value="e-pin-history" />

					<?php $epin_affiliates_table->views() ?>
					<?php $epin_affiliates_table->prepare_items() ?>
					<?php $epin_affiliates_table->display() ?>
				</form>
				<?php
				/**
				 * Fires at the bottom of the admin affiliates page.
				 *
				 * Use this hook to add content to this section of AffiliateWP.
				 */
				// do_action( 'eps_affiliates_page_bottom' );
				?>
			</div>
			<?php
	}
