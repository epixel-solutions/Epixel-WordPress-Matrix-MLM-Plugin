<?php
function afl_epin_user_purchase(){
	do_action('eps_affiliate_page_header');
	do_action('afl_content_wrapper_begin');
		afl_epin_purchase_form();
	do_action('afl_content_wrapper_end');
}
function afl_epin_purchase_form(){
	new Afl_enque_scripts('common');
	
	if ( isset($_POST['submit']) ) {
	 	$validation = afl_epin_purchase_form_validation($_POST);
	 	if (!empty($validation)) {
	 		afl_epin_purchase_form_submit($_POST);
	 	}
	 }

		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

	 $form['epin_conf'] = array(
		 '#type' 				=> 'fieldset',
		 '#title'				=>'Package Purchase using E-Pin'
		);
  	$form['epin_conf']['purchase_amount']= array(
    	'#type' 				=> 'text',
    	'#title' 				=> 'Purchase amount ',
    	'#default_value'=> '0',
    	'#required' 		=>	TRUE,
	 		'#prefix'				=> '<div class="form-group row">',
	 		'#suffix' 			=> '</div>'
  	);
  	$form['epin_conf']['pin_number']= array(
    	'#type' 				=> 'text',
    	'#title' 				=> 'Pin Number',
    	'#default_value'=> '11111',
    	'#required' 		=>	TRUE,
	 		'#prefix'				=> '<div class="form-group row">',
	 		'#suffix' 			=> '</div>'
  	);
  	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' =>'Submit'
	 	);
	 	echo afl_render_form($form);
}

function afl_epin_purchase_form_validation($form_state){
	global $wpdb;
	$uid 					 = get_current_user_id();

	$pin_number = $form_state['pin_number'];

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
      echo wp_set_message(__("Wrong E-pin number. Please enter a correct Pin number"),'danger');
      return FALSE;
  }
  
  $ebalance = afl_get_payment_amount($val->balance);
  $estatus = $val->status;
  $etransferable = $val->transferable;
  $euid = $val->uid;

  if($ebalance < $form_state['purchase_amount']){
      wp_set_message( __('E-pin balance can not be less than order amount'),'danger');
      return FALSE;
  }
  elseif ($estatus == 0) {
      wp_set_message( __('You entered an incorrect E-pin or your E-pin is blocked'),'danger');
      return FALSE;
  }
  elseif ($etransferable == 0) {
      if($euid != $uid){
      		wp_set_message( __('Unauthorized Access Prevented'),'danger');
      		return FALSE;
      }
  }
  return TRUE;
}

function afl_epin_purchase_form_submit($form_state){
global $wpdb;
	$uid 					 = get_current_user_id();

	// Commerce payment transaction
	$pin_number = $form_state['pin_number'];
	$query['#select'] = _table_name('afl_epin');
	$query['#fields'] = array(
		_table_name('afl_epin') => array(
			'pin_id',
			'pin',
			'balance',
			'status',
			'transferable',
			'reusable'
		)
	);
	$query['#where'] 	= array(
		'pin="'.$pin_number.'"',
	);
	$val = db_select($query, 'get_row');
	if(!empty($val)){
		$pin_id = $val->pin_id;
		$pin = $val->pin;
		$purchase_amount = afl_commerce_amount($form_state['purchase_amount']);
		$new_balance = $val->balance - $purchase_amount;
		
		$status=1;
		if(!$val->reusable){
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
    $history['amount'] = afl_commerce_amount($form_state['purchase_amount']);
    $history['transaction_note'] = 'Test E-Pin Purchase';
    $history_id = $wpdb->insert($his_table, $history);
    wp_set_message( __('Completed'),'success');
    return TRUE;
  }
  else{
  	wp_set_message( __('Somme error occured.. Please try again later'),'danger');
  	return FALSE;
  }

}