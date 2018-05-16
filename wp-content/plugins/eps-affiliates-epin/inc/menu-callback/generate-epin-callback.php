<?php
function afl_epin_generate(){
	do_action('eps_affiliate_page_header');
	do_action('afl_content_wrapper_begin');
		afl_epin_generate_form();
	do_action('afl_content_wrapper_end');
}
function afl_epin_generate_form(){
	new Afl_enque_scripts('common');
	
	$min_amount = afl_format_payment_amount(afl_commerce_amount(afl_variable_get('epin_minimum_amount',0)));
	$max_amount = afl_format_payment_amount(afl_commerce_amount(afl_variable_get('epin_maximum_amount',0)));
	$no_of_pins = afl_variable_get('epin_members_active_epins',FALSE);
	global $wpdb;
	$uid 					 = get_current_user_id();
		
	 if ( isset($_POST['submit']) ) {
	 	$validation = afl_epin_generate_form_validation($_POST);
	 	if (!empty($validation)) {
	 		afl_epin_generate_form_submit($_POST);
	 	}
	 }

		$form = array();
 		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

	 	$form['epin_conf'] = array(
		 '#type' 				=> 'fieldset',
		 '#title'				=>'Configuration for E-pin Generation'
		);
  	$form['epin_conf']['epin_amount']= array(
    	'#type' 				=> 'text',
    	'#default_value'=> isset($_POST['e_pin_amount']) ? $_POST['e_pin_amount'] :afl_variable_get('e_pin_amount',0),
    	'#title' 				=> 'E-Pin amount  ',
    	'#required' 		=>	TRUE,
	 		'#prefix'				=> '<div class="form-group row">',
	 		'#suffix' 			=> '</div>'
  	);
  	$form['epin_conf']['no_of_epins']= array(
    	'#type' 				=> 'text',
    	'#default_value'=> isset($_POST['no_of_pins']) ? $_POST['no_of_pins'] :afl_variable_get('no_of_pins',0),
    	'#title' 				=> 'Number of E-Pins ',
    	'#required' 		=>	TRUE,
	 		'#prefix'				=> '<div class="form-group row">',
	 		'#suffix' 			=> '</div>'
  	);
  	$form['epin_conf']['e_pin_transferable']= array(
  		'#type' 				=> 'checkbox',
  		'#title' 				=> 'Transferable (Can other users use this E-pin for their purchases ?)',
  	);
  	$form['epin_conf']['reusable']= array(
  		'#type' 				=> 'checkbox',
  		'#title' 				=> 'Reusable (This E-pin can be used for multiple purchases. Its value will be decreased for each purchases.)',
  	);
  	
  	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' =>'Submit'
	 	);
	 	echo afl_render_form($form);

	 	$balance = $wpdb->get_var("SELECT  SUM(`wp_afl_user_transactions`.`balance`) as balance FROM `wp_afl_user_transactions` WHERE `uid` = $uid AND `deleted` = 0 AND `int_payout` = 0 AND `int_return` = 0");	
  	$balance = (!empty($balance) ?$balance  : 0);
  	$processed_for_payments = $wpdb->get_var("SELECT  SUM(`wp_afl_user_transactions`.`balance`) as balance FROM `wp_afl_user_transactions` WHERE `uid` = $uid AND `deleted` = 0 AND `int_payout` >= 1 ");	
		$processed_for_payments = (!empty($processed_for_payments) ?$processed_for_payments  : 0);
	
		$gen_fee = afl_variable_get('epin_generation_fee',0);
		if(strpos($gen_fee, '%') === FALSE){
			$epin_fee = $gen_fee.' '.afl_currency();
			$avail_balance = $balance - afl_commerce_amount($gen_fee);
		}
		else{
			$comisn_percentage = str_replace('%','',$gen_fee);
			$epin_fee = $gen_fee;
			$avail_balance = $balance-($balance *($comisn_percentage/100));
		}
 		

	 	
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

		$table['#header'] 		= array('E-Wallet Particulars ','Amount'); 
		$rows[0][] = array(
			'#type' => 'label',
			'#title'=> 'Total Ewallet Amount',
	 	);
		$rows[0][] = array(
			'#type' => 'label',
			'#title'=> afl_format_payment_amount($balance, TRUE).' '.afl_currency(),
	 	);
	 		$rows[1][] = array(
			'#type' => 'label',
			'#title'=> 'E-pin Generation Fee	',
	 	);
		$rows[1][] = array(
			'#type' => 'label',
			'#title'=> $epin_fee,
	 	);
	 	


		$rows[2][] = array(
			'#type' => 'label',
			'#title'=> 'E-wallet amount already in payout or withdrawal process',
	 	);
		$rows[2][] = array(
			'#type' => 'label',
			'#title'=> afl_format_payment_amount($processed_for_payments, TRUE).' '.afl_currency(),
	 	);

		$rows[3][] = array(
			'#type' => 'label',
			'#title'=> 'Maximum eligible amount to generate E-pin	',
	 	);
		$rows[3][] = array(
			'#type' => 'label',
			'#title'=> afl_format_payment_amount($avail_balance, TRUE).' '.afl_currency(),
	 	);

	 	$rows[4][] = array(
			'#type' => 'label',
			'#title'=> 'Maximum amount to generate E-pin',
	 	);
		$rows[4][] = array(
			'#type' => 'label',
			'#title'=> $max_amount . afl_currency(),
	 	);

	 	$rows[5][] = array(
			'#type' => 'label',
			'#title'=> 'Minimum amount to generate E-pin',
	 	);
		$rows[5][] = array(
			'#type' => 'label',
			'#title'=> $min_amount .afl_currency(),
	 	);

 	
		$table['#rows'] = $rows;
		$render_table  = '';
		$render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-afl-ewallet-withdraw-fund'));
		$render_table .= afl_render_table($table);
		$render_table .= afl_form_close();

		echo $render_table;


}

function afl_epin_generate_form_validation($form_state){

	global $wpdb;
	$uid 					 = get_current_user_id();
	$e_pin_amount = afl_commerce_amount ($form_state['epin_amount']);
	$min_amount = afl_commerce_amount(afl_variable_get('epin_minimum_amount',0));
	$max_amount = afl_commerce_amount(afl_variable_get('epin_maximum_amount',0));
	$no_of_pins = $form_state['no_of_epins'];
	$gen_fee = (afl_variable_get('epin_generation_fee',0) );
	$gen_fee = (afl_commission($gen_fee,$e_pin_amount,0 ));
	if($e_pin_amount >= $min_amount && $e_pin_amount <= $max_amount){
		$balance = $wpdb->get_var("SELECT  SUM(`wp_afl_user_transactions`.`balance`) as balance FROM `wp_afl_user_transactions` WHERE `uid` = $uid AND `deleted` = 0 AND `int_payout` = 0 AND `int_return` = 0");	
	 	$net_amount = ($e_pin_amount + $gen_fee) * $no_of_pins;
	 	if($net_amount > $balance){ 
	 			echo wp_set_message( ('You dont have sufficient balance to generate E-pin of'.afl_get_commerce_amount($net_amount) ),'danger'  );
	 			return FALSE;
	    }
	    $query = $wpdb->get_col("SELECT  `wp_afl_epin`.`uid` as uid FROM `wp_afl_epin` WHERE `uid` = $uid AND `status` = 1 ");
	    $count = count($query);
			$count = $count + $no_of_pins;
			$max_count = afl_variable_get('epin_members_active_epins');
			if($max_count != -1){
				if($count > $max_count){
					echo wp_set_message( ('you reach maximum E-pin limit. Please try again Later.'),'danger'  );
	 				return FALSE;
				}
			}
		return TRUE;
	}
	else{
			echo wp_set_message( ('Please enter E-pin amount between'.afl_get_commerce_amount($min_amount).'and'.afl_get_commerce_amount($max_amount) ),'danger'  );
			return FALSE;
    }
}

function afl_epin_generate_form_submit($form_state){
	
	global $wpdb;
	$table 					= $wpdb->prefix. 'afl_epin';
	$uid 					 	= get_current_user_id();
	$afl_date 			= afl_date();
	$e_pin_amount 	= afl_commerce_amount ($form_state['epin_amount']);		
	$no_of_pins 		= $form_state['no_of_epins'];
	$gen_fee 				= (afl_variable_get('epin_generation_fee',0) );
	$gen_fee 				= (afl_commission($gen_fee,$e_pin_amount,0 ));

	$total_gen_fee  = $gen_fee * $no_of_pins; 
	$net_amount 		=	($e_pin_amount + $gen_fee) * $no_of_pins;
	$length 				= afl_variable_get('epin_length');
	$transferable 	= (!empty($form_state['e_pin_transferable']) ? 1 : 0);
	$reusable 			=	(!empty($form_state['reusable']) ? 1 : 0);
	
	$i 							= $no_of_pins;
	while($i>0){
		$epin['uid'] 					= $uid;
		$epin['pin'] 					= get_epin_code($length);
		$epin['balance'] 			= $e_pin_amount;
		$epin['amount'] 			= $e_pin_amount;
		$epin['charge'] 			= $gen_fee;
		$epin['created'] 			= $afl_date;
		$epin['status']	 			= 1;
		$epin['transferable'] =	$transferable;
		$epin['reusable'] 		= $reusable;
		$epin_response 				= $wpdb->insert($table, $epin);
		$i--;
	}
	if($epin_response){
			$transaction = array();
	    $transaction['uid'] 					= $uid;
	    $transaction['associated_user_id'] = $uid;
	    $transaction['level'] 				= 0;
	    $transaction['currency_code'] = afl_currency();
	    $transaction['order_id'] 			= 1;
	    $transaction['int_payout'] 		= 0;
	    $transaction['credit_status'] = 0;
	    $transaction['amount_paid'] 	= $net_amount;
	    $transaction['category'] 			= 'E-PIN GENERATION';
	    $transaction['notes'] 				= 'E-Pin issue on '.date('Y-m-d', afl_date());
	    afl_member_transaction($transaction, FALSE, FALSE);

	    $b_transaction = array();
	    $b_transaction['uid'] 					= $uid;
	    $b_transaction['associated_user_id'] = $uid;
	    $b_transaction['currency_code'] = afl_currency();
	    $b_transaction['order_id'] 			= 1;
	    $b_transaction['int_payout'] 		= 0;
	    $b_transaction['credit_status'] = 1;
	    $b_transaction['amount_paid'] 	= $total_gen_fee;
	    $b_transaction['category'] 			= 'E-PIN GENERATION';
	    $b_transaction['notes'] 				= 'E-Pin issue on '.date('Y-m-d', afl_date());
	    $b_transaction['additional_notes'] = 'E-Pin Generation Fee from '.$uid;
	    afl_business_transaction($b_transaction);

	    echo wp_set_message( ('Your E-Pin has been generated..'),'success'  );
	    return TRUE;
	    
	}
	else{
		echo wp_set_message( ( 'Your form could not be saved. Please, try again.'), 'danger' );
		return FALSE;
	}
	
}

// To create a E-pin Code
function get_epin_code($length = 10) {
    global $wpdb;
    $key = '';
    $keys = array_merge(range(0, 9), range('A', 'H'),range('J', 'N'),range('P', 'Z'));
    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    $afl_epin_exist = $wpdb->get_var($wpdb->prepare( 
    										"SELECT  `wp_afl_epin`.`pin` as pin FROM `wp_afl_epin` WHERE pin = %s", $key
    									)
    								);
		if(empty($afl_epin_exist)){ return $key;}
  		else{ get_epin_code($length); }
}

