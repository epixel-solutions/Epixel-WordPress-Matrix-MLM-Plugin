<?php 
/*
 * -----------------------------------------------------------------------
 * In here, the functionalities that done when the plugin hase been 
 * deleted
 * -----------------------------------------------------------------------
*/
	class Eps_affiliates_uninstall {
		public function __construct () {
			
			$this->remove_roles();
			//remove page
			$page_id = get_option('eps_affiliate_page');
			if( $page_id ) {
		    wp_delete_post( $page_id ); // this will trash, not delete
			}
			//remove the tables
			$this->eps_afl_remove_tables() ;

			//set option as uninstalled
			if (get_option( 'eps_afl_is_installed' ) ) {
				update_option( 'eps_afl_is_installed', '0' );
			}
			//set option as uninstalled
			if (afl_variable_get( 'eps_afl_is_configur_permissions', FALSE ) ) {
				afl_variable_set( 'eps_afl_is_configur_permissions', FALSE );
			}
		}
		/*
		 * ------------------------------------------------------------
		 * Remove roles
		 * ------------------------------------------------------------
		*/
		 public function remove_roles () {
		 	remove_role( 'afl_member' );
		 	remove_role( 'afl_customer' );
		 	remove_role( 'business_admin' );
		 	remove_role( 'business_director' );
		 }
		 /* 
		 * -----------------------------------------------------------
		 * Remove tables
		 * -----------------------------------------------------------
		*/
		 public function eps_afl_remove_tables () {
			global $wpdb;
			$tables = $this->system_tables_list();
			foreach ($tables as $table) {
				$delete = $wpdb->query('DROP TABLE `'.$table.'`');
			}
		 }
		 /*
		  * ----------------------------------------------------------
		  * All tables names
		  * ----------------------------------------------------------
		 */
		  public function system_tables_list () {
		  	global $wpdb;
				$tbl_prefix = $wpdb->prefix;
		  	$tables = array();
		  	$tables = array(
		  		// $tbl_prefix.'afl_variable',
		  		$tbl_prefix.'afl_business_funds',
		  		$tbl_prefix.'afl_business_transactions',
		  		$tbl_prefix.'afl_business_transactions_overview',
		  		$tbl_prefix.'afl_payout_history',
		  		$tbl_prefix.'afl_payout_requests',
		  		$tbl_prefix.'afl_ranks',
		  		$tbl_prefix.'afl_rank_history',
		  		$tbl_prefix.'afl_transactions',
		  		$tbl_prefix.'afl_transactions_errors',
		  		$tbl_prefix.'afl_transaction_authorization',
		  		$tbl_prefix.'afl_user_fund',
		  		
		  		$tbl_prefix.'afl_user_downlines',
		  		$tbl_prefix.'afl_unilevel_user_downlines',

		  		$tbl_prefix.'afl_user_genealogy',
		  		$tbl_prefix.'afl_unilevel_user_genealogy',

		  		$tbl_prefix.'afl_user_holding_tank',
		  		$tbl_prefix.'afl_unilevel_user_holding_tank',

		  		$tbl_prefix.'afl_user_payment_methods',
		  		$tbl_prefix.'afl_user_transactions',
		  		$tbl_prefix.'afl_user_transactions_overview',
		  		
		  		$tbl_prefix.'afl_purchases',
		  		
		  		$tbl_prefix.'afl_tree_last_insertion_positions',
		  		$tbl_prefix.'afl_unilevel_tree_last_insertion_positions',

		  		$tbl_prefix.'afl_customer',
		  		$tbl_prefix.'afl_log_messages',
		  		$tbl_prefix.'afl_global_pool_bonus_transactions',
		  		$tbl_prefix.'afl_processing_queue',
		  		$tbl_prefix.'afl_referal_downlines',
		  		
		  		$tbl_prefix.'afl_bonus_incentive_history',
		  		// $tbl_prefix.'tmp_table',
		  		// $tbl_prefix.'tmp_table_down',
		  	);
		  return $tables;
		  }
	}

/*
 * ------------------------------------------------------------
 * Un-Install functions and Features 
 * ------------------------------------------------------------
*/
	function eps_affiliates_uninstall() {
		$obj = new Eps_affiliates_uninstall();
	}

	
