<?php 
/*
 * -------------------------------------------------------------------
 * Withdraw fund for menu callback 
 * -------------------------------------------------------------------
*/
function afl_ewallet_withdraw_fund(){
	new Afl_enque_scripts('common');

	echo afl_eps_page_header();
	echo afl_content_wrapper_begin();
		afl_ewallet_withdraw_fund_form();
	echo afl_content_wrapper_end();
}

/*
 * -------------------------------------------------------------------
 * Withdraw fund callback form
 * -------------------------------------------------------------------
*/
function afl_ewallet_withdraw_fund_form(){

	global $wpdb;
	$uid 					 = get_current_user_id();

	 if ( isset($_POST['submit']) ) {
	 	$validation = afl_ewallet_withdraw_fund_form_validation($_POST);
	 	if (!empty($validation)) {
	 		afl_ewallet_withdraw_fund_form_submit($_POST);
	 	}
	 }
	$table = $wpdb->prefix. 'afl_user_payment_methods';
	$payment_method = $wpdb->get_row("SELECT * FROM $table WHERE (uid = '$uid' AND status= '". 1 ."')");

	if(!$payment_method){
		$redirect = afl_variable_get('redirect_select_payment_method');
		$params['redirect'] = urlencode($_SERVER['REQUEST_URI']);

		if ( !empty($redirect)) {
			header("Location:".afl_build_url($redirect,$params)." ");
		}
			echo wp_set_message('Please set your payment method details first before proceeding withdrawal ', 'warning');
	}
	else if ( !$payment_method->completed) {
		$redirect = afl_variable_get('redirect_select_payment_method_detail');
		$params['redirect'] = urlencode($_SERVER['REQUEST_URI']);

		if ( !empty($redirect)) {
			header("Location:".afl_build_url($redirect,$params)." ");
		}
			echo wp_set_message('Please set your payment method details first before proceeding withdrawal ', 'warning');
	}
	else{
		$table = $wpdb->prefix. 'afl_transaction_authorization';
		$password = $wpdb->get_row("SELECT * FROM $table WHERE (uid = '$uid' )");
  	if(!$password){
  		echo wp_set_message('Please create a transaction password before proceeding', 'warning');
  		$redirect = afl_variable_get('redirect_set_transaction_password');
			$params['redirect'] = urlencode($_SERVER['REQUEST_URI']);

			if ( !empty($redirect)) {
				header("Location:".afl_build_url($redirect,$params)." ");
			}
  		/*
				goto set payment password set forms
			*/
  	}
  	$payout_methods = list_extract_allowed_values(afl_variable_get('payout_methods'),'list_text',FALSE);
  	$method 				= $payment_method->method;
  	$method_slected = $payout_methods[$method];

 		$form = array();
 		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

  	$form['withdrwal_amount']= array(
    	'#type' 				=> 'text',
    	'#default_value'=> isset($_POST['withdrwal_amount']) ? $_POST['withdrwal_amount'] :afl_variable_get('withdrwal_amount',0),
    	'#title' 				=> 'Amount to be withdraw ($)',
    	'#required' 		=>	TRUE,
	 		'#prefix'				=> '<div class="form-group row">',
	 		'#suffix' 			=> '</div>'
  	);
  	$form['password']= array(
    	'#type' 				=> 'password',
    	'#title' 				=> 'Transaction Password',
    	'#required' 		=>	TRUE,
    	'#default_value'=> afl_variable_get('password', ''),
	 		'#prefix'				=> '<div class="form-group row">',
	 		'#suffix' 			=> '</div>'
  	);
  	$form['my_payment_methods']= array(
	    '#type' 					=> 'radio',
	    '#title' 					=> 'Select Payment Methods',
	    '#name'  					=> 'payment_method',
	    '#options' 				=> array($method => $method_slected),
	    '#default_value'	=> $method,
	    '#prefix'					=> '<div class="form-group row">',
		 	'#suffix' 				=> '</div>'
  	);
		/*$path="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
  	$form['forgot_password'] = array(
      '#type' 				=> 'markup',
      '#markup' 			=> '<div class ="forget-transaction-password"><a href ="'.$path.'" class="btn btn-primary"> Forgot Password</a></div>',
      '#prefix'			=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
    );*/

  	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' =>'Submit'
	 	);
	 	echo afl_render_form($form);
		  
  	$balance = $wpdb->get_var("SELECT  SUM(`wp_afl_user_transactions`.`balance`) as balance FROM `wp_afl_user_transactions` WHERE `uid` = $uid AND `deleted` = 0 AND `int_payout` = 0 AND `int_return` = 0");	
  	$balance = (!empty($balance) ?$balance  : 0);
  
		$processed_for_payments = $wpdb->get_var("SELECT  SUM(`wp_afl_user_transactions`.`balance`) as balance FROM `wp_afl_user_transactions` WHERE `uid` = $uid AND `deleted` = 0 AND `int_payout` >= 1 ");	
		$processed_for_payments = (!empty($processed_for_payments) ?$processed_for_payments  : 0);

  	$processing_charge = afl_variable_get('payout_charges_'.$method, -100);
  	$withdrawal_max_value = /*afl_commerce_amount*/( afl_variable_get('withdrawal_max_value', -100));
  	$withdrawal_min_value = /*afl_commerce_amount*/( afl_variable_get('withdrawal_min_value',-100) );

		if(strpos($withdrawal_max_value, '%')){
  						$withdrawal_max = $withdrawal_max_value;
 		}else{ 
  		$withdrawal_max = ($withdrawal_max_value).' '.afl_currency();	
 		}
		if(strpos($processing_charge, '%')){
  			$process_charge = $processing_charge;
 		}else{ 
  		$process_charge = ($processing_charge).' '.afl_currency();	
 		}
 		if(strpos($withdrawal_min_value, '%')){
  			$withdrawal_min = $withdrawal_min_value;
 		}else{ 
  		$withdrawal_min = ($withdrawal_min_value).' '.afl_currency();	
 		}
  	$eligible_amount = afl_get_max_withrawal_amount($processing_charge ,$withdrawal_max_value, $withdrawal_min_value, $balance);

		$table 								= array();
		$table['#name'] 			= '';
		$table['#title'] 			= '';
		$table['#prefix'] 		= '';
		$table['#suffix'] 		= '';
		$table['#attributes'] = array(
							'class'=> array(
									'table',
									'table-responsive'
							)
						);

		$table['#header'] 		= array('Particulars ','Amount');
		// $table['#header'][] 		= array('Particulars & Amount');
		$rows[0][] = array(
			'#type' => 'label',
			'#title'=> 'Eligible amount for withdrawal',
	 	);
		$rows[0][] = array(
			'#type' => 'label',
			'#title'=> afl_format_payment_amount($balance, TRUE).' '.afl_currency(),
	 	);
		$rows[1][] = array(
			'#type' => 'label',
			'#title'=> 'E-wallet amount already in payout process',
	 	);
		$rows[1][] = array(
			'#type' => 'label',
			'#title'=> afl_format_payment_amount($processed_for_payments, TRUE).'' .afl_currency(),	
	 	);
	 	$rows[2][] = array(
			'#type' => 'label',
			'#title'=> 'Preferred Payment Method',
	 	);
		$rows[2][] = array(
			'#type' => 'label',
			'#title'=> $method_slected,	
	 	);
	 	$rows[3][] = array(
			'#type' => 'label',
			'#title'=> 'Processing Charges -'.$method_slected,
	 	);
		$rows[3][] = array(
			'#type' => 'label',
			'#title'=> $process_charge,	
	 	);
		$rows[4][] = array(
			'#type' => 'label',
			'#title'=> 'Minimum withdrawal Amount',
	 	);
		$rows[4][] = array(
			'#type' => 'label',
			'#title'=> $withdrawal_min,	
	 	);
	 	$rows[5][] = array(
			'#type' => 'label',
			'#title'=> 'Maximum withdrawal Percentage / Amount',
	 	);
		$rows[5][] = array(
			'#type' => 'label',
			'#title'=> $withdrawal_max,	
	 	);
	 		$rows[6][] = array(
			'#type' => 'label',
			'#title'=> 'Available Maximum Withdrawal Amount',
	 	);
		$rows[6][] = array(
			'#type' => 'label',
			'#title'=> afl_format_payment_amount($eligible_amount,TRUE).' '.afl_currency(),	
	 	);

		$table['#rows'] = $rows;

		$render_table  = '';
		$render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-afl-ewallet-withdraw-fund'));
		$render_table .= afl_render_table($table);
		$render_table .= afl_form_close();

		// pr($render_table,1);
		echo $render_table;


	}
	
}

/*
 * -------------------------------------------------------------------
 * Withdraw fund callback form validation
 * -------------------------------------------------------------------
*/
function afl_ewallet_withdraw_fund_form_validation($form_state){
	
	global $wpdb;
	$uid 		 = get_current_user_id();
	if($form_state['password'] && $form_state['withdrwal_amount']){
			$table = $wpdb->prefix. 'afl_transaction_authorization';
			$current = $wpdb->get_row("SELECT * FROM $table WHERE (uid = '$uid' )");
			if($current){
				$existing_password = $current->password;
				$password = md5($form_state['password']);
			 	if($password != $existing_password){
			 		echo wp_set_message(__('You entered an incorrect password.'), 'danger');
			 		return false;
		    }
		  }else{
		    echo wp_set_message(__('Please create a transaction password before proceeding.'), 'danger');
		    return false;
		  }

		  $table = $wpdb->prefix. 'afl_payout_requests';
		  $afl_payout_id = $wpdb->get_row("SELECT afl_payout_id FROM $table WHERE (uid = '$uid' AND deleted = 0  AND request_status = 1 AND category = 'WITHDRAWAL')"); 
  		if($afl_payout_id){
      echo wp_set_message(__('You have already one active withdrawal request. Users can not place multiple active withdrawal requests.'), 'danger');
      return false;

  		}
		 	$table = $wpdb->prefix. 'afl_user_transactions';
		 	$balance = $wpdb->get_var("SELECT  SUM(`$table`.`balance`) as balance FROM `$table` WHERE `uid` = $uid AND `deleted` = 0 AND `int_payout` = 0 AND `int_return` = 0");	
	  	$balance = (!empty($balance) ? $balance  : 0);

	  	$processed_for_payments = $wpdb->get_var("SELECT  SUM(`wp_afl_user_transactions`.`balance`) as balance FROM `wp_afl_user_transactions` WHERE `uid` = $uid AND `deleted` = 0 AND `int_payout` >= 1 ");	
			$processed_for_payments = (!empty($processed_for_payments) ?$processed_for_payments  : 0);
	
			$amount_entered = $form_state['withdrwal_amount'];
			$requested_amount = afl_commerce_amount($amount_entered) ;
			
			$processing_charge = afl_variable_get('payout_charges_'.$form_state['payment_method'], -100);
			$withdrawal_max_value = /*afl_commerce_amount*/( afl_variable_get('withdrawal_max_value', -100));
		  $withdrawal_min_value = /*afl_commerce_amount*/( afl_variable_get('withdrawal_min_value',-100) );
	  	$eligible_amount = ( afl_get_max_withrawal_amount($processing_charge ,$withdrawal_max_value, $withdrawal_min_value, $balance) );
	  	
	  	$eligible_amount = afl_get_commerce_amount($eligible_amount);
	  	$requested_amount = afl_get_commerce_amount($requested_amount);
	  	/*pr("eligible - ".$eligible_amount);
	  	pr("maximum - ".$withdrawal_max_value);
	  	pr("minimum - ".$withdrawal_min_value);
	  	pr("requested - ". $requested_amount);
			exit();*/

	  	if($requested_amount <= 0){
	  		echo wp_set_message(__('Withdrawal amount should be greater than zero .'), 'danger');
		    return false;
    	}
    	if($requested_amount < $withdrawal_min_value){
    		echo wp_set_message(__("Withdrawal amount should be greater or equal to ". ($withdrawal_min_value) ), "danger");
		    return false;
  		}
  		if($requested_amount > $eligible_amount){
    		echo wp_set_message(__('Withdrawal amount should be smaller or equal to '.($eligible_amount) ), 'danger');
		    return false;
  		}
		    return TRUE;

	}
	else{
		echo wp_set_message(__('Amount to be withdraw and Transaction Password fields are required .'), 'danger');
	}
}

/*
 * -------------------------------------------------------------------
 * Withdraw fund callback form submit
 * -------------------------------------------------------------------
*/
function afl_ewallet_withdraw_fund_form_submit($form_state){
	
if($form_state['password'] && $form_state['withdrwal_amount']){
	global $wpdb;
	$uid 		 	= get_current_user_id();
	$afl_date = afl_date();
	$afl_date_splits = afl_date_splits($afl_date);

	$amount_entered = $form_state['withdrwal_amount'];
	$requested_amount = ($amount_entered) ;
	$processing_charge =  (afl_variable_get('payout_charges_'.$form_state['payment_method'], -100));
  $charges = afl_commission($processing_charge, $requested_amount, 0);
	
	$payout_table = $wpdb->prefix . 'afl_payout_requests';
	$payout_his_table = $wpdb->prefix . 'afl_payout_history';

	$records 		= array();
  $records['uid'] 							= $uid;
  $records['initiated_by'] 			= $uid;
  $records['payout_method']			=	$form_state['payment_method'];
  $records['request_status']		= 1;
  $records['paid_status'] 			= 0;
  $records['payout_type'] 			= '';
  $records['amount_requested'] 	= afl_commerce_amount($requested_amount);
  $records['charges']  					= afl_commerce_amount($charges);
  $records['currency_code'] 		= afl_currency();
  $records['amount_paid'] 			= afl_commerce_amount ( ($requested_amount - $charges) );
  $records['category'] 					= 'WITHDRAWAL';
  $records['notes'] 						= 'Submitted withdrawal request';
  $records['created'] 					= $afl_date;
  $records['modified'] 					= $afl_date;
  $records['deleted'] 					= 0;
  $records['payment_date'] 			= $afl_date_splits['d'];
  $records['payment_month'] 		= $afl_date_splits['m'];
  $records['payment_year'] 			= $afl_date_splits['y'];
  $records['payment_week'] 			= $afl_date_splits['w'];

    $tempary_id = $wpdb->insert($payout_table, $records);
    if($tempary_id){
    	$records['afl_payout_id'] = $tempary_id;
    	$tempary_id =  $wpdb->insert($payout_his_table, $records);
    	
    $transaction = array();
    $transaction['uid'] = $uid;
    $transaction['associated_user_id'] = $uid;
    $transaction['payout_id'] = (is_numeric($tempary_id) ) ? $tempary_id : 0;
    $transaction['level'] = 0;
    $transaction['currency_code'] = afl_currency();
    $transaction['order_id'] = 1;
    $transaction['int_payout'] = 0;
    $transaction['hidden_transaction'] = 0;
    $transaction['credit_status'] = 0;
    $transaction['amount_paid'] = afl_commerce_amount($requested_amount);
    $transaction['category'] = 'WITHDRAWAL';
    $transaction['notes'] = 'Withdrawal Request on '. afl_date_combined($afl_date_splits);
    afl_member_transaction($transaction, FALSE, FALSE);

    $business_transactions['category'] = 'WITHDRAWAL CHARGES';
    $business_transactions['additional_notes'] = 'Withdrawal Charges';
    $business_transactions['uid'] = $uid;
    $business_transactions['associated_user_id'] = $uid;
    $business_transactions['credit_status'] = 1;
    $business_transactions['amount_paid'] = afl_commerce_amount($charges);
    $business_transactions['notes'] = 'Withdrawal Charges';
    $business_transactions['currency_code'] = afl_currency();
    $business_transactions['order_id'] = 1;
    afl_business_transaction($business_transactions);
  	echo wp_set_message(__('congrats..'), 'success');

  }
  else{
  	echo wp_set_message(__('error in submit..'), 'danger');

    }
  }

}	
/*
 * -------------------------------------------------------------------
 * Calculate eligible withdrawal amount
 * 
 * -------------------------------------------------------------------
*/
function afl_get_max_withrawal_amount($commission ,$withdrawal_max_value, $withdrawal_min, $balance){
  $chrg = explode('%', $commission); 
  $max_amount = explode('%', $withdrawal_max_value);
  $bal = afl_format_payment_amount($balance, FALSE); 
  if (strpos($withdrawal_max_value, '%')) {
     // $net_amount = ((($balance * $max_amount[0]) / 100) * 100) / (100 + $chrg[0]);
     $net_amount = afl_commission($withdrawal_max_value, $balance);
  	

  } 
  else {
  
    if ($bal >= $withdrawal_max_value) {
      $net_amount = $withdrawal_max_value;
    } else {
      $net_amount = $bal;
    }
    $net_amount = afl_commerce_amount($net_amount);
  }
  if ($bal <= $withdrawal_min || ((!strpos($commission, '%')) && $bal <= $chrg[0])) {
    $net_amount = 0;
  }
  if ($net_amount < 0) {
    $net_amount = 0;
  }
  return $net_amount;
}

