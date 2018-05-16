<?php
/** 
 * --------------------------------------------------------------------------
 * @author < pratheeshepixelsolutions.com >
 *
 * Here calculates the matrix compensation for users
 * --------------------------------------------------------------------------
*/
 function _calculate_matrix_compensation () {
 		$this_mnth_actived_members = [];

 		$matrix_given_date 	= afl_variable_get('matrix_compensation_given_day', 1);
	 	$current_date				= afl_date();
	 	$afl_date_splits 		= afl_date_splits(afl_date());

	 	if ( $afl_date_splits['d'] == $matrix_given_date) {



	 		/*$query = array();
	 		$query['#select'] = _table_name('afl_user_genealogy');
	 		$query['#where']  = array(
	 			'deleted = 0',
	 			'status = 1'
	 		);
			// $query['#limit'] 		 = 100;
	 		$users  = db_select($query, 'get_results');*/

	 		//get user list they having distributor kit this month from purchases
	 		$afl_date = afl_date();
	 		$afl_date_splits = afl_date_splits($afl_date);

	 		$query['#select'] = _table_name('afl_purchases');
	 		$query['#where']  = array(
	 			'category = "Distributor Kit"',
	 			'purchase_month = '.$afl_date_splits['m'],
	 			'purchase_year = '.$afl_date_splits['y'],
	 		);
	 		$users  = db_select($query, 'get_results');

	 		foreach ($users as $key => $user) {
	 			$this_mnth_actived_members[] = $user->uid;

	 		/*
	 		 * -----------------------------------------------------
	 		 * IF a user status is 1 then check the months he actived 
	 		 * 
	 		 * If user status is 0, check howmany months he has been,
	 		 * actived then give the commission only once
	 		 * -----------------------------------------------------
	 		*/
	 			$months_actived = 0;
	 			/*$actived_on  		= $user->actived_on;
	 			if(is_numeric($actived_on)) {
		 			$diff						= $current_date - $actived_on;
		 			$years 					= floor($diff / (365*60*60*24));
		 			$months_actived = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		 			$months_actived = $months_actived + 0;
	 			}*/
	 			$maximum_period = afl_variable_get('matrix_compensation_period_maximum', 3);

	 			// pr($actived_on);
	 			$months_actived = _get_howmany_months_user_active($user->uid);
	 			if (!empty($months_actived)) {
	 				//check the difference greater than maximum period
	 				//if yes, taken the maximum
	 				if ($months_actived > $maximum_period) {
	 					$months_actived = $maximum_period;
	 				}
	 				//get the countof actived downlines under this user
			 		//only get the users under 9 level
			 		$max_level  = afl_variable_get('matrix_compensation_max_level', 9);

			 		/**
			 		 * 
			 		 * How Paid (Visual Representation / Potential)
			 		 * 
			 		 * Sponsor Count Vs Level
			 		 */

						$max_level = _get_max_level_of_user($user->uid, $max_level);
            // var_dump($max_level);

            if(!$max_level) {
            	continue;
            }
          
          $prev_date = strtotime('-1 month',afl_date());
          $prev_month_split = afl_date_splits($prev_date);

          //get the actived distributos prev month under this user
          $downline_distribs = _get_downline_distributors_($user->uid, 'matrix');
          $query['#select'] = _table_name('afl_purchases');
			 		$query['#where']  = array(
			 			'category = "Distributor Kit"',
			 			'purchase_month = '.$prev_month_split['m'],
			 			'purchase_year = '.$prev_month_split['y'],
			 		);
			 		$query['#where_in'] = [
			 			'uid' =>  $downline_distribs
			 		];
			 		$query['#expression'] = array(
			 			'COUNT(`'._table_name('afl_purchases').'`.`uid`) as count'
			 		);
			 		$respo  = db_select($query, 'get_row');
			 		
	 				/*$query = array();
			 		$query['#select'] = _table_name('afl_user_downlines');
			 		$query['#join'] 	= array(
			 			_table_name('afl_user_genealogy') => array(
			 				'#condition' => '`'._table_name('afl_user_downlines').'`.`downline_user_id` = `'._table_name('afl_user_genealogy').'`.`uid`'
			 			),
			 			_table_name('afl_purchases') => array(
			 				'#condition' => '`'._table_name('afl_purchases').'`.`uid` = `'._table_name('afl_user_downlines').'`.`downline_user_id`'
			 			),
			 		);

			 		$query['#where']  = array(
			 			'`'._table_name('afl_user_genealogy').'`.`status` = 1',
			 			'`'._table_name('afl_user_downlines').'`.`uid` = '.$user->uid,
			 			'`'._table_name('afl_user_downlines').'`.`level` <='.$max_level,
			 			'`'._table_name('afl_purchases').'`.`category` ="Distributor Kit"',
			 			'`'._table_name('afl_purchases').'`.`purchase_month` ='.$afl_date_splits['m'],
			 			'`'._table_name('afl_purchases').'`.`purchase_year` ='.$afl_date_splits['y'],
			 		);
			 		$query['#expression'] = array(
			 			'COUNT(`'._table_name('afl_user_genealogy').'`.`uid`) as count'
			 		);

			 		$respo = db_select($query, 'get_row');*/
	
			 		$count = !empty($respo->count) ? $respo->count : 0;

			 		/**
			 		 * 
			 		 * Get Inactive Downlines
			 		 * 
			 		 */
			 		$inactive_count = _afl_inactive_user_downline_count($user->uid, $max_level);
			 		
			 		// $count += $inactive_count;

			 		//get the bonus for the $months_actived 
			 		$amount_for_actived_month = afl_variable_get('month_'.$months_actived.'_matrix_compensation', 0);
			 		$user_amount = $amount_for_actived_month * $count;

			 		if ( $user_amount > 0) {
			 			 $transaction = array();
					   $transaction['uid'] 								= $user->uid;
					   $transaction['associated_user_id'] = $user->uid;
					   $transaction['payout_id'] 					= 0;
					   $transaction['level']							= 0;
					   $transaction['currency_code'] 			= afl_currency();
					   $transaction['order_id'] 					= 1;
					   $transaction['int_payout'] 				= 0;
					   $transaction['hidden_transaction'] = 0;
					   $transaction['credit_status'] 			= 1;
					   $transaction['amount_paid'] 				= afl_commerce_amount($user_amount);
					   $transaction['category'] 					= 'MATRIX COMPENSATION';
					   $transaction['notes'] 							= 'Matrix compensation for '.$months_actived.' actived months having '.$count.' actived distributors On '.$prev_month_split['m'].'/'.$prev_month_split['y'];

					   //check already paid
					   $query = array();
					   $afl_date_splits = afl_date_splits(afl_date());
					   // $query['#select'] = _table_name('afl_user_transactions');
					   $query['#select'] = _table_name('afl_user_holding_transactions');
					   $query['#where'] = array(
					   	'`uid`='.$user->uid,
					   	'`category` = "MATRIX COMPENSATION"',
					   	'`transaction_month` = '.$afl_date_splits['m'],
					   	'`transaction_year` = '.$afl_date_splits['y'],
					   );
					   $check = db_select($query, 'get_row');

					   if ( empty($check) ){
					   	 afl_member_holding_transaction($transaction, FALSE, FALSE);

						  /* $b_transactions['category'] 						= 'MATRIX COMPENSATION';
						   $b_transactions['additional_notes']		= 'Matrix compensation';
						   $b_transactions['uid'] 								= $user->uid;
						   $b_transactions['associated_user_id'] 	= $user->uid;
						   $b_transactions['credit_status'] 			= 0;
						   $b_transactions['amount_paid'] 				= afl_commerce_amount($user_amount);
						   $b_transactions['notes'] 							= 'Matrix compensation for '.$months_actived.' actived months';
						   $b_transactions['currency_code'] 			= afl_currency();
						   $b_transactions['order_id'] 						= 1;*/
						   // afl_business_transaction($b_transactions);
					   }
			 		}
	 			}
	 		}

			//block the users NOT IN $this_mnth_actived_members , 
			/*if ( !empty($this_mnth_actived_members)) {
				$update_query['#table'] = _table_name('afl_user_genealogy');
				$update_query['#fields'] = [
					'status' => 0,
					'deactived_on' => afl_date()
				];
				$update_query['#where_not_in'] = [
					'uid' => $this_mnth_actived_members
				];
				db_update($update_query);
			}*/
	 		//log cron run
		 	if ( afl_variable_get('cron_logs_enable')) {
				afl_log('matrix_compensation_payout_scheduler','cron run completed',array(),LOGS_INFO);
		 	}
	 	}

 }


 /**
  * _afl_inactive_user_downline_count
  * 
  * 
  * @param $uid User Id
  * 
  * @return $count Count of Personaly Sponsored Members of inactive downlines of current user
  * 
  */

 function _afl_inactive_user_downline_count($uid = NULL, $max_level) {

 	if(!$uid) {return 0;}
	global $wpdb;

	$afl_user_downlines = _table_name('afl_user_downlines');
	$afl_user_genealogy = _table_name('afl_user_genealogy');
	// $max_level  = afl_variable_get('matrix_compensation_max_level', 9);


	/*$query_inactive_downlines = array();
	$query_inactive_downlines['#select'] = $afl_user_downlines;
	$query_inactive_downlines['#join'] 	= array(
		$afl_user_genealogy => array(
			'#condition' => '`'.$afl_user_downlines.'`.`downline_user_id` = `'.$afl_user_genealogy.'`.`uid`'
		)
	);
	//only get the users under 9 level
	$query_inactive_downlines['#where']  = array(
		'`'.$afl_user_genealogy.'`.`status` = 0',
		'`'.$afl_user_downlines.'`.`uid` = '.$uid,
		'`'.$afl_user_downlines.'`.`level` <='.$max_level,

	);

	$query_inactive_downlines['#fields'] = array(
		$afl_user_genealogy => array('uid'),
		);


	$inactive_user_downlies = db_select($query_inactive_downlines, 'get_results');*/

	$sql = 'SELECT `'.$afl_user_genealogy.'`.`uid` FROM `'.$afl_user_downlines.'`  JOIN `'.$afl_user_genealogy.'` ON `'.$afl_user_downlines.'`.`downline_user_id` = `'.$afl_user_genealogy.'`.`uid` WHERE `'.$afl_user_genealogy.'`.`status` = 0  AND `'.$afl_user_downlines.'`.`uid` = '.$uid.'  AND `'.$afl_user_downlines.'`.`level` <='.$max_level; 

	$inactive_downlines = array_keys($wpdb->get_results($sql, 'OBJECT_K'));

	$total_count = 0;

	foreach ($inactive_downlines as $key => $value) {
		$max_level = _get_max_level_of_user($value);
		$sql1 = 'SELECT COUNT(`'.$afl_user_genealogy.'`.`uid`) AS downline_count FROM `'.$afl_user_downlines.'`  JOIN `'.$afl_user_genealogy.'` ON `'.$afl_user_downlines.'`.`downline_user_id` = `'.$afl_user_genealogy.'`.`uid` WHERE `'.$afl_user_genealogy.'`.`status` = 1  AND `'.$afl_user_downlines.'`.`uid` = '.$value.'  AND `'.$afl_user_downlines.'`.`level` <='.$max_level; 
		$result = $wpdb->get_row($sql1);
		$active_user_count = $result->downline_count;

		$total_count += $active_user_count;
	}

	return $total_count;
 }





 function _get_max_level_of_user($uid = NULL, $max_level = NULL) {
 	if(!$uid) {
 		return 0;
 	}

 	if(!$max_level) {
 		$max_level  = afl_variable_get('matrix_compensation_max_level', 9);
 	}


	$afl_user_genealogy = _table_name('afl_user_genealogy');

	$query = array();
  $query['#select'] =  $afl_user_genealogy;
  $query['#where']  = array(
    $afl_user_genealogy.'.referrer_uid='.$uid,
  );
  $query['#expression']  = array(
    'COUNT(uid) AS sponsor_count'
    );

  $query['#fields']  = array(
    $afl_user_genealogy	 => array('referrer_uid')
    );
  $referal_downlines  = db_select($query, 'get_row');

  $sponsor_count = 0;
  if(isset($referal_downlines->sponsor_count) && !empty($referal_downlines->sponsor_count)) {
  	$sponsor_count = $referal_downlines->sponsor_count;
  }

  // pr('$sponsor_count - '.$sponsor_count);

 
  for ($i=1; $i <= $max_level ; $i++) { 
    $afl_min_referal_count = afl_variable_get('matrix_compensation_lvl_'.$i.'_min_spons',0);
    // pr($afl_min_referal_count);
    if($sponsor_count < $afl_min_referal_count) {
      // pr('here');
      $max_level = $i-1;
      break;
    }
  }
  return $max_level;
 }

