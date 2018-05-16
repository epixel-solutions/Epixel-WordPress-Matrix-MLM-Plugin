<?php 
/*
 * ------------------------------------------------
 * Admin payout configuration menu call back
 * ------------------------------------------------
*/
function afl_admin_payout_configuration(){
		echo afl_eps_page_header();
		new Afl_enque_scripts('common');
		
	$post = array();
	if (isset($_POST['submit']) ){
			$post = $_POST;
			$validation = afl_payout_configuration_form_validation($_POST);
		if (!empty($validation)) {
	 		afl_payout_configuration_form_submit($_POST);
	 	}
	}

	afl_payout_configuration_form($post);
}
/*
 * ------------------------------------------------
 * Admin payout configuration form
 * ------------------------------------------------
*/
function afl_payout_configuration_form($post){

$payout_methods = list_extract_allowed_values(afl_variable_get('payout_methods'),'list_text',FALSE);

	$form = array();
	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';
	$color_hr = afl_variable_get('mlm_hr_color', '#7266ba');

 	$form['afl_system_payout_frequency'] = array(
 		'#title' 					=> 'Payout',
 		'#required' 			=> TRUE,
 		'#type'  					=> 'select',
 		'#options' 				=> afl_get_periods(),
 		'#default_value' 	=> isset($post['afl_system_payout_frequency']) ? $post['afl_system_payout_frequency'] : (!empty(afl_variable_get('afl_system_payout_frequency')) ? afl_variable_get('afl_system_payout_frequency') : '') ,
 		
 	);
 	$form['field'] = array(
 		'#type' => 'fieldset',
 		'#title'=>'Payout Configurations',
 	);
	$form['field']['payout_min_value'] = array(
		'#type' 					=> 'text',
		'#required' 			=> TRUE,
		'#addon'					=>	'$',
		'#title'					=> 'Minimum payout amount',
		'#default_value' 	=>  isset($post['payout_min_value']) ? $post['payout_min_value'] : (!empty(afl_variable_get('payout_min_value')) ? afl_variable_get('payout_min_value') : '-1') ,
		
	);
	foreach ($payout_methods as $key => $value) {
		$form['field']['payout_charges_'.$key] = array(
		'#type' 					=> 'text',
		'#title'					=> 'Payout charges - '.$value,
		'#required' 			=> TRUE,
		'#default_value' 	=> isset($post['payout_charges_'.$key]) ? $post['payout_charges_'.$key] : (!empty(afl_variable_get('payout_charges_'.$key)) ? afl_variable_get('payout_charges_'.$key) : '') ,
		'#prefix'=>'<div class="form-group row">',
		
	);
	}
	
	$form['field']['payout_conf'] = array(
   '#type' => 'markup',
   '#markup' => '<hr style="border:2px solid '.$color_hr.'; color:'.$color_hr.'; margin:60px 0px 60px 0px">',
 );

	$form['field_1'] = array(
 		'#type' 	=> 'fieldset',
 		'#title'	=>'Withdrawal Settings',
 	);
	$form['field_1']['withdrawal_min_value'] = array(
		'#type' 					=> 'text',
		'#title'					=> 'Minimum withdrawal amount',
		'#required' 			=> TRUE,
		'#default_value' 	=> isset($post['withdrawal_min_value']) ? $post['withdrawal_min_value'] : (!empty(afl_variable_get('withdrawal_min_value')) ? afl_variable_get('withdrawal_min_value') : '') ,
		
	);
	$form['field_1']['withdrawal_max_value'] = array(
		'#type' 					=> 'text',
		'#title'					=> 'Maximum withdrawal amount',
		'#required' 			=> TRUE,
		'#default_value' 	=> isset($post['withdrawal_max_value']) ? $post['withdrawal_max_value'] : (!empty(afl_variable_get('withdrawal_max_value')) ? afl_variable_get('withdrawal_max_value') : '') ,
		
	);
	$form['field_1']['withdrawal_conf'] = array(
   '#type' => 'markup',
   '#markup' => '<hr style="border:2px solid '.$color_hr.'; color:'.$color_hr.'; margin:60px 0px 60px 0px">',
 );

	/*Ewallet Transfer*/

	/*$form['field_2'] = array(
 		'#type' 		=> 'fieldset',
 		'#title'		=>'E-wallet Transfer',
 	);
	$form['field_2']['etransfer_min_value'] = array(
		'#type' 						=> 'text',
		'#title'						=> ' Minimum amount to initiate e-wallet transfer',
		'#required' 				=> TRUE,
		'#default_value' 		=> isset($post['etransfer_min_value']) ? $post['etransfer_min_value'] : (!empty(afl_variable_get('etransfer_min_value')) ? afl_variable_get('etransfer_min_value') : '') ,
		
	);
	$form['field_2']['etransfer_max_value'] = array(
		'#type' 						=> 'text',
		'#title'						=> 'Maximum amount can be transferred from e-wallet',
		'#required' 				=> TRUE,
		'#default_value' 		=> isset($post['etransfer_max_value']) ? $post['etransfer_max_value'] : (!empty(afl_variable_get('etransfer_max_value')) ? afl_variable_get('etransfer_max_value') : '') ,
		
	);
	$form['field_2']['etransfer_charges'] = array(
		'#type' 					=> 'text',
		'#title'					=> 'E-wallet Transfer charges',
		'#required' 			=> TRUE,
		'#default_value' 	=> isset($post['etransfer_charges']) ? $post['etransfer_charges'] : (!empty(afl_variable_get('etransfer_charges')) ? afl_variable_get('etransfer_charges') : '') ,
		
	);
	$form['field_2']['withdrawal_conf'] = array(
   '#type'		 => 'markup',
   '#markup'	 => '<hr style="border:2px solid '.$color_hr.'; color:'.$color_hr.'; margin:60px 0px 60px 0px">',
 );*/
	$form['submit'] = array(
 		'#title' 			=> 'Submit',
 		'#type' 			=> 'submit',
 		'#value' 			=> 'Save Configuration',
 		'#attributes' => array(
 			'class' 	=> array(
 				'btn','btn-primary'
 			)
 		),
 	);

	echo afl_render_form($form);

}

/*
 * ------------------------------------------------
 * Admin payout configuration form validation
 * ------------------------------------------------
*/
function afl_payout_configuration_form_validation($form_state){
 // pr($form_state);
 $payout_methods = list_extract_allowed_values(afl_variable_get('payout_methods'),'list_text',FALSE);
		$rules = array();
		if(isset($form_state['afl_system_payout_frequency'])){
			$rules[] = array(
		 		'value'=> $form_state['afl_system_payout_frequency'],
		 		'name' =>'Payout Freequency',
		 		'field' =>'afl_system_payout_frequency',
		 		'rules' => array(
		 			'rule_required',
		 		)
			);
		}
		if(isset($form_state['payout_min_value'])){
			$rules[] = array(
		 		'value'=> $form_state['payout_min_value'],
		 		'name' =>'Minimum payout amount',
		 		'field' =>'payout_min_value',
		 		'rules' => array(
		 			'rule_required',
		 			'rule_is_numeric_posative'
		 		)
			);
		}
		if(isset($payout_methods)){
			foreach ($payout_methods as $key => $value) {
				$rules[] = array(
			 		'value'=> $form_state['payout_charges_'.$key],
			 		'name' => 'Payout charges - '.$value,
			 		'field' =>'payout_charges_'.$key,
			 		'rules' => array(
			 			'rule_required',
			 			'rule_is_numeric_percentage_posative'
			 		)
				);
			}
		}
		$rules[] = array(
	 		'value'=> $form_state['withdrawal_min_value'],
	 		'name' =>'Minimum Withdrawal Amount',
	 		'field' =>'withdrawal_min_value',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric_posative'
	 		)
		);
		$rules[] = array(
		 		'value'=> $form_state['withdrawal_max_value'],
		 		'name' => 'Maximum Withdrawal Value',
		 		'field' =>'withdrawal_max_value',
		 		'rules' => array(
		 			'rule_required',
		 			'rule_is_numeric_percentage_posative'
		 		)
		);
		if(isset($form_state['etransfer_min_value'])){
			$rules[] = array(
			 		'value'=> $form_state['etransfer_min_value'],
			 		'name' => 'Minumum E-wallet Transfer Amount',
			 		'field' =>'etransfer_min_value',
			 		'rules' => array(
			 			'rule_required',
			 			'rule_is_numeric_posative'
			 		)
			);
		}
		if(isset($form_state['etransfer_max_value'])){
			$rules[] = array(
			 		'value'=> $form_state['etransfer_max_value'],
			 		'name' => 'Maximum E-wallet Transfer Amount',
			 		'field' =>'etransfer_max_value',
			 		'rules' => array(
			 			'rule_required',
			 			'rule_is_numeric_percentage_posative'
			 		)
			);
		}
		if(isset($form_state['etransfer_charges'])){
			$rules[] = array(
			 		'value'=> $form_state['etransfer_charges'],
			 		'name' => 'E-wallet Transfer Charges',
			 		'field' =>'etransfer_charges',
			 		'rules' => array(
			 			'rule_required',
			 			'rule_is_numeric_percentage_posative'
			 		)
			);
		}
		$resp  = set_form_validation_rule($rules);
		if (!$resp) {
			return false;
		}
		else
			return true;
}
/*
 * ------------------------------------------------
 * Admin payout configuration form submit
 * ------------------------------------------------
*/
function afl_payout_configuration_form_submit($post){
	foreach ($post as $key => $value) {
				afl_variable_set($key, maybe_serialize($value));
			}
	echo wp_set_message(__('Configuration has been saved successfully.'), 'success');

		
}