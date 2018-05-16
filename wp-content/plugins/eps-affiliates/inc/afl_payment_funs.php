<?php 

/*
 * -----------------------------------------------------------------------
 * Insert business transaction table 
 * 
 * -----------------------------------------------------------------------
*/
function afl_business_transaction ($business_transactions = array()) {
		if(empty($business_transactions)){
    	return FALSE;
  	}
  	global $wpdb;
		$afl_date 				= afl_date();
		$afl_date_splits 	= afl_date_splits($afl_date); 
		$afl_merchant_id 	= 'default';
  	$afl_project_name = 'default';

  	$business_trans_table = $wpdb->prefix . 'afl_business_transactions';
    if($business_transactions['credit_status'] == 0){
      $business_transactions['balance'] = $business_transactions['amount_paid'] * -1;
      $business_transactions['notes'] = $business_transactions['notes']. ' Paid';
    }
    else{
      $business_transactions['balance'] = $business_transactions['amount_paid'];
      $business_transactions['notes'] = $business_transactions['notes'].' Received';
    }


    if($business_transactions['associated_user_id'] != 0 && $business_transactions['associated_user_id'] != $business_transactions['uid']){
      $business_transactions['notes'] = $business_transactions['notes']. ' User associated is ' . $business_transactions['associated_user_id'];
    }

    $business_transactions['created'] = $afl_date;
    $business_transactions['transaction_day'] = $afl_date_splits['d'];
    $business_transactions['transaction_month'] = $afl_date_splits['m'];
    $business_transactions['transaction_year'] = $afl_date_splits['y'];
    $business_transactions['transaction_week'] = $afl_date_splits['w'];
    $business_transactions['transaction_date'] = afl_date_combined($afl_date_splits);
    $business_transactions['merchant_id'] = $afl_merchant_id;
    $business_transactions['project_name'] = $afl_project_name;
    
		
		$business_trans_id = $wpdb->insert($business_trans_table, $business_transactions);

	}

/*
 * -----------------------------------------------------------------------
 * Insert business transaction table 
 * credit status  1 => Cedit
 *                2 => Debit
 * -----------------------------------------------------------------------
*/
function afl_member_transaction($transaction = array(), $business = FALSE){
  if(empty($transaction)){
    return FALSE;
  }
  global $wpdb;
  $afl_merchant_id  = 'default';
  $afl_project_name = 'default';
  
  $afl_date = afl_date();
  $afl_date_splits = afl_date_splits($afl_date);
  $transaction_table = $wpdb->prefix . 'afl_user_transactions';
  try{

    $transaction['balance'] = 0;
    if($transaction['credit_status'] == 1){
      $transaction['balance'] = $transaction['amount_paid'];
      $transaction['notes'] = $transaction['notes'];
    }
    else{
      $transaction['balance'] = $transaction['amount_paid'] * -1;
      $transaction['notes'] = $transaction['notes'];
    }

    $transaction['rejoined_phase'] = 0;
    $transaction['created'] = $afl_date;
    $transaction['transaction_day'] = $afl_date_splits['d'];
    $transaction['transaction_month'] = $afl_date_splits['m'];
    $transaction['transaction_year'] = $afl_date_splits['y'];
    $transaction['transaction_week'] = $afl_date_splits['w'];
    $transaction['transaction_date'] = afl_date_combined($afl_date_splits);
    $transaction['merchant_id'] = $afl_merchant_id;
    $transaction['project_name'] = $afl_project_name;
    $transaction['payout_id'] = 'Null';
    $business_trans_id = $wpdb->insert($transaction_table, $transaction);
      
      if($business == TRUE){
        $business_transactions = array();
        $business_transactions['uid'] = $transaction['uid'];
        $business_transactions['associated_user_id'] = $transaction['associated_user_id'];
        $business_transactions['amount_paid'] = $transaction['amount_paid'];
        $business_transactions['notes'] = $transaction['notes'];
        $business_transactions['additional_notes'] = $transaction['notes'];
        $business_transactions['category'] = $transaction['category'];
        $business_transactions['currency_code'] = $transaction['currency_code'];
        $business_transactions['order_id'] = $transaction['order_id'];

        if($transaction['credit_status'] == 1){
          $business_transactions['credit_status'] = 0;
        }
        else{
          $business_transactions['credit_status'] = 1;
        }
        afl_business_transaction($business_transactions);
      }
    }
  catch (Exception $e) {
   // __commerce_checkout_complete_error($order, 299, $e);
    // watchdog_exception('User Transaction', $e);
  }
  return TRUE;
}


/*
 * -----------------------------------------------------------------------
 * Insert business transaction table 
 * credit status  1 => Cedit
 *                2 => Debit
 * -----------------------------------------------------------------------
*/
function afl_member_holding_transaction($transaction = array(), $business = FALSE){
  if(empty($transaction)){
    return FALSE;
  }
  global $wpdb;
  $afl_merchant_id  = 'default';
  $afl_project_name = 'default';
  
  $afl_date = afl_date();
  $afl_date_splits = afl_date_splits($afl_date);
  $transaction_table = $wpdb->prefix . 'afl_user_holding_transactions';
  try{

    $transaction['balance'] = 0;
    if($transaction['credit_status'] == 1){
      $transaction['balance'] = $transaction['amount_paid'];
      $transaction['notes'] = $transaction['notes'];
    }
    else{
      $transaction['balance'] = $transaction['amount_paid'] * -1;
      $transaction['notes'] = $transaction['notes'];
    }

    $transaction['rejoined_phase'] = 0;
    $transaction['created'] = $afl_date;
    $transaction['transaction_day'] = $afl_date_splits['d'];
    $transaction['transaction_month'] = $afl_date_splits['m'];
    $transaction['transaction_year'] = $afl_date_splits['y'];
    $transaction['transaction_week'] = $afl_date_splits['w'];
    $transaction['transaction_date'] = afl_date_combined($afl_date_splits);
    $transaction['merchant_id'] = $afl_merchant_id;
    $transaction['project_name'] = $afl_project_name;
    $transaction['payout_id'] = 'Null';
    $business_trans_id = $wpdb->insert($transaction_table, $transaction);

    }
  catch (Exception $e) {
   // __commerce_checkout_complete_error($order, 299, $e);
    // watchdog_exception('User Transaction', $e);
  }
  return TRUE;
}

function afl_currency(){
  return 'USD';
}
function afl_currency_symbol(){
  return '$';
}
/*
 * --------------------------------------------------------
 * Save the transaction details to the  global pool bonus
 * TRnasaction table
 * --------------------------------------------------------
*/
function afl_global_pool_transaction($transaction = array()){
  if(empty($transaction)){
    return FALSE;
  }
  global $wpdb;
  $afl_merchant_id  = 'default';
  $afl_project_name = 'default';
  
  $afl_date = afl_date();
  $afl_date_splits = afl_date_splits($afl_date);
  $transaction_table = $wpdb->prefix . 'afl_global_pool_bonus_transactions';
  try{

    $transaction['balance'] = 0;
    if($transaction['credit_status'] == 1){
      $transaction['balance'] = $transaction['amount_paid'];
      $transaction['notes'] = $transaction['notes'];
    }
    else{
      $transaction['balance'] = $transaction['amount_paid'] * -1;
      $transaction['notes'] = $transaction['notes'];
    }

    $transaction['created'] = $afl_date;
    $transaction['transaction_day'] = $afl_date_splits['d'];
    $transaction['transaction_month'] = $afl_date_splits['m'];
    $transaction['transaction_year'] = $afl_date_splits['y'];
    $transaction['transaction_week'] = $afl_date_splits['w'];
    $transaction['transaction_date'] = afl_date_combined($afl_date_splits);
    $transaction['merchant_id'] = $afl_merchant_id;
    $transaction['project_name'] = $afl_project_name;
    $transaction['payout_id'] = 'Null';
    $business_trans_id = $wpdb->insert($transaction_table, $transaction);
    
    }
  catch (Exception $e) {
   // __commerce_checkout_complete_error($order, 299, $e);
    // watchdog_exception('User Transaction', $e);
  }
  return TRUE;
}