<?php 
/*
 * ---------------------------------------------------
 * Calculate the rank of user and uplines 
 * ---------------------------------------------------
*/
	function _rank_calculation_scheduler_callback () {

		$afl_date = afl_date();
		//get the purchase details today
		$query = array();
	  $query['#select'] = _table_name('afl_purchases');
	  $query['#fields'] = array(
	  	_table_name('afl_purchases') => array('uid','afl_purchases_id')
	  );
	  $query['#join']  = array(
     _table_name('afl_unilevel_user_genealogy') => array(
     		'#condition' => _table_name('afl_purchases').'.`uid`'.'='._table_name('afl_unilevel_user_genealogy').'.`uid`'
    	)
  	);
	  $query['#where'] = array(
	    '`'._table_name('afl_purchases').'`.`cron_status` != 2',
	    // '`'._table_name('afl_purchases').'`.`category` = "Package Purchase"',
	  );
	  $query['#limit'] = 500;
   	$data = db_select($query, 'get_results');
   	
   	foreach ($data as $key => $value) {
   		try{
   			_change_cron_status($value->afl_purchases_id,$value->uid, 1);
	   			_recursive_calc_user_rank( $value->uid );
	   			_recursive_calc_user_rank_parents( $value->uid );
	   		_change_cron_status($value->afl_purchases_id,$value->uid, 2);
   		} catch(Exception $e){
   			afl_log('cron_rank_updation','Error %er',array('%er'=>print_r($e,TRUE)),LOGS_ERROR);
	   		_change_cron_status($value->afl_purchases_id,$value->uid, -1);
   		}
   		

   	}
   	//log cron run
   	if ( afl_variable_get('cron_logs_enable')) {
			afl_log('rank_calculation_scheduler','cron run completed',array(),LOGS_INFO);
   	}
	}
/**
 * -------------------------------------------------------
 * This dunction will be update the referrer rank 
 * recursivly
 * -------------------------------------------------------
*/
	function _recursive_calc_user_rank ( $uid = ''){
		//if uid
		if ( $uid)  {
			do_action('eps_affiliates_calculate_affiliate_rank',$uid);
		}

		//get the sponsor id
		$node = afl_genealogy_node($uid, 'unilevel');
		if ( isset($node->referrer_uid) &&  $node->referrer_uid) {
			_recursive_calc_user_rank( $node->referrer_uid );
		}

	}

/**
 * -------------------------------------------------------
 * This dunction will be update the parents rank 
 * recursivly
 * -------------------------------------------------------
*/
	function _recursive_calc_user_rank_parents ( $uid = '' ) {
		if ( $uid)  {
			do_action('eps_affiliates_calculate_affiliate_rank',$uid);
		}

		//get the sponsor id
		$node = afl_genealogy_node($uid, 'unilevel');
		if ( isset($node->parent_uid) && $node->parent_uid) {
			_recursive_calc_user_rank( $node->parent_uid );
		}
	}


	function _change_cron_status ($afl_purchases_id, $uid, $status){
 	global $wpdb;

		$update_id = $wpdb->update(
			_table_name('afl_purchases'),
			array(
				'cron_status' => $status
			),
			array(
				'uid' => $uid,
				'afl_purchases_id' => $afl_purchases_id
			)
		);
	}