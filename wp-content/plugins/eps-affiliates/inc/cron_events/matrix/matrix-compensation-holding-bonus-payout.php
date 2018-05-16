<?php
/** 
 * --------------------------------------------------------------------------
 * Here releases the holding bonuses to user wallet
 * --------------------------------------------------------------------------
*/
 function _release_holding_bonus_payouts () {

	 		$current_date				= afl_date();
	 		$afl_date_splits 		= afl_date_splits(afl_date());

	 		global $wpdb;

	 		$afl_user_holding_transactions = _table_name('afl_user_holding_transactions');
	 		
	 		// pr($afl_date_splits['d']);
	 		$payout_date = afl_variable_get('matrix_plan_payout_date',0);

	 		if($payout_date && $payout_date == $afl_date_splits['d']) {

		 		$query = array();
		 		$query['#select'] = $afl_user_holding_transactions;
		 		$query['#where']  = array(
		 			'paid_status = 0'
		 		);
				// $query['#limit'] 		 = 100;
		 		$holding_transactions  = db_select($query, 'get_results');


		 		if($holding_transactions) {
		 			foreach ($holding_transactions as $key => $trans) {
		 				
		 				// pr($trans);

					  /**
					   * 
					   * Member Transaction
					   * 
					   */

		 				$transaction = array();
					  $transaction['uid'] 								= $trans->uid;
					  $transaction['associated_user_id'] = $trans->associated_user_id;
					  $transaction['payout_id'] 					= $trans->payout_id;
					  $transaction['level']							= $trans->level;
					  $transaction['currency_code'] 			= $trans->currency_code;
					  $transaction['order_id'] 					= $trans->order_id;
					  $transaction['int_payout'] 				= $trans->int_payout;
					  $transaction['hidden_transaction'] = $trans->hidden_transaction;
					  $transaction['credit_status'] 			= $trans->credit_status;
					  $transaction['amount_paid'] 				= $trans->amount_paid;
					  $transaction['category'] 					= $trans->category;
					  $transaction['notes'] 							= $trans->notes;
					  // pr($transaction);
						afl_member_transaction($transaction, FALSE, FALSE);

					  /**
					   * 
					   * Business Transaction
					   * 
					   */

					  $b_transactions['category'] 						= $trans->category;
					  $b_transactions['additional_notes']		= 'Matrix compensation';
					  $b_transactions['uid'] 								= $trans->uid;
					  $b_transactions['associated_user_id'] 	= $trans->associated_user_id;
					  $b_transactions['credit_status'] 			= !$trans->credit_status;
					  $b_transactions['amount_paid'] 				= $trans->amount_paid;
					  $b_transactions['notes'] 							= $trans->notes;
					  $b_transactions['currency_code'] 			= $trans->currency_code;
					  $b_transactions['order_id'] 						= $trans->order_id;
					  // pr($b_transactions);
					   afl_business_transaction($b_transactions);


					  $update = $wpdb->update(
					 			$afl_user_holding_transactions,
					 			array(
					 				'paid_status'			=> 1,
					 			),
					 			array(
					 				'afl_user_transactions_id' => $trans->afl_user_transactions_id,
					 			)
					 		);


					  /**
					   * 
					   * Fast Start Bonus PV
					   * 
					   */

					  if($trans->category == 'FAST START BONUS') {
							require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/plan/matrix/fast-start-bonus-calc.php';
						  _afl_fast_start_bonus_pv($trans->uid);
					  }





		 				
		 			}
		 		}

	 		 	//log cron run
			 	if ( afl_variable_get('cron_logs_enable')) {
					afl_log('release_holding_payout_scheduler','cron run completed',array(),LOGS_INFO);
			 	}

	 		}
 }