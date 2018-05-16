<?php
/*
* delete a pin bulk opearation
*
*/
function epsaffliates_epin_delete_my_pin_callback($id= ''){
	global $wpdb;
	$uid 		 	= get_current_user_id();	
	$table 		= _table_name('afl_epin');
	$query['#select'] = $table;
 	$query['#where'] 	= array(
 		'pin_id ='.$id,
 		'uid ='.$uid
 	);
 	$val = db_select($query, 'get_row');
 	
 	$balance = $val->balance;
		if($balance > 0){
        $transaction = array();
        $transaction['uid'] = $uid;
        $transaction['associated_user_id'] = $uid;
        $transaction['level'] = 0;
        $transaction['currency_code'] = afl_currency();
        $transaction['order_id'] = 1;
        $transaction['credit_status'] = 1;
        $transaction['amount_paid'] = $balance;
        $transaction['category'] = 'EPIN REFUND';
        $transaction['notes'] = __('Refund Proccessed on'.date('Y-m-d', afl_date() ) );
        afl_member_transaction($transaction, FALSE);
    }
    $update = $wpdb->update(
		 	_table_name('afl_epin'),
		 		array(
		 			'deleted'	=> 1,	
		 		),
		 		array(
		 			'pin_id'	=> $id,
		 			'uid'			=> $uid,
		 		)
		);
    if($update){
    	return true;
    }else{
    	return false;
    }
   

}