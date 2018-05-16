<?php
require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/afl_payment_funs.php';
function _calculate_global_pool_bonus () {
	if (afl_variable_get('afl_enable_pool_bonus')) {

	 	$max_rank 			= afl_variable_get('number_of_ranks');
	 	$monthly_profit = _get_company_profit('monthly');
	 	$monthly_profit = !empty($monthly_profit->balance) ? $monthly_profit->balance : 0;
	 	$monthly_profit = afl_get_payment_amount($monthly_profit);
	 	
	 	if ( $max_rank ) {
	 		if ( !empty($monthly_profit)) {
	 			for ($i = $max_rank; $i > 0 ; $i--) { 
	 				//get % and maximum leg taken amount of this rank ($i)
	 				$percentage = afl_variable_get('pool_bonus_percentage_rank_'.$i, 0);
	 				$max_taken  = afl_variable_get('pool_bonus_maximum_amount_rank_'.$i, 0); 
	 				
	 				/**
				 	 * -------------------------------------------------------------
	 				 * @var $commission : the commission amount given for all the user having rank $i
				 	 * -------------------------------------------------------------
	 				*/ $commission = afl_commission($percentage.'%',$monthly_profit);
	 				


	 				//get all users having this rank and their sum of purchases
	 				$query = array();
	 				$query['#select']  =_table_name('afl_purchases');
	 				$query['#join'] 	 = array(
	 					_table_name('afl_user_genealogy') => array(
	 						'#condition' => '`'._table_name('afl_user_genealogy').'`.`uid` =`'._table_name('afl_purchases').'`.`uid` '
	 					)
	 				);
	 				$query['#fields'] = array(
	 					_table_name('afl_user_genealogy') => array('uid', 'member_rank'),
	 				);
	 				$query['#where'] = array(
	 					'`'._table_name('afl_user_genealogy').'`.`member_rank` = '.$i
	 				);
	 				$query['#group_by'] = array(
	 					'`'._table_name('afl_user_genealogy').'`.`uid`'
	 				);
				/*
				 * -------------------------------------------------------------
				 * Check the total purchase of user is greater than maximum taken,
				 * If yes take the maximum
				 * Else take the user sales amount
				 * -------------------------------------------------------------
				*/
	 				$query['#expression'] = array(
	 					'IF((SUM(`'._table_name('afl_purchases').'`.`amount_paid`)) >= '.afl_commerce_amount($max_taken).','.afl_commerce_amount($max_taken).',(SUM(`'._table_name('afl_purchases').'`.`amount_paid`))) as total_purchase'
	 					// 'SUM(`'._table_name('afl_purchases').'`.`amount_paid`) as total_purchase'
	 				);

	 				$users_purchases = db_select($query, 'get_results');
	 				$count_of_users  = count($users_purchases);
				/*
				 * --------------------------------------------------------------
				 * Here we get a list of users 
				 * Array
				 *	(
				 *   [0] => stdClass Object
				 *      (
				 *       [uid] => 952
				 *       [member_rank] => 2
				 *       [total_purchase] => 0
				 *      )
	 			 *
				 *   [1] => stdClass Object
				 *      (
				 *       [uid] => 958
				 *       [member_rank] => 2
				 *       [total_purchase] => 0
				 *      )
				 *		)
				 *
				 * find out the smallest sale of user, base don that find the 
				 * others ratio.
				 * --------------------------------------------------------------
				*/
	 				$smallet_purchase = 0;
	 				foreach ($users_purchases as $key => $value) {
	 					$smallet_purchase   = empty($smallet_purchase) ? $value->total_purchase : $smallet_purchase;
	 					if ( $smallet_purchase < $value->total_purchase) {
	 						$smallet_purchase = $value->total_purchase;
	 					}
	 				}
	 			/**
				 * --------------------------------------------------------------
	 			 * @var  $user_ratios get the ratios of all users based on the 
	 			 * smallest purchase
	 			 * @var $sum_of_ratios : the sum of user ratios
				 * --------------------------------------------------------------
	 			*/
	 			 $user_ratios 		= array();

	 			 $sum_of_ratios	= 0;
	 			 if ( !empty($smallet_purchase)) :  
		 			foreach ($users_purchases as $key => $value) {
		 					$ratio = $value->total_purchase / $smallet_purchase;
		 					$user_ratios[$value->uid] 		= $ratio;
		 					$sum_of_ratios 	= $sum_of_ratios + $ratio;
		 			}
		 		 endif;
		 		/**
				 * --------------------------------------------------------------
				 * @var $sum_of_ratios : sum of all te ratios
				 *
				 * Find how much get a single user (average of $commission )
				 * 
				 * avg = $commission / $sum_of_ratios
				 * --------------------------------------------------------------
	 			*/
	 				$avg_pool_amount = 0;
	 			  if (!empty($sum_of_ratios)) 
	 					$avg_pool_amount = $commission / $sum_of_ratios;
	 			/**
				 * --------------------------------------------------------------
	 			 * @var  $avg_pool_amount is the amount for ratio 1
	 			 *
	 			 * According to this pool bonus calculated with the ratio
	 			 *
	 			 * @var $uid is the uid
	 			 * @var $ratio : ratio of the user 
				 * --------------------------------------------------------------
	 			*/
		 			if ( !empty($avg_pool_amount) ) {

			 			foreach ($user_ratios as $uid => $ratio) {
			 			 	$pool_bonus = $ratio * $avg_pool_amount;
			 			 	if ( !empty($pool_bonus)) {
							 
							 //check already paid the bonus
							 $check = _check_global_pool_alredy_paid('this_month',$uid);
							 
							 //check he has a distrib kit
							 $hs_distrib_kit = _has_distributor_kit_renewal($uid);

							 	if ( !$check && $hs_distrib_kit ) {
				 			 	 $rank_name 	= strtoupper(afl_variable_get('rank_'.$i.'_name','Rank '.$i));
			 			 		 $transaction = array();
							   $transaction['uid'] 								= $uid;
							   $transaction['associated_user_id'] = $uid;
							   $transaction['payout_id'] 					= 0;
							   $transaction['level']							= 0;
							   $transaction['currency_code'] 			= afl_currency();
							   $transaction['order_id'] 					= 1;
							   $transaction['int_payout'] 				= 0;
							   $transaction['hidden_transaction'] = 0;
							   $transaction['credit_status'] 			= 1;
							   $transaction['amount_paid'] 				= afl_commerce_amount($pool_bonus);
							   $transaction['category'] 					= $rank_name.' POOL BONUS';
							   $transaction['notes'] 							= $rank_name.' pool bonus ratio '.$ratio.' and average '.$avg_pool_amount.' of the bonus amount '.$commission;
								  //to mbr transaction
			    				afl_member_holding_transaction($transaction, TRUE);
								  
								  //to global pool bonus transactions
			        //     $transaction['category'] 					= $rank_name.' POOL BONUS';
			    				// afl_global_pool_transaction($transaction);
								}
			 			 	}
			 			}
		 			}
	 			}
	 		}
	 	}
 	}

 	//log cron run
 	if ( afl_variable_get('cron_logs_enable')) {
		afl_log('global_pool_bonus_scheduler','cron run completed',array(),LOGS_INFO);
 	}

}
/**
 * -------------------------------------------------------
 * check the pool bonus bonus already paid to user 
 * @var $type  : the checking type
 * @var $uid  :user id
 * @return Boolean 
 * -------------------------------------------------------
*/
function _check_global_pool_alredy_paid ($type = '', $uid = '') {
	switch ($type) {
		case 'this_month':
			 return _check_global_pool_paid_this_month($uid);
		break;
	}
}
/**
 * -------------------------------------------------------
 * check the pool bonus bonus already paid to user this 
 * month
 * @var $uid  :user id
 * @return Boolean 
 * -------------------------------------------------------
*/
function _check_global_pool_paid_this_month ($uid = '') {
	$afl_date_splits = afl_date_splits(afl_date());
	$query = array();
	$query['#select']  =_table_name('afl_user_holding_transactions');
	$query['#where']   = array(
		'hidden_transaction = 0',
		'deleted = 0',
		'transaction_month ='.$afl_date_splits['m'],
		'transaction_year  ='.$afl_date_splits['y'],
		'uid='.$uid
	);
	$result = db_select($query, 'get_results');

	if (count($result)) {
		return true;
	} else {
		return false;
	}

}