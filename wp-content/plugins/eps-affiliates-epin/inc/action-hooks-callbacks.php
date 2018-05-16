<?php 
/*
 * ----------------------------------------------------------------
 * E-pin Details
 * ----------------------------------------------------------------
*/
	function eps_affiliates_epin_details_callback ( $pin_number = '' ) {
		$query['#select'] = _table_name('afl_epin');
		// $query['#fields'] = array(
		// 	_table_name('afl_epin') => array(
		// 		'uid',
		// 		'pin',
		// 		'balance',
		// 		'status',
		// 		'transferable'
		// 	)
		// );
		$query['#where'] 	= array(
			'pin="'.$pin_number.'"',
		);
		$val = db_select($query, 'get_row');
		return (array)$val;
	}
/*
 * ----------------------------------------------------------------
 * Check the given epin is valid or not
 * ----------------------------------------------------------------
*/
	function check_epin_valid_or_not ( $uid = '', $pin_number = '', $purchase_amount = 0 ) {
		$response = array();
		$response['status'] = 1;
		
		if (empty($uid)) {
			$response['status'] 	= 0;
			$response['error'][] 	= 'Please provide a user id';
			return $response;
		}

		if (empty($pin_number)) {
			$response['status'] 	= 0;
			$response['error'][] 	= 'Please provide a pin number';
			return $response;
		}

		$query['#select'] = _table_name('afl_epin');
		$query['#fields'] = array(
			_table_name('afl_epin') => array(
				'uid',
				'pin',
				'balance',
				'status',
				'transferable'
			)
		);
		$query['#where'] 	= array(
			'pin="'.$pin_number.'"',
		);
		$val = db_select($query, 'get_row');


		if(empty($val)){
      $response['status'] 	= 0;
			$response['error'][] 	= 'Invalid pin number';
			return $response;

	  }

	  $ebalance 			= afl_get_payment_amount($val->balance);
	  $estatus 				= $val->status;
	  $etransferable 	= $val->transferable;
	  $euid 					= $val->uid;

	  if ( $ebalance < $purchase_amount ) {
	  	$response['status'] 	= 0;
			$response['error'][] 	= 'E-pin balance less than purchase amount';
	  }

	  if ( $estatus == 0 ) {
	  	$response['status'] 	= 0;
			$response['error'][] 	= 'You entered an incorrect E-pin or your E-pin is blocked';
	  }

	  if ($etransferable == 0) {
      if($euid != $uid){
      	$response['status'] 	= 0;
				$response['error'][] 	= 'Unauthorized Access Prevented';
      }
  	}

	  return $response;
	}
/*
 * -------------------------------------------------------------------------------------
 * Complete purchase using e-pin
 * -------------------------------------------------------------------------------------
*/
	function eps_affiliates_epin_purchase_complete_callback ($uid = '', $pin_number = '', $args = array()) {
		global $wpdb;
		$response = array();
		$response['status'] = 1;
		$purchase_amount = !empty($args['amount_paid']) ?  $args['amount_paid'] : 0;
		$pin_validity  	 = apply_filters('eps_affiliates_check_epin_validity',$uid, $pin_number ,$purchase_amount);
		
		if ( empty($pin_validity['status'] )) {
			return $pin_validity;
		}


		$pin_detail = apply_filters('eps_affiliates_epin_details',$pin_number);
		
		if(!empty($pin_detail)){
			$pin_id = $pin_detail['pin_id'];
			$pin 		= $pin_detail['pin'];
			$purchase_amount = afl_commerce_amount($purchase_amount);
			$new_balance = $pin_detail['balance'] - $purchase_amount;
			
			$status=1;
			if(!$pin_detail['reusable']){
		    $status = 0;
		  }
		  if($new_balance < 1){
		    $status = 0;
		  }
	  	$update = $wpdb->update(
		 			_table_name('afl_epin'),
		 			array(
		 				'balance'		=> $new_balance,
		 				'status' 		=> $status,
		 			),
		 			array(
		 				'pin' 			=> $pin,
		 				'pin_id'		=> $pin_id,
		 			)
		 		);
	  	$his_table = _table_name('afl_epin_history');
	  	$afl_date = afl_date();
	    $history = array();
	    $history['pin_id'] = $pin_id;
	    $history['pin'] = $pin;
	    $history['uid'] = $uid;
	    $history['created_date'] = $afl_date ;
	    $history['amount'] = afl_commerce_amount($purchase_amount);
	    $history['transaction_note'] = 'Test E-Pin Purchase';
	    $history_id = $wpdb->insert($his_table, $history);
	    

			$purchase_details = apply_filters('eps_commerce_purchase_complete', $args );
			
			if (!empty($purchase_details['status'])) 
				return $purchase_details;
			else
				return $purchase_details;
	  } else {
	  	$response['status'] 	= 0;
	  	$response['error'][] 	= 'E-pin details could not find';
	  	return $response;
	  }
	}