<?php
/*
* ------------------------------------------------------------
* User payment method select call back
* ------------------------------------------------------------
*/
function afl_user_payment_methods(){
		new Afl_enque_scripts('common');

	echo afl_eps_page_header();
	echo afl_content_wrapper_begin();
		afl_user_payment_method_conf_form();
	echo afl_content_wrapper_end();
}
/*
* ------------------------------------------------------------
* User payment method select form and tab
* ------------------------------------------------------------
*/
function afl_user_payment_method_conf_form(){
	
	global $wpdb;
	$uid 					 = get_current_user_id();
	$table = $wpdb->prefix. 'afl_user_payment_methods';
	$payment_methods = list_extract_allowed_values(afl_variable_get('payout_methods'),'list_text',FALSE);
	$default_method = $wpdb->get_var("SELECT method FROM $table WHERE (uid = '$uid' AND status= '". 1 ."')");
 // pr($default_method);
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'payment_method';
	echo '<ul class="tabs--primary nav nav-tabs">';
		  echo '<li class="'.(($active_tab == 'payment_method') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-payment_method&tab=payment_method" >Payment Methods</a>
		          </li>';
		          foreach ($payment_methods as $key => $value) {
		          	if($key == $default_method){
		          	echo '<li class="'.(($active_tab == $key) ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-payment_method&tab='.$key.'" >'.$value.'</a>
		          	</li>';
		          }
		         }
						 echo '<li class="'.(($active_tab == 'autherization') ? 'active' : '').'">
			 		            	<a href="?page=affiliate-eps-payment_method&tab=autherization" >Payment Autherization</a>
			 		          </li>';
		  echo '</ul>';
		switch ($active_tab) {
	  	case 'payment_method':
	  		afl_user_payment_method_form();
  		break;
  		case 'method_bank' :
  			afl_user_payment_conf_method_bank_form();
  		break;
  		case 'method_paypal' :
  			afl_user_payment_conf_method_paypal_form();
  		break;
			case  'autherization' :
				afl_user_payment_autherization_form();
			break;

			case 'method_hyperwallet' :
  			afl_user_payment_conf_method_hyperwallet_form();
  		break;

  		default :
  			afl_user_payment_conf_error_form();
  		break;
  		}
}

/*
* ------------------------------------------------------------
* User payment method select form
* ------------------------------------------------------------
*/
function afl_user_payment_method_form(){

	global $wpdb;
	$uid 					 = get_current_user_id();
	$table = $wpdb->prefix .'afl_user_payment_methods';
	$check = $wpdb->get_row("SELECT  * FROM `$table` WHERE `uid` = $uid ");

  if( !$check){
		echo wp_set_message('You need to complete step here before you can access this page', 'warning');
	}


	 if ( isset($_POST['submit']) ) {
	 	$validation = afl_user_payment_method_form_validation($_POST);
	 	if (!empty($validation)) {
	 		afl_user_payment_method_form_submit($_POST);
		  if (isset($_GET['redirect'])) {
				header("Location:".urldecode($_GET['redirect'])." ");
		  }
	 	}
	 }
		global $wpdb;
		$uid 					 = get_current_user_id();
		$table = $wpdb->prefix. 'afl_user_payment_methods';

		$payment_methods = list_extract_allowed_values(afl_variable_get('payout_methods'),'list_text',FALSE);
		$default_method = $wpdb->get_var("SELECT method FROM $table WHERE (uid = '$uid' AND status= '". 1 ."')");
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
		$i = 0;
		foreach ($payment_methods as $key => $value) {
			if($default_method == $key)
				$flag = TRUE;
			else
				$flag = FALSE;
			$rows[$i]['label_'.$key] = array(
				'#type' => 'label',
				'#title'=> $value,
			);
			$rows[$i][$key] = array(
				'#type' 					=> 'checkbox',
				'#default_value' 	=> $flag,
				// '#attributes' 		=> array('switch' => 'switch', 'class'=> array('single-switch-checkbox') ),
			);
			// $link = '?page=user-payment-conf-'.$key;
			$link = '?page=affiliate-eps-payment_method&tab='.$key;
		// 	$rows[$i]['conf_'.$key] = array(
		// 	'#type' => 'markup',
		// 	'#markup' => '<a href="'.$link.'" ><span class="btn btn-rounded btn-sm btn-icon btn-default"><i class="fa fa-cog"></i></span></a>',
		// );
			$i++;
		}

		$table['#rows'] = $rows;
		$table['#header'] 		= array('Payment Methods','Status');

		$render_table  = '';
		$render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-afl-ewallet-withdraw-fund'));
		$render_table .= afl_render_table($table);
		$render_table .= afl_input_button('submit', 'Save configuration', '',array('class'=>'btn btn-default btn-primary'));
		$render_table .= afl_form_close();
		// pr($render_table,1);
		echo $render_table;
}
/*
* ------------------------------------------------------------
* User payment method select form validation
* ------------------------------------------------------------
*/
function afl_user_payment_method_form_validation($form_state){
	$flag = 0;
	unset($form_state['submit']);
  foreach ($form_state as $key => $value) {
  	$payment_method = $key;
		if($value)
      	$flag++;
  }
  if($flag >1){
   	echo wp_set_message(__('You can select only one payout method.'), 'danger');
   	return false;
  }
  elseif($flag == 0){
  	echo wp_set_message(__('Please select one payout method'), 'danger');
    return false;
  }
  else{
  	return true;
  }
}
/*
* ----------------------------------- -------------------------
* User payment method select form submit
* ------------------------------------------------------------
*/
function afl_user_payment_method_form_submit($form_state){
	global $wpdb;
	$uid 		 	= get_current_user_id();
	$flag = 0;
	unset($form_state['submit']);
	foreach ($form_state as $key => $value) {
  	$payment_method = $key;
		if($value){
      $flag++;
    }
  }
  if($flag >1){
  	echo wp_set_message(__('You can select only one payout method.'), 'danger');
    return false;
  }
  elseif($flag == 0){
  	echo wp_set_message(__('Please select one payout method'), 'danger');
    return false;
  }

  $table = $wpdb->prefix .'afl_user_payment_methods';

  $wpdb->update($table, array('status'=>0), array('uid'=>$uid));
  $check = $wpdb->get_results("SELECT  `method` FROM `$table` WHERE `uid` = $uid AND `method` = '$payment_method'");

  if($check){
  	$q = $wpdb->update($table, array('status'=>1), array('uid'=>$uid,'method' => $payment_method ));
  }
  else{
   		$insert['uid'] 			= $uid;
   		$insert['method']  	=	$payment_method;
   		$insert['completed']= 0;
   		$insert['status'] 	= 1;
   		$insert['data'] 		= '';
   	$q = $wpdb->insert($table, $insert);
  }
   if($q) {
     echo wp_set_message(__('Your payment method saved successfully.'), 'success');
   }

}

/*
* ------------------------------------------------------------
* User bank payment method configuration
* ------------------------------------------------------------
*/
function afl_user_payment_conf_method_bank_form(){
	 if ( isset($_POST['submit']) ) {
	 	$validation = afl_user_payment_conf_method_bank_form_validation($_POST);
	 	if ($validation) {
	 		afl_user_payment_conf_method_bank_form_submit($_POST);
	 	}
	 }

	global $wpdb;
	$uid 					 = get_current_user_id();
	$table = $wpdb->prefix .'afl_user_payment_methods';
  $check = $wpdb->get_row("SELECT  * FROM `$table` WHERE `uid` = $uid AND `method` = 'method_bank' AND `status` = 1 ");
  if(! $check){
   	wp_redirect('?page=affiliate-eps-payment_method');
  }
	$data = json_decode($check->data);
   $form = array();
 		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

	 	$form['bank_name'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Bank Name',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->bank_name) ? $data->bank_name : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['bank_account_number'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Account number',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->bank_account_number) ? $data->bank_account_number : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['bank_swift_code'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Swift Code ',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->bank_swift_code) ? $data->bank_swift_code : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['bank_country'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Country',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->bank_country) ? $data->bank_country : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['bank_ifsc_code'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'IFSC Code ',
	 		'#default_value' 	=> isset($data->bank_ifsc_code) ? $data->bank_ifsc_code : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' =>'Save configuration'
	 	);
	 	echo afl_render_form($form);
}

/*
* ------------------------------------------------------------
* User bank payment method configuration form validation
* ------------------------------------------------------------
*/
function afl_user_payment_conf_method_bank_form_validation($form_state){
		$rules = array();
		//create rules
		$rules[] = array(
	 		'value'=> $form_state['bank_name'],
	 		'name' =>'Bank name',
	 		'field' =>'bank_name',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	 	$rules[] = array(
	 		'value'=> $form_state['bank_account_number'],
	 		'name' =>'Bank Account Number',
	 		'field' =>'bank_account_number',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	 	$rules[] = array(
	 		'value'=> $form_state['bank_swift_code'],
	 		'name' =>'Bank Swift Code',
	 		'field' =>'bank_swift_code',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	 	$rules[] = array(
	 		'value'=> $form_state['bank_country'],
	 		'name' =>'Bank Country',
	 		'field' =>'bank_country',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
		$resp  = set_form_validation_rule($rules);
		if (!$resp) {
			return  $resp;
			
		}
		else
			return true;
}

/*
* ------------------------------------------------------------
* User bank payment method configuration form submit
* ------------------------------------------------------------
*/
function afl_user_payment_conf_method_bank_form_submit($form_state){
	unset($form_state['submit']);
	$data = json_encode($form_state);
	global $wpdb;
	$uid 	 = get_current_user_id();
	$table = $wpdb->prefix .'afl_user_payment_methods';
	$q = $wpdb->update($table, array('completed' => 1, 'data' => $data), array('uid'=>$uid,'method' => 'method_bank' ));
  if ($q) {
  	  echo wp_set_message(__('Your bank account details saved successfully'), 'success');
  }
}

/*
* ------------------------------------------------------------
* User paypal payment method configuration form
* ------------------------------------------------------------
*/
function afl_user_payment_conf_method_paypal_form(){
	if ( isset($_POST['submit']) ) {
	 	$validation = afl_user_payment_conf_method_paypal_form_validate($_POST);
	 	if ($validation) {
	 		afl_user_payment_conf_method_paypal_form_submit($_POST);
	 	}
	 }
	global $wpdb;
	$uid 					 = get_current_user_id();
	$table = $wpdb->prefix .'afl_user_payment_methods';
  $check = $wpdb->get_row("SELECT  * FROM `$table` WHERE `uid` = $uid AND `method` = 'method_paypal' AND `status` = 1 ");

  if(! $check){
   	wp_redirect('?page=affiliate-eps-payment_method');
  }
  $data = json_decode($check->data);
   $form = array();
 		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

	 	$form['paypal_email'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Paypal Email',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->paypal_email) ? $data->paypal_email : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['paypal_firstname'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'First Name',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->paypal_firstname) ? $data->paypal_firstname : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['paypal_surname'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Sur Name',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->paypal_surname) ? $data->paypal_surname : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['paypal_id'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Paypal Id',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->paypal_id) ? $data->paypal_id : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' =>'Save configuration'
	 	);
	 	echo afl_render_form($form);

}

/*
* ------------------------------------------------------------
* User bank payment method configuration form validate
* ------------------------------------------------------------
*/
function afl_user_payment_conf_method_paypal_form_validate($form_state){
	$rules = array();
		//create rules
		$rules[] = array(
	 		'value'=> $form_state['paypal_email'],
	 		'name' =>'Paypal Email',
	 		'field' =>'paypal_email',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_valid_email',
	 		)
	 	);
	 	$rules[] = array(
	 		'value'=> $form_state['paypal_firstname'],
	 		'name' =>'Paypal First name',
	 		'field' =>'paypal_firstname',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	 	$rules[] = array(
	 		'value'=> $form_state['paypal_surname'],
	 		'name' =>'Paypal Sur Name',
	 		'field' =>'paypal_surname',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	 	$rules[] = array(
	 		'value'=> $form_state['paypal_id'],
	 		'name' =>'Paypal ID ',
	 		'field' =>'paypal_id',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
		$resp  = set_form_validation_rule($rules);
		if (!$resp) {
			return false;
		}
		else
			return true;
}

/*
* ------------------------------------------------------------
* User paypal payment method configuration form submit
* ------------------------------------------------------------
*/
function afl_user_payment_conf_method_paypal_form_submit($form_state){
	unset($form_state['submit']);
	$data = json_encode($form_state);
	global $wpdb;
	$uid 	 = get_current_user_id();
	$table = $wpdb->prefix .'afl_user_payment_methods';
	$q = $wpdb->update($table, array('completed' => 1, 'data' => $data), array('uid'=>$uid,'method' => 'method_paypal' ));
  if ($q) {
  	  echo wp_set_message(__('Your Paypal  account details saved successfully'), 'success');
  }
}
/*
* ------------------------------------------------------------
* user payment autherization form
* ------------------------------------------------------------
*/
function  afl_user_payment_autherization_form(){

	if ( isset($_POST['submit']) ) {
		$validation = afl_user_payment_autherization_form_validation($_POST);
		if (!empty($validation)) {
			afl_user_payment_autherization_form_submit($_POST);
		  if (isset($_GET['redirect'])) {
				header("Location:".urldecode($_GET['redirect'])." ");
		  }
		}
	 }
	global $wpdb;
	$uid 					 = get_current_user_id();
	$table = $wpdb->prefix .'afl_user_payment_methods';
	$check = $wpdb->get_row("SELECT  * FROM `$table` WHERE `uid` = $uid ");

	 if( !$check){
   	$redirect = afl_variable_get('redirect_select_payment_method');
  	$params['redirect'] = urlencode($_SERVER['REQUEST_URI']);

		if ( !empty($redirect)) {
			header("Location:".afl_build_url($redirect,$params)." ");
		}
  }

  //display a message
  $table = _table_name('afl_transaction_authorization');
	$exist = $wpdb->get_row("SELECT  * FROM `$table` WHERE `uid` = $uid ");
	if(!$exist){
		wp_set_message('You need to complete step here before you can access this page', 'warning');
	}

	$form = array();
	 $form['#method'] = 'post';
	 $form['#action'] = $_SERVER['REQUEST_URI'];
	 $form['#prefix'] ='<div class="form-group row">';
	 $form['#suffix'] ='</div>';

	 $table = $table = $wpdb->prefix .'afl_transaction_authorization';
	 $exist = $wpdb->get_row("SELECT  * FROM `$table` WHERE `uid` = $uid ");
	 if($exist){
		$form['old_password'] = array(
	 		'#title' 		=>	'Old Password',
	 		'#type' 		=>	'password',
	 		'#required' =>	TRUE,
	 		'#name' 		=>	'Old Password',
	 	);
    }

		$form['password'] = array(
	 		'#title' 		=>	'Password',
	 		'#type' 		=>	'password',
	 		'#required' =>	TRUE,
	 		'#name' 		=> 	'Password',
	 	);
	 	$form['confirm_password'] = array(
	 		'#title' 		=> 	'Confirm Password',
	 		'#type' 		=> 	'password',
	 		'#required' =>	TRUE,
	 		'#name' 		=> 	'Confirm Password',
	 	);
	 
		$form['submit'] = array(
			'#type' => 'submit',
			'#value' =>'Save Password'
		);
		echo afl_render_form($form);

}
/*
* ------------------------------------------------------------
* user payment autherization form validate
* ------------------------------------------------------------
*/
function afl_user_payment_autherization_form_validation($form_state){
	global $wpdb;
	$uid 					 = get_current_user_id();
	$table = _table_name('afl_transaction_authorization');
	$exist = $wpdb->get_row("SELECT  * FROM `$table` WHERE `uid` = $uid ");
	if($exist){
		if( isset($form_state['old_password']) &&  empty($form_state['old_password'])){
			echo wp_set_message(__('Old Password field is reuired'), 'danger');
			return FALSE;
		}
		else{
			$old_pass = md5($form_state['old_password']);
				if($old_pass != $exist->password){
					echo wp_set_message(__('You entered an incorrect password'), 'danger');
					return FALSE;
				}
		}
	}	
	if( empty($form_state['password']) ||  empty($form_state['confirm_password']) ){
		echo wp_set_message(__('Password and Confirm Password fields are reuired'), 'danger');
		return FALSE;
	}
	elseif($form_state['password'] != $form_state['confirm_password']){
		$resp =  wp_set_message(__('Please check the password and confirm pasword you entered'), 'danger');
		return $resp;
		return FALSE;
	}else{
		 return true;
	}
}

/*
* ------------------------------------------------------------
* user payment autherization form submit
* ------------------------------------------------------------
*/
function afl_user_payment_autherization_form_submit($form_state){
	global $wpdb;
	$uid 					 = get_current_user_id();
	$password = md5($form_state['password']);
	$table = $wpdb->prefix .'afl_transaction_authorization';
	$exist = $wpdb->get_row("SELECT  * FROM `$table` WHERE `uid` = $uid ");
	if($exist){
				$wpdb->update($table, array('password'=>$password), array('uid'=>$uid));
				echo wp_set_message(__('Your password changed successfully..'), 'success');
	}elseif(!$exist){
	   	$insert['uid'] 			= $uid;
	   	$insert['password']  	=	$password;
	   	$q = $wpdb->insert($table, $insert);
			echo wp_set_message(__('Your Autherization Password Saved successfully..'), 'success');
	}
	else{
			echo wp_set_message(__('Some error occured. Please try again later..'), 'success');
	}

}

/*
* ------------------------------------------------------------
* User error payment configuration form
* ------------------------------------------------------------
*/
function afl_user_payment_conf_error_form(){
	pr("Please choose another payout method and withdraw your amount... :),,:)");
}




/*
* ------------------------------------------------------------
* User Hyper Wallet payment method configuration
* ------------------------------------------------------------
*/
function afl_user_payment_conf_method_hyperwallet_form(){

	 if ( isset($_POST['submit']) ) {
	 	$validation = afl_user_payment_conf_method_hyperwallet_form_validation($_POST);
	 	if ($validation) {
	 		afl_user_payment_conf_method_hyperwallet_form_submit($_POST);
	 		 if (isset($_GET['redirect'])) {
				header("Location:".urldecode($_GET['redirect'])." ");
		  }
	 	}
	 }
	global $wpdb;
	$uid 					 = get_current_user_id();
	$table = $wpdb->prefix .'afl_user_payment_methods';
  $check = $wpdb->get_row("SELECT  * FROM `$table` WHERE `uid` = $uid AND `method` = 'method_hyperwallet' AND `status` = 1 ");
  if(! $check){
		echo wp_set_message('You need to complete step here before you can access this page', 'warning');
   	$redirect = afl_variable_get('redirect_select_payment_method');
		$params['redirect'] = urlencode($_SERVER['REQUEST_URI']);

		if ( !empty($redirect)) {
			header("Location:".afl_build_url($redirect,$params)." ");
		}
  }

  //check set transaction password
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
// pr($check,1);
	$data = json_decode($check->data);
	// pr($_SERVER['REQUEST_URI']);
	$user = wp_get_current_user();
	$user_name = $user->user_login;
	$user_email = $user->user_email;
	// pr($user->user_email);
	// pr($user);
	if($data) {
		// pr($check);
					// $response = apply_filters('afl_hyper_wallet_payout', array('uid'=>$uid,'amount'=>2));
					// pr($response);
		print do_shortcode("[hyper_wallet_acc_detail data='".$check->data."']");
	}else {
		print 'Your Wallet is not yet created. Create One.';
   	$form = array();
 		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

	 	$form['clientUserId'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Client User Id',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->clientUserId) ? $data->clientUserId : $user_name,
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'

	 	);


	 	$form['email'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Email',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->email) ? $data->email : $user_email,
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);



	 	$form['firstName'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'First Name',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->firstName) ? $data->firstName : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);

	 	$form['lastName'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Last Name',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->lastName) ? $data->lastName : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);


	 	$form['addressLine1'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Address Line 1',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->addressLine1) ? $data->addressLine1 : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['city'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'city',
	 		'#required'				=> TRUE,
	 		'#default_value' 	=> isset($data->city) ? $data->city : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);

	 	$form['country'] = array(
	 		'#type' 					=> 'select',
	 		'#title' 					=> 'Country',
	 		'#default_value' 	=> isset($data->country) ? $data->country : '',
	 		'#options' => afl_hyper_wallet_counries(),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#required'				=> TRUE,
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['stateProvince'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'State Province',
	 		'#default_value' 	=> isset($data->stateProvince) ? $data->stateProvince : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#required'				=> TRUE,
	 		'#suffix' 				=> '</div>'
	 	);

	 	$form['postalCode'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Postal Code',
	 		'#default_value' 	=> isset($data->postalCode) ? $data->postalCode : '',
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#required'				=> TRUE,
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' =>'Save configuration'
	 	);
	 	echo afl_render_form($form);
	}
}


/*
* ------------------------------------------------------------
* User Hyper Wallet method configuration form submit
* ------------------------------------------------------------
*/
function afl_user_payment_conf_method_hyperwallet_form_submit($form_state){
	unset($form_state['submit']);
	global $wpdb;
	$uid 	 = get_current_user_id();
	$table = $wpdb->prefix .'afl_user_payment_methods';

	/**
	 * Hyper Wallet Account Creation
	 */
	$hyper_wallet_detail = afl_hyper_wallet_new_user($form_state);
	// pr($hyper_wallet_detail,1);

	if(isset($hyper_wallet_detail['token']) && !empty($hyper_wallet_detail['token'])) {
		$form_state += $hyper_wallet_detail;
		$data = json_encode($form_state);
		$q = $wpdb->update($table, array('completed' => 1, 'data' => $data), array('uid'=>$uid,'method' => 'method_hyperwallet' ));
	  if ($q) {
	  	  echo wp_set_message(__('Your Hyper Wallet account created and saved successfully'), 'success');
	  }
	}

  if (isset($_GET['redirect'])) {
		header("Location:".$_GET['redirect']." ");
  }
}


/*
* ------------------------------------------------------------
* User Hyper Wallet payment method configuration form validation
* ------------------------------------------------------------
*/
function afl_user_payment_conf_method_hyperwallet_form_validation($form_state){
		$rules = array();
		//create rules
		$rules[] = array(
	 		'value'=> $form_state['clientUserId'],
	 		'name' =>'Client User ID',
	 		'field' =>'clientUserId',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=> $form_state['email'],
	 		'name' =>'Client Email',
	 		'field' =>'email',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);


	 	$rules[] = array(
	 		'value'=> $form_state['firstName'],
	 		'name' =>'First Name',
	 		'field' =>'firstName',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	 	$rules[] = array(
	 		'value'=> $form_state['lastName'],
	 		'name' =>'Last Name',
	 		'field' =>'lastName',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	 	$rules[] = array(
	 		'value'=> $form_state['addressLine1'],
	 		'name' =>'Address Line 1',
	 		'field' =>'addressLine1',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	 	$rules[] = array(
	 		'value'=> $form_state['city'],
	 		'name' =>'City',
	 		'field' =>'city',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=> $form_state['stateProvince'],
	 		'name' =>'State Province',
	 		'field' =>'stateProvince',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=> $form_state['country'],
	 		'name' =>'Country',
	 		'field' =>'country',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=> $form_state['postalCode'],
	 		'name' =>'Postal Code',
	 		'field' =>'postalCode',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);

		$resp  = set_form_validation_rule($rules);
		if (!$resp) {
			return  $resp;
			
		}
		else
			return true;
}