<?php 

function afl_epin_configurations(){
	do_action('eps_affiliate_page_header');
	do_action('afl_content_wrapper_begin');
		afl_epin_configuration_form();
	do_action('afl_content_wrapper_end');
}
function afl_epin_configuration_form(){
	new Afl_enque_scripts('common');
	
	if (isset($_POST['submit']) ){
			$post = $_POST;
			$validation = afl_epin_configuration_form_validation($_POST);
		if (!empty($validation)) {
	 		afl_epin_configuration_form_submit($_POST);
	 	}
	}
	$form = array();
	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';
	$color_hr = afl_variable_get('mlm_hr_color', '#7266ba');
	$currency = afl_currency_symbol();

	$form['epin_conf'] = array(
		 '#type' 				=> 'fieldset',
		 '#title'				=>'Configuration for E-pin Generation'
	);

	$form['epin_conf']['epin_minimum_amount'] = array(
		'#type' 					=> 'text',
		'#title'					=> 'Minimum amount of denomination of E-pin '.$currency,
		'#required' 			=> TRUE,
		'#default_value'	=> isset($post['epin_minimum_amount']) ? $post['epin_minimum_amount'] : (!empty(afl_variable_get('epin_minimum_amount')) ? afl_variable_get('epin_minimum_amount') : '0') ,
	);


	$form['epin_conf']['epin_maximum_amount'] = array(
		'#type' 					=> 'text',
		'#title'					=> 'Maximum amount of denomination of E-pin '.$currency,
		'#required' 			=> TRUE,
		'#default_value' 	=> isset($post['epin_maximum_amount']) ? $post['epin_maximum_amount'] : (!empty(afl_variable_get('epin_maximum_amount')) ? afl_variable_get('epin_maximum_amount') : '0') ,
	);

	$form['epin_conf']['epin_members_active_epins'] = array(
		'#type' 					=> 'text',
		'#title'					=> 'Maximum active E-pin per member',
		'#required' 			=> TRUE,
		'#default_value' 	=> isset($post['epin_members_active_epins']) ? $post['epin_members_active_epins'] : (!empty(afl_variable_get('epin_members_active_epins')) ? afl_variable_get('epin_members_active_epins') : '0') ,
	);

	$form['epin_conf']['epin_generation_fee'] = array(
		'#type' 					=> 'text',
		'#title'					=> 'The E-pin Geneation fee/ charge',
		'#required' 			=> TRUE,
		'#default_value' 	=>  isset($post['epin_generation_fee']) ? $post['epin_generation_fee'] : (!empty(afl_variable_get('epin_generation_fee')) ? afl_variable_get('epin_generation_fee') : '0') ,
	);

	$form['epin_conf']['epin_length'] = array(
		'#type' 					=> 'text',
		'#title'					=> 'Length of E-pin ',
		'#required' 			=> TRUE,
		'#default_value' 	=>  isset($post['epin_length']) ? $post['epin_length'] : (!empty(afl_variable_get('epin_length')) ? afl_variable_get('epin_length') : '0') ,
	);

	$form['submit'] 	= array(
		'#type' 				=> 'submit',
		'#value'				=> 'Save configuration',

	);

	echo apply_filters('afl_render_form',$form);
}

function afl_epin_configuration_form_validation($form_state){
	$rules = array();
		
		$rules[] = array(
	 		'value'=> $form_state['epin_minimum_amount'],
	 		'name' =>'E-pin Minimum Amount',
	 		'field' =>'epin_minimum_amount',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric_posative',
	 		)
		);
		$rules[] 	= array(
	 		'value'	=> $form_state['epin_maximum_amount'],
	 		'name' 	=>'E-pin Maximum Amount',
	 		'field' =>'epin_maximum_amount',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric_posative',
	 		)
	 	);
		$rules[] 	= array(
	 		'value'	=> $form_state['epin_members_active_epins'],
	 		'name' 	=>'Maximum active E-pin per member',
	 		'field' =>'epin_members_active_epins',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric_posative',
	 		)
	 	);
		$rules[] 	= array(
	 		'value'	=> $form_state['epin_generation_fee'],
	 		'name' 	=>'E-pin Generation fee',
	 		'field' =>'epin_generation_fee',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric_percentage_posative',
	 		)
	 	);
		$rules[] 	= array(
	 		'value'	=> $form_state['epin_length'],
	 		'name' 	=>'Length of E-pin',
	 		'field'	=>'epin_length',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric_posative',
	 		)
	 	);
		
		$resp  = set_form_validation_rule($rules);
		if (!$resp) {
			return false;
		}
		else
			return true;
}
function afl_epin_configuration_form_submit($form_state){
foreach ($form_state as $key => $value) {
				afl_variable_set($key, maybe_serialize($value));
			}
	echo wp_set_message(__('Configuration has been saved successfully.'), 'success');
}
