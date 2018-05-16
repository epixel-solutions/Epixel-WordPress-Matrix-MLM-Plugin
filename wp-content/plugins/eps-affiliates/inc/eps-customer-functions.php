<?php
/*
 * ----------------------------------------------------
 * Get the uids of customers of a user
 * ----------------------------------------------------
*/
 function _my_customers_uids($uid = '') {
 	if ( $uid == '') {
 		$uid = get_uid();
 	}

	$query = array();
	$query['#select'] = _table_name('afl_customer');
	$query['#join']   = array(
		_table_name('users') => array(
				'#condition' => '`'._table_name('users').'`.`ID`=`'._table_name('afl_customer').'`.`uid`'
			),
		_table_name('afl_unilevel_user_genealogy') => array(
			'#condition' => '`'._table_name('afl_unilevel_user_genealogy').'`.`uid`=`'._table_name('afl_customer').'`.`uid`'
		)
	);
	$query['#where'] = array(
		'`'._table_name('afl_customer').'`.`referrer_uid` ='.$uid
	);
	$query['#fields'] = array(
		_table_name('users') => array('ID'),
	);
	if (!empty($limit) ) {
		$query['#limit'] = $index.','.$limit;
	}
	$res = db_select($query, 'get_results');
	return $res;
 }
 /*
 * ----------------------------------------------------
 * Get the uids of customers of a user
 * ----------------------------------------------------
*/
 function _my_downline_customers_uids($uid = '') {
 	if ( $uid == '') {
 		$uid = get_uid();
 	}

	$query = array();
	$query['#select'] = _table_name('afl_customer');
	$query['#join']   = array(
		_table_name('users') => array(
				'#condition' => '`'._table_name('users').'`.`ID`=`'._table_name('afl_customer').'`.`uid`'
			),
		_table_name('afl_unilevel_user_downlines') => array(
			'#condition' => '`'._table_name('afl_unilevel_user_downlines').'`.`downline_user_id`=`'._table_name('afl_customer').'`.`uid`'
		)
	);
	$query['#where'] = array(
		'`'._table_name('afl_unilevel_user_downlines').'`.`uid` ='.$uid
	);
	$query['#fields'] = array(
		_table_name('users') => array('ID'),
	);
	if (!empty($limit) ) {
		$query['#limit'] = $index.','.$limit;
	}
	$res = db_select($query, 'get_results');
	return $res;
 }
/*
 * --------------------------------------------------
 * My downline customers
 * --------------------------------------------------
*/
 function get_user_downline_customers($uid = ''){
 	if ( empty($uid)) {
 		$uid = get_uid();
 	}
 	$downlines 		= afl_get_unilevel_user_downlines_uid($uid);
 	$downlines 		= array_ret($downlines,'downline_user_id');
 	$downlines[]  = $uid;
 	$query = array();
 	$query['#select'] = _table_name('afl_customer');
 	$query['#fields'] = array(
 		_table_name('afl_customer') => array('uid')
 	); 
 	$query['#where_in'] = array(
 		'uid' => $downlines
 	);
 	$result = db_select($query, 'get_results');
 	return $result;
 }
/*
 * ---------------------------------------------------
 * Customer sales of a user
 * ---------------------------------------------------
*/
 function get_user_downline_customers_sales($uid = '', $sum = FALSE){
 	if ( empty( $uid ) ) {
 		$uid = get_uid();
 	}
 	//downlines cstomers
 	$customers  = array_ret(get_user_downline_customers($uid), 'uid');
 	// pr($customers);
 	
 	
 	//get purchases
 	$query = array();
 	$downline_sales = array();
 	if ( !empty($customers)) {
 		$downline_sales = get_purchases('',$customers,$sum);
 	}
 	if ( $sum ) {
 		return (!empty($downline_sales[0]->points) ? afl_get_payment_amount($downline_sales[0]->points) : 0);
 	}
 	return $downline_sales;
 }

/*
 * --------------------------------------------------
 * Get purchase details of a user or group of users
 * -------------------------------------------------
*/
 	function get_purchases($uid = '', $uids = array(), $ret_sum = FALSE, $ret_uids = FALSE){
 		$query = array();
 		$query['#select'] = _table_name('afl_purchases');
 		if ( !empty($uid) ) {
 			$query['#where'] = array(
 				'`'._table_name('afl_purchases').'`.`uid`='.$uid
 			);
 		}

 		if ( !empty($uids) && empty($uid)) {
 			$query['#where_in'] = array(
 				'uid' => $uids
 			);
 		}

 		if ( $ret_uids ) {
 			$query['#fields'] = array(
 				_table_name('afl_purchases') => array('uid')
 			);
 		}
 		if ( $ret_sum ) {
 			$query['#expression'] = array(
 				'SUM(afl_points) as points'
 			);
 		}
 		// pr($query);
 		$result = db_select($query, 'get_results');
 		return $result;
 	}

 function _user_customers_sales($uid = ''){
 	 	if ( empty($uid) ) {
 		$uid = get_uid();
 	}

 	//get downline user id
 	$uids_array = _my_customers_uids($uid);
 	$uids = array();
 	foreach ($uids_array as $key => $value) {
 		$uids[] = $value->ID;
 	}

 	pr($uids);
 }