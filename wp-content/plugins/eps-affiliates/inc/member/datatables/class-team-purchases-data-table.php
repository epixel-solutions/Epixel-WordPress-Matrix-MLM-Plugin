<?php

/*
 * -------------------------------------------------------------------------
 * List table view
 * -------------------------------------------------------------------------
*/
 	if( ! class_exists( 'WP_List_Table' ) ) {
	    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}
	class Eps_team_purchases_data_table extends WP_List_Table {
	/**
	 * Default number of items to show per page
	 *
	 * @var string
	 * @since 1.0
	*/
		public $per_page = 30;

	/**
	 * ---------------------------------------------------------------------
	 * Get things started
	 *
	 * ---------------------------------------------------------------------
	 * @access public
	 * @since  1.0
	 *
	 * @see WP_List_Table::__construct()
	 *
	 * @param array $args Optional. Arbitrary display and query arguments to pass through
	 *                    the list table. Default empty array.
	 * ---------------------------------------------------------------------
	*/
		public function __construct( $args = array() ) {
			$args = wp_parse_args( $args, array(
				'singular' => 'team_purchase',
				'plural'   => 'team_purchases',
			) );

			parent::__construct( $args );
		}

	/**
	 * ---------------------------------------------------------------------
	 * Retrieve the view types
	 * ---------------------------------------------------------------------
	 *
	 * @access public
	 * @since 1.0
	 * @return array $views All the views available
	 * ---------------------------------------------------------------------
	 */
		public function get_views() {
			$base           = 'manage-members';

			$current        = isset( $_GET['status'] ) ? $_GET['status'] : '';
			$total_count    = '&nbsp;<span class="count">(' . $this->total_count    . ')</span>';
			$active_count   = '&nbsp;<span class="count">(' . $this->active_count . ')</span>';
			$inactive_count = '&nbsp;<span class="count">(' . $this->inactive_count  . ')</span>';
			$pending_count  = '&nbsp;<span class="count">(' . $this->pending_count  . ')</span>';
			$rejected_count = '&nbsp;<span class="count">(' . $this->rejected_count  . ')</span>';

			$views = array(
				'all'		=> sprintf( '<a href="%s"%s>%s</a>', esc_url( remove_query_arg( 'status', $base ) ), $current === 'all' || $current == '' ? ' class="current"' : '', __('All', 'affiliate-wp') . $total_count ),
				'active'	=> sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'status', 'active', $base ) ), $current === 'active' ? ' class="current"' : '', __('Active', 'affiliate-wp') . $active_count ),
				'inactive'	=> sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'status', 'inactive', $base ) ), $current === 'inactive' ? ' class="current"' : '', __('Inactive', 'affiliate-wp') . $inactive_count ),
				'pending'	=> sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'status', 'pending', $base ) ), $current === 'pending' ? ' class="current"' : '', __('Pending', 'affiliate-wp') . $pending_count ),
				'rejected'	=> sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'status', 'rejected', $base ) ), $current === 'rejected' ? ' class="current"' : '', __('Rejected', 'affiliate-wp') . $rejected_count ),
			);

			return $views;
		}
	/**
	 * ---------------------------------------------------------------------
	 * Search Box
	 * ---------------------------------------------------------------------
	*/
    function search_box($text, $input_id){ ?>
    <p class="search-box">
		<label class="screen-reader-text" for="search_id-search-input">
		search:</label> 
		<input id="search_id-search-input" type="text" name="s" value="" /> 
		<input id="search-submit" class="button" type="submit" name="" value="search" />
		</p>
   <?php  }
	/**
	 * ---------------------------------------------------------------------
	 * Retrieve the table columns
	 * ---------------------------------------------------------------------
	 *
	 * @access public
	 * @since 1.0
	 * @return array $columns Array of all the list table columns
	 * ---------------------------------------------------------------------
	 */
		function get_columns(){
		  $columns = array(
		  	'member'				=> __( 'Member', 'affiliate-eps' ),
		  	'member_status'	=> __( 'Member Status', 'affiliate-eps' ),
		  	'Rank'					=> __( 'Rank', 'affiliate-eps' ),
		  	'total'					=> __( 'Total Purchase', 'affiliate-eps' )
		  );
		  return apply_filters('affiliate_eps_member_data_table_colums',$columns);
		}
		function column_default( $item, $column_name ) {
		  switch( $column_name ) {
		    case 'booktitle':
		    case 'author':
		    case 'isbn':
		      return $item[ $column_name ];
		    default:
		      return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		  }
		}
  /**
	 * Retrieve the table's sortable columns
	 *
	 * @access public
	 * @since 1.0
	 * @return array Array of all the sortable columns
	 */
		public function get_sortable_columns() {
			return array(
				// 'member'        => array( 'member',        false ),
				// 'parent'        => array( 'parent',        false ),
				// 'sponsor'       => array( 'sponsor',       false ),
				// 'role' 					=> array( 'role', 				 false ),
				// 'member_status' => array( 'member_status', false ),
				// 'registered_on' => array( 'registered_on', false ),
			);
		}

	/**
	 * ---------------------------------------------------------------------
	 * Get he column member value
	 * ---------------------------------------------------------------------
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	 * ---------------------------------------------------------------------
	*/
		public function column_member($item) {
		  $actions = array(
		            'edit'      => sprintf('<a href="?page=%s&action=%s&book=%s">Edit</a>',$_REQUEST['page'],'edit',$item->uid),
		            'delete'    => sprintf('<a href="?page=%s&action=%s&book=%s">Delete</a>',$_REQUEST['page'],'delete',$item->uid),
		        );
		  $text = $item->display_name.'<span class="label label-primary">'.$item->uid.'</span>';
		  return $text;
		}
	
	/**
	 * ---------------------------------------------------------------------
	 * column Member_status
	 * ---------------------------------------------------------------------
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	 * ---------------------------------------------------------------------
	*/
	  public function column_member_status($item) {
				$statuses = list_extract_allowed_values(afl_variable_get('member_status'), 'list_text', '');
			  $value 		= $statuses[$item->status];
				return apply_filters( 'eps_affiliate_member_table_member_status', $value, $item );
		}
	/**
	 * ---------------------------------------------------------------------
	 * column member rank
	 * ---------------------------------------------------------------------
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	 * ---------------------------------------------------------------------
	*/
	  public function column_rank($item) {

			  $value = render_rank($item->member_rank);
				return $value;
		}
	/**
	 * ---------------------------------------------------------------------
	 * Total purchase
	 * ---------------------------------------------------------------------
	 *
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	 * ---------------------------------------------------------------------
	*/
		public function column_total($item) {
			// pr($item);
			  $value = afl_format_payment_amount($item->total_purchase).afl_currency_symbol();
				return $value;
		}


  /**
 	 * ---------------------------------------------------------------------
	 * Renders the message to be displayed when there 
	 * are no affiliates.
 	 * ---------------------------------------------------------------------
	 *
	 * @access public
	 * @since  1.0
 	 * ---------------------------------------------------------------------
	*/
		function no_items() {
			_e( 'No affiliates found.', 'affiliate-wp' );
		}
	/**
 	 * ---------------------------------------------------------------------
	 * Prepare the items
 	 * ---------------------------------------------------------------------
	 *
	 * @access public
	 * @since  1.0
 	 * ---------------------------------------------------------------------
	*/
		function prepare_items() {
			$per_page = 5;

		  $columns = $this->get_columns();

		  $hidden = array();

		  $sortable =	 $this->get_sortable_columns();

		  $this->_column_headers = array($columns, $hidden, $sortable);

		  $this->get_column_info();

			$this->process_bulk_action();



		  $this->items = $this->purchase_data();

		  $current_page = $this->get_pagenum();

		  $total_items = count($this->items);
		  // only ncessary because we have sample data
		  $this->found_data = array_slice($this->items,(($current_page-1)*$per_page),$per_page);

		  $this->set_pagination_args( array(
		    'total_items' => $total_items,                  //WE have to calculate the total number of items
		    'per_page'    => $per_page                     //WE have to determine how many items to show on a page
		  ) );
		  // $this->items = $this->found_data;

		}
	/**
 	 * ---------------------------------------------------------------------
	 * Retrieve all the data for all the Affiliates
 	 * ---------------------------------------------------------------------
	 *
	 * @access public
	 * @since 1.0
	 * @return return the purchase details
 	 * ---------------------------------------------------------------------
	*/
		public function purchase_data() {
			$uid = get_uid();
			$query = array();
			$query['#select'] = _table_name('afl_purchases');
			$query['#join'] 	= array(
				_table_name('afl_user_genealogy') => array(
					'#condition' => '`'._table_name('afl_user_genealogy').'`.`uid` =`'._table_name('afl_purchases').'`.`uid` '
				),
				_table_name('users') => array(
					'#condition' => '`'._table_name('users').'`.`ID` =`'._table_name('afl_user_genealogy').'`.`uid` '
				)
			);
			$query['#expression'] = array(
				'SUM(`'._table_name('afl_purchases').'`.`afl_points`) as total_purchase'
			);
			$query['#where'] = array(
				'`'._table_name('afl_user_genealogy').'`.`referrer_uid` = '.$uid
			);
			$query['#group_by'] = array(
				_table_name('afl_user_genealogy').'.uid'
			);
			$result  = db_select($query, 'get_results');

			return $result;
		}
	}
