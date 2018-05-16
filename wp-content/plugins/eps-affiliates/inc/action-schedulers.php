<?php
	/**
	 * @author < pratheesh@epixelsolutions.com >
	 *
	 * Here gives all the cron jobs for our plugin
	 *
	 *
	 *
	 *
	 *
	*/

/*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function eps_affiliates_holding_tank_user_expiry_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_holding_tank_user_expiry_scheduler' ) ) {  
		   wp_schedule_event( time(), 'every_minute', 'eps_affiliates_holding_tank_user_expiry_scheduler' );  
		}
	}

/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function eps_affiliates_holding_tank_user_expiry_deactivate() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_holding_tank_user_expiry_scheduler');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_holding_tank_user_expiry_scheduler');
	} 
/*
 * -------------------------------------------------------------
 * here's the function we'd like to call with our cron job
 * -------------------------------------------------------------
 * 
 * set the users remaining days in the holding tank
 *
 * If the remaining day is 0, means he expires from the holding tank,
 * get that user and frocelly place that user to the tree
 *
*/
	function eps_affiliates_holding_tank_user_expiry_cron_callback() {
		require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/cron_events/matrix/holding-tank-expiry-check.php';
		if (function_exists('_check_holding_tank_expiry')) {
			_check_holding_tank_expiry();
		}
	}



/*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function eps_affiliates_unilevel_holding_tank_user_expiry_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_unilevel_holding_tank_user_expiry_scheduler' ) ) {  
		   wp_schedule_event( time(), 'every_minute', 'eps_affiliates_unilevel_holding_tank_user_expiry_scheduler' );  
		}
	}

/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function eps_affiliates_unilevel_holding_tank_user_expiry_deactivate() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_unilevel_holding_tank_user_expiry_scheduler');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_unilevel_holding_tank_user_expiry_scheduler');
	} 
/*
 * -------------------------------------------------------------
 * here's the function we'd like to call with our cron job
 * -------------------------------------------------------------
 * 
 * set the users remaining days in the holding tank
 *
 * If the remaining day is 0, means he expires from the holding tank,
 * get that user and frocelly place that user to the tree
 *
*/
	function eps_affiliates_unilevel_holding_tank_user_expiry_cron_callback() {
		require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/cron_events/unilevel/holding-tank-expiry-check.php';
		if (function_exists('_unilevel_check_holding_tank_expiry')) {
			_unilevel_check_holding_tank_expiry();
		}
	}



/*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function eps_affiliates_monthly_matrix_compensation_payout_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_monthly_matrix_compensation_payout' ) ) {  
		   wp_schedule_event( time(), 'every_minute', 'eps_affiliates_monthly_matrix_compensation_payout' );  
		}
	}
/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function eps_affiliates_monthly_matrix_compensation_payout_deactivation() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_monthly_matrix_compensation_payout');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_monthly_matrix_compensation_payout');
	} 
/*
 * ------------------------------------------------------------
 * Monthly matrix commision payout
 *
 * check month starting
 * get all active users
 * get total actived month of a user
 * get actived downlines of this user
 * give count * actived month count amount
 * ------------------------------------------------------------
*/
// monthly_matrix_compensation_payout_cron_job_callback();
 function eps_affiliates_monthly_matrix_compensation_payout_cron_callback () {
	 	require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/cron_events/matrix/matrix-compensation-bonus-calculation.php';
		if (function_exists('_calculate_matrix_compensation')) {
			_calculate_matrix_compensation();
		}
 }


 /*
 * ------------------------------------------------------------
 * commision payout from holding payouts on every 15th
 *
 * ------------------------------------------------------------
*/
 function eps_affiliates_monthly_release_holding_bonus_payouts_cron_callback () {
	 	require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/cron_events/matrix/matrix-compensation-holding-bonus-payout.php';
		if (function_exists('_release_holding_bonus_payouts')) {
			_release_holding_bonus_payouts();
		}
 }


/*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function eps_affiliates_monthly_release_holding_bonus_payouts_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_monthly_release_holding_bonus_payouts' ) ) {  
		   wp_schedule_event( time(), 'every_minute', 'eps_affiliates_monthly_release_holding_bonus_payouts' );  
		}
	}


	/*
	 * -------------------------------------------------------------
	 * unschedule event upon plugin deactivation
	 * -------------------------------------------------------------
	*/
	function eps_affiliates_monthly_release_holding_bonus_payouts_deactivation() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_monthly_release_holding_bonus_payouts');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_monthly_release_holding_bonus_payouts');
	} 


/*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function eps_affiliates_monthly_pool_bonus_payout_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_monthly_pool_bonus_payout' ) ) {  
		   wp_schedule_event( time(), 'every_minute', 'eps_affiliates_monthly_pool_bonus_payout' );  
		}
	}
/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function eps_affiliates_monthly_pool_bonus_payout_deactivation() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_monthly_pool_bonus_payout');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_monthly_pool_bonus_payout');
	} 
/*
 * -------------------------------------------------------------
 * Monthly sales pool bonus calculation callback
 * -------------------------------------------------------------
 * 
 * Get the monthly profit 
 * get maximum rank
 * check the count of each rank occured users
 * get sales under their downlines and only take the maximum amount
 * calulate the % for each ranked member based on their total purchase
 * -------------------------------------------------------------
*/
 function eps_affiliates_monthly_pool_bonus_payout_cron_callback () {
	require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/cron_events/matrix/global-pool-bonus-calculation.php';
	if (function_exists('_calculate_global_pool_bonus')) {
		_calculate_global_pool_bonus();
	}

 }




 /*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	/*function eps_affiliates_remote_users_embedd_cron_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_remote_users_embedd_cron' ) ) {  
		   wp_schedule_event( time(), 'every_minute', 'eps_affiliates_remote_users_embedd_cron' );  
		}
	}*/
/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	/*function eps_affiliates_remote_users_embedd_cron_deactivation() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_remote_users_embedd_cron');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_remote_users_embedd_cron');
	} */
/*
 * ------------------------------------------------------------
 * Monthly matrix commision payout
 *
 * check month starting
 * get all active users
 * get total actived month of a user
 * get actived downlines of this user
 * give count * actived month count amount
 * ------------------------------------------------------------
*/

 /*function eps_affiliates_remote_users_embedd_cron_callback () {
 	//check the configuration for processing import remote user
 	if ( afl_variable_get('afl_enable_que_processing')) {
	 	require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/API/api-remote-user-embedd-cron-callback.php';
		if (function_exists('_process_embedd_users_queue')) {
			_process_embedd_users_queue();
		}
 	}
 }*/






 /*
 * -------------------------------------------------------------
 * Open the deactive user spot for another one activation
 * -------------------------------------------------------------
*/
	/*function eps_affiliates_deactived_spot_openup_cron_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_deactived_spot_openup_cron' ) ) {  
		   wp_schedule_event( time(), 'every_minute', 'eps_affiliates_deactived_spot_openup_cron' );  
		}
	}*/
/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	/*function eps_affiliates_deactived_spot_openup_cron_deactivation() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_deactived_spot_openup_cron');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_deactived_spot_openup_cron');
	} */
/*
 * ------------------------------------------------------------
 * Monthly matrix commision payout
 *
 * check month starting
 * get all active users
 * get total actived month of a user
 * get actived downlines of this user
 * give count * actived month count amount
 * ------------------------------------------------------------
*/
/* function eps_affiliates_deactived_spot_openup_cron_callback () {
	 	require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/cron_events/matrix/inactive-user-spot-openup.php';
		if (function_exists('_matrix_inactive_user_spot_open_up')) {
			_matrix_inactive_user_spot_open_up();
		}
 }
*/





/*
 * -------------------------------------------------------------
 * Open the deactive incentive calcultion activation
 * -------------------------------------------------------------
*/
	function eps_affiliates_bonus_incentive_cron_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_bonus_incentive_cron' ) ) {  
		   wp_schedule_event( time(), 'every_minute', 'eps_affiliates_bonus_incentive_cron' );  
		}
	}
/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function eps_affiliates_bonus_incentive_cron_deactivation() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_bonus_incentive_cron');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_bonus_incentive_cron');
	} 
/*
 * ------------------------------------------------------------
 * Bonus incentive calculation
 *
 * Give an incentive for the rank 
 * ------------------------------------------------------------
*/
 function eps_affiliates_bonus_incentive_cron_callback () {
	 	require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/cron_events/common/bonus-incentive-calculation.php';
		if (function_exists('_member_bonus_incentive_calculation')) {
			_member_bonus_incentive_calculation();
		}
 }





/*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function eps_affiliates_unilevel_rank_calculation_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_unilevel_rank_calculation_scheduler' ) ) {  
		   wp_schedule_event( time(), 'every_minute', 'eps_affiliates_unilevel_rank_calculation_scheduler' );  
		}
	}

/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function eps_affiliates_unilevel_rank_calculation_deactivate() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_unilevel_rank_calculation_scheduler');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_unilevel_rank_calculation_scheduler');
	} 
/*
 * -------------------------------------------------------------
 * here's the function we'd like to call with our cron job
 * -------------------------------------------------------------
 * 
 * set the users remaining days in the holding tank
 *
 * If the remaining day is 0, means he expires from the holding tank,
 * get that user and frocelly place that user to the tree
 *
*/
	function eps_affiliates_unilevel_rank_calculation_scheduler_callback() {
		require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/cron_events/unilevel/rank-calculation.php';
		if (function_exists('_rank_calculation_scheduler_callback')) {
			_rank_calculation_scheduler_callback();
		}
	}





/*
 * -------------------------------------------------------------
 * Custom interval
 * -------------------------------------------------------------
*/
	function cron_add_hour( $schedules ) {
	    $schedules['six_mins'] = array(
		    'interval' => 360,
		    'display' => __( 'Six minute' )
	    );
     $schedules['two_mins'] = array(
		    'interval' => 120,
		    'display' => __( '2 Minute' )
	    );
	    $schedules['eps_queue_processing'] = array(
		    'interval' => 300,
		    'display' => __( 'Every 5 minute' )
	    );
	    return $schedules;
	}
	add_filter( 'cron_schedules', 'cron_add_hour' );



/*
 * -------------------------------------------------------------
 * All the scheduler activation hook callback comes here
 * and make sure it's called whenever WordPress loads
 * -------------------------------------------------------------
*/
	add_action('wp', 'eps_affiliates_holding_tank_user_expiry_activation');
	
	add_action('wp', 'eps_affiliates_unilevel_holding_tank_user_expiry_activation');
	
	add_action('wp', 'eps_affiliates_monthly_matrix_compensation_payout_activation');

	add_action('wp', 'eps_affiliates_monthly_pool_bonus_payout_activation');

	// add_action('wp', 'eps_affiliates_remote_users_embedd_cron_activation');

	// add_action('wp', 'eps_affiliates_deactived_spot_openup_cron_activation');
	
	add_action('wp', 'eps_affiliates_bonus_incentive_cron_activation');

	add_action('wp', 'eps_affiliates_monthly_release_holding_bonus_payouts_activation');
	
	add_action('wp', 'eps_affiliates_unilevel_rank_calculation_activation');
/*
 * -------------------------------------------------------------
 * All the scheduler deactivation hooks comes here
 * callback function when the plugin deactivated
 * -------------------------------------------------------------
*/
	register_deactivation_hook (__FILE__, 'eps_affiliates_holding_tank_user_expiry_deactivate');
	register_deactivation_hook (__FILE__, 'eps_affiliates_unilevel_holding_tank_user_expiry_deactivate');
	register_deactivation_hook (__FILE__, 'eps_affiliates_monthly_matrix_compensation_payout_deactivation');
	// register_deactivation_hook (__FILE__, 'eps_affiliates_monthly_pool_bonus_payout_deactivation');
	// register_deactivation_hook (__FILE__, 'eps_affiliates_remote_users_embedd_cron_deactivation');
	register_deactivation_hook (__FILE__, 'eps_affiliates_deactived_spot_openup_cron_deactivation');
	register_deactivation_hook (__FILE__, 'eps_affiliates_bonus_incentive_cron_deactivation');
	
	register_deactivation_hook (__FILE__, 'eps_affiliates_monthly_release_holding_bonus_payouts_deactivation');
	register_deactivation_hook (__FILE__, 'eps_affiliates_unilevel_rank_calculation_deactivate');


/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * check the user expired from the holding tank, if yes auto place
 * user under sponsor
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_holding_tank_user_expiry_scheduler', 'eps_affiliates_holding_tank_user_expiry_cron_callback');

/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * check the user expired from the holding tank, if yes auto place
 * user under sponsor
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_unilevel_holding_tank_user_expiry_scheduler', 'eps_affiliates_unilevel_holding_tank_user_expiry_cron_callback');
/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * Here calculate the monthly matrix bonus compemsation
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_monthly_matrix_compensation_payout', 'eps_affiliates_monthly_matrix_compensation_payout_cron_callback');

/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * Here calculate the pool bonus for each and every  user
 * Monthly calculation
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_monthly_pool_bonus_payout', 'eps_affiliates_monthly_pool_bonus_payout_cron_callback');
/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * Here get the remoet users from the queue
 * every 5 mins
 * -------------------------------------------------------------
*/
	// add_action ('eps_affiliates_remote_users_embedd_cron', 'eps_affiliates_remote_users_embedd_cron_callback');

/*
 * -------------------------------------------------------------
 * Open the inactived user spot for another user, when he has 
 * been in deatived for maximum allowed days / months/year
 * -------------------------------------------------------------
*/
	// add_action ('eps_affiliates_deactived_spot_openup_cron', 'eps_affiliates_deactived_spot_openup_cron_callback');

/*
 * -------------------------------------------------------------
 * After a particular time period check the users rank and 
 * give the incentive
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_bonus_incentive_cron', 'eps_affiliates_bonus_incentive_cron_callback');




/*
 * -------------------------------------------------------------
 * Holding payout release
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_monthly_release_holding_bonus_payouts', 'eps_affiliates_monthly_release_holding_bonus_payouts_cron_callback');


/*
 * -------------------------------------------------------------
 * calculate rank 
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_unilevel_rank_calculation_scheduler', 'eps_affiliates_unilevel_rank_calculation_scheduler_callback');