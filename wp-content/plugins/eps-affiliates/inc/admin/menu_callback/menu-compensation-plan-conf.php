<?php 
/*
 * -------------------------------------------------------------------
 * Compensation plan menu callbacks for admin menus
 * -------------------------------------------------------------------
*/
function afl_admin_compensation_plan_configuration() {
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
		afl_admin_compensation_plan_config_tabs();
	afl_content_wrapper_end();
	
}
/*
 * ------------------------------------------------------------------
 * Tabs
 * ------------------------------------------------------------------
*/
	function afl_admin_compensation_plan_config_tabs () {
		new Afl_enque_scripts('common');

		$matrix_active = $basic_active = $fsb_active = $incentives_active = $free_account =  $other = '';
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'basic';  
		
		switch ($active_tab) {
			case 'basic':
					$basic_active  = 'active';
			break;
			case 'matrix-compensation':
					$matrix_active  = 'active';
			break;
			case 'fast-start-bonus':
					$fsb_active  = 'active';
			break;
			case 'incentives':
					$incentives_active  = 'active';
			break;
			case 'free-account':
					$free_account  = 'active';
			break;
			case 'extra-configs':
					$other  = 'active';
			break;
		}
		  //here render the tabs
		  echo '<ul class="tabs--primary nav nav-tabs">';
		  
		  echo '<li class="'.$basic_active.'">
		            	<a href="?page=affiliate-eps-compensation-plan-configurations&tab=basic" >Basic configuration</a>  
		          </li>';

		  echo '<li class="'.$matrix_active.'">
		            	<a href="?page=affiliate-eps-compensation-plan-configurations&tab=matrix-compensation" >Matrix Compensation</a>  
		          </li>';
		  echo '<li class="'.$fsb_active.'">
		            	<a href="?page=affiliate-eps-compensation-plan-configurations&tab=fast-start-bonus" >Fast Start Bonus</a>  
		          </li>';
		  echo '<li class="'.$incentives_active.'">
		            	<a href="?page=affiliate-eps-compensation-plan-configurations&tab=incentives" >Incentives</a>  
		          </li>';
		  echo '<li class="'.$free_account.'">
		            	<a href="?page=affiliate-eps-compensation-plan-configurations&tab=free-account" >Free Account</a>  
		          </li>';
		  echo '<li class="'.$other.'">
		            	<a href="?page=affiliate-eps-compensation-plan-configurations&tab=extra-configs" >Extra configs</a>  
		          </li>';
		  echo '</ul>';

		  switch ($active_tab) {
		  	case 'basic':
		  		afl_admin_compensation_plan_config_();
		  	break;
		  	case 'matrix-compensation':
		  		afl_admin_matrix_compensation_config_();
		  	break;
		  	case 'fast-start-bonus':
		  		afl_admin_fsb_compensation_config_();
		  	break;
		  	case 'incentives':
		  		afl_admin_incentives_config();
		  	break;
		  	case 'free-account':
		  		afl_admin_free_account_config();
		  	break;
		  	case 'extra-configs':
		  		afl_admin_extra_config();
		  	break;
		  }

	}
/*
 * -------------------------------------------------------------------
 * Admin compensation plan configuration form
 * -------------------------------------------------------------------
*/
function afl_admin_compensation_plan_config_(){
		new Afl_enque_scripts('common');
		
	 if ( isset($_POST['submit']) ) {
	 	$validation = afl_admin_compensation_plan_form_validation($_POST);
	 	if (!empty($validation)) {
	 		afl_admin_compensation_plan_form_submit($_POST);
	 	}
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

	$table['#header'] 		= array('Plan Configuration','');

	/*--------------------- Rows ---------------------*/
	$i = 0;

	$rows[$i]['label_payout_mode'] = array(
		'#type' => 'label',
		'#title'=> 'Compensation Release Payout',
 	);
	$rows[$i]['matrix_plan_payout_mode'] = array(
		'#type' 					=> 'select',
		'#attributes'			=>array('form-select','select'),
		'#options' 				=> afl_get_payout_modes(),
		'#default_value' 	=> afl_variable_get('matrix_plan_payout_mode',''),
 	);
	$i++;

	$rows[$i]['label_payout_date'] = array(
		'#type' => 'label',
		'#title'=> 'Compensation Release Payout Date',
 	);
	$rows[$i]['matrix_plan_payout_date'] = array(
		'#type' 					=> 'text',
		'#preffix' 			=> '<div class="form-item clearfix form-type-textfield form-group" data-toggle="tooltip">',
		'#suffix' 			=> '</div>',
		'#attributes'		=> array(
						'class' => array(
								'form-text',
								'form-control',
						)
		),
		'#default_value' 	=> afl_variable_get('matrix_plan_payout_date',''),
 	);
	$i++;

	$rows[$i]['label_2'] = array(
		'#type' => 'label',
		'#title'=> 'Width of matrix',
 	);
	$rows[$i]['matrix_plan_width'] = array(
		'#type' 					=> 'select',
		'#attributes'			=>array('form-select','select'),
		'#options' 				=> afl_get_levels(),
		'#default_value' 	=> afl_variable_get('matrix_plan_width',3),
 	);
 	$i++;
 	$rows[$i]['label_3'] = array(
		'#type' => 'label',
		'#title'=> 'Height of matrix',
 	);
	$rows[$i]['matrix_plan_height'] = array(
		'#type' 					=> 'select',
		
		'#options' 				=> afl_get_levels(),
		'#default_value' 	=> afl_variable_get('matrix_plan_height',3),
 	);
 	$i++;
	$rows[$i]['label_1'] = array(
		'#type' => 'label',
		'#title'=> 'Total number of ranks',
	);
	$rows[$i]['number_of_ranks'] = array(
		'#type' 					=> 'select',
		'#attributes'			=>array('form-select','select'),
		'#options' 				=> afl_get_levels(),
		'#default_value' 	=> afl_variable_get('number_of_ranks',1),
 	);
	/* Enable Holding Expiry */
 	$i++;
	$rows[$i]['label_1'] = array(
		'#type' => 'label',
		'#title'=> 'Holding tank Expiry Autoplace',
	);
	
	$rows[$i]['holding_tank_expiry_autoplace'] = array(
		'#type' 					=> 'checkbox',
		// '#title'					=> 'Enable this, member will be automatically placed after expired',
		'#default_value' 	=> afl_variable_get('holding_tank_expiry_autoplace',FALSE),
		'#description'		=> 'Automatically place user after holding days expired'
 	);
	/*---- Holding tank expiry days*/
 	$i++;
 	$rows[$i]['label_1'] = array(
		'#type' => 'label',
		'#title'=> 'Holding tank holding days',
	);
	$rows[$i]['holding_tank_holding_days'] = array(
		'#type' 					=> 'text',
		'#default_value' 	=> afl_variable_get('holding_tank_holding_days',7),
 	);


 	$table['#rows'] = $rows;


	$render_table  = '';
	$render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-afl-compensation-paln'));
	$render_table .= afl_render_table($table);
	$render_table .= afl_input_button('submit', 'Save configuration', '',array('class'=>'btn btn-default btn-primary'));

	$render_table .= afl_form_close();

	echo $render_table;
}

/* 
 * ----------------------------------------------------------------------------
 * Form Validation
 * ----------------------------------------------------------------------------
*/
function afl_admin_compensation_plan_form_validation($POST){
	global $reg_errors;
	$reg_errors = new WP_Error;
	$flag 			= 1;
	// $values = $POST['var'];
	if ( is_wp_error( $reg_errors ) ) {
    foreach ( $reg_errors->get_error_messages() as $error ) {
				$flag = 0;
    		echo wp_set_message($error, 'danger');
    }
	}
	return $flag;
}

/* 
 * ----------------------------------------------------------------------------
 * Form Submit action
 * ----------------------------------------------------------------------------
*/
function afl_admin_compensation_plan_form_submit($POST){
	$checking_vars	 = array();
	$checking_vars[] = 'holding_tank_expiry_autoplace';

	foreach ($POST as $key => $value) {
		afl_variable_set($key, maybe_serialize($value));
	}

	foreach ($checking_vars as $key ) {
		if ( !array_key_exists($key, $POST)) {
			afl_variable_set($key, '');
		}	
	}

	echo wp_set_message(__('Configuration has been saved successfully.'), 'success');

	}


/*
 * ----------------------------------------------------------------------------
 * Matrix compensation plan cofig
 * ----------------------------------------------------------------------------
*/
 function afl_admin_matrix_compensation_config_ () {
 	if ( isset($_POST['submit'])) {
 		$validation = afl_admin_matrix_compensation_config_validation($_POST);

 		if ($validation) {
 			afl_admin_matrix_compensation_config_submit($_POST);
 		}
 	}
 	$color_hr = afl_variable_get('mlm_hr_color', '#7266ba');

 	$form = array();
	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';

 	$form['fieldset_1'] = array(
 		'#type' => 'fieldset',
 		'#title' =>'Basic configuration',
 	);

 	$form['fieldset_1']['matrix_compensation_period_maximum'] = array(
 		'#title' 	=> 'Compensation Period',
 		'#type'  	=> 'select',
 		'#name'		=> 'matrix-compensation-period-maximum',
 		'#options' => array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9),
 		'#required' => TRUE,
 		'#default_value'=> afl_variable_get('matrix_compensation_period_maximum',''),

 	);
 	$options = array();
 	for ( $i = 1; $i <= 31; $i++) {
 		$options[$i] =  $i;
 	}

 	$form['fieldset_1']['matrix_compensation_given_day'] = array(
 		'#title' 	=> 'Compensation Given Day',
 		'#type'  	=> 'select',
 		'#name'		=> 'matrix-compensation-given-day',
 		'#required' => TRUE,
 		'#default_value'=> afl_variable_get('matrix_compensation_given_day',''),
 		'#options' => $options
 	);


 	$matrix_compensation_max_level = afl_variable_get('matrix_compensation_max_level','');
 	$form['fieldset_1']['matrix_compensation_max_level'] = array(
 		'#title' 	=> 'Compensation Maximum Level',
 		'#type'  	=> 'select',
 		'#name'		=> 'matrix-compensation-max-level',
 		'#required' => TRUE,
 		'#options' => array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9),
 		'#default_value'=> $matrix_compensation_max_level,

 	);

 	$form['fieldset_1']['markup'] = array(
   '#type' => 'markup',
   '#markup' => '<hr style="border:2px solid '.$color_hr.'; color:'.$color_hr.'; margin:60px 0px 60px 0px">',
 	);

 	$form['fieldset_3'] = array(
 		'#type' => 'fieldset',
 		'#title' =>'Matrix Compensation Criteria',
 	);


 	for ($i=1; $i <= $matrix_compensation_max_level ; $i++) { 
 		$form['fieldset_3']['matrix_compensation_lvl_'.$i.'_min_spons'] = array(
	 		'#title' 	=> 'Minimum Count of Sponsors (Level'.$i.')',
	 		'#type'  	=> 'text',
	 		'#default_value'=> afl_variable_get('matrix_compensation_lvl_'.$i.'_min_spons',0),
	 		'#required' => TRUE,

	  );
 	}


 	$form['fieldset_3']['markup'] = array(
   '#type' => 'markup',
   '#markup' => '<hr style="border:2px solid '.$color_hr.'; color:'.$color_hr.'; margin:60px 0px 60px 0px">',
 	);

 	$form['fieldset_2'] = array(
 		'#type' => 'fieldset',
 		'#title' =>'Each Month Compensation',
 	);

 	$form['fieldset_2']['month_1_matrix_compensation'] = array(
 		'#title' 	=> 'First Month Compensation',
 		'#type'  	=> 'text',
 		'#default_value'=> afl_variable_get('month_1_matrix_compensation',''),
 		'#required' => TRUE,

  );
  $form['fieldset_2']['month_2_matrix_compensation'] = array(
 		'#title' 	=> 'Second Month Compensation',
 		'#type'  	=> 'text',
 		'#default_value'=> afl_variable_get('month_2_matrix_compensation',''),
 		'#required' => TRUE,

  );
  $form['fieldset_2']['month_3_matrix_compensation'] = array(
 		'#title' 	=> 'Third Month Compensation',
 		'#type'  	=> 'text',
 		'#default_value'=> afl_variable_get('month_3_matrix_compensation',''),
 		'#required' => TRUE,

  );

  $form['fieldset_2']['markup'] = array(
   '#type' => 'markup',
   '#markup' => '<hr style="border:2px solid '.$color_hr.'; color:'.$color_hr.'; margin:60px 0px 60px 0px">',
 	);


 	$form['submit'] = array(
 		'#type' => 'submit',
 		'#value' => 'Save configuration'
 	);
 	echo afl_render_form($form);
 
 }

 /*
 	* ----------------------------------------------------------------------------
	* Validating the input fields
 	* ----------------------------------------------------------------------------
 */
 	function afl_admin_matrix_compensation_config_validation ($form_state = array()) {
 		$rules = array();
		//create rules
		$rules[] = array(
	 		'value'=> $form_state['matrix_compensation_period_maximum'],
	 		'name' =>'Matrix compensation period',
	 		'field' =>'matrix_compensation_period_maximum',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric'
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=> $form_state['month_1_matrix_compensation'],
	 		'name' =>'First month matrix compensation',
	 		'field' =>'month_1_matrix_compensation',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric'
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=> $form_state['month_2_matrix_compensation'],
	 		'name' =>'Second month matrix compensation',
	 		'field' =>'month_2_matrix_compensation',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric'
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=> $form_state['month_3_matrix_compensation'],
	 		'name' =>'Third month matrix compensation',
	 		'field' =>'month_3_matrix_compensation',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric'
	 		)
	 	);

	 	$resp  = set_form_validation_rule($rules);
	 	return $resp;
 	}

/*
 * ----------------------------------------------------------------------------
 * Submit hook
 * ----------------------------------------------------------------------------
*/
 function afl_admin_matrix_compensation_config_submit ($form_state = array()) {
 	unset($form_state['submit']);
 	foreach ($form_state as $key => $value) {
 		afl_variable_set($key,$form_state[$key]);
 	}

 	echo wp_set_message('Configuration has been saved successfully', 'success');
 }









/*
 * ----------------------------------------------------------------------------
 *  Fast start bonus configs
 * ----------------------------------------------------------------------------
*/
 function afl_admin_fsb_compensation_config_ () {

 	if ( isset($_POST['submit'])) {
 		$post = $_POST;
 		unset($_POST['submit']);
 		if ( afl_admin_fsb_compensation_config_validation($_POST)) {
 			afl_admin_fsb_compensation_config_submit($_POST);
 		}
 	}

 	$form = array();
	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';

 	$form['enable_fast_start_bonus'] = array(
 		'#title' 	=> 'Enable Fast start Bonus',
 		'#type'  	=> 'checkbox',
 		'#default_value'=> !empty($post['enable_fast_start_bonus']) ? $post['enable_fast_start_bonus'] : afl_variable_get('enable_fast_start_bonus',''),
  );

  $form['fast_start_bonus_amount'] = array(
 		'#title' 	=> 'Fast start Bonus Amount',
 		'#type'  	=> 'text',
 		'#default_value'=> !empty($post['fast_start_bonus_amount']) ? $post['fast_start_bonus_amount'] : afl_variable_get('fast_start_bonus_amount',''),
 		'#required'	=> TRUE
  );

  $form['fast_start_bonus_pv'] = array(
 		'#title' 	=> 'Fast start Bonus PV',
 		'#type'  	=> 'text',
 		'#default_value'=> !empty($post['fast_start_bonus_pv']) ? $post['fast_start_bonus_pv'] : afl_variable_get('fast_start_bonus_pv',''),
 		'#required'	=> TRUE
  );

  $form['submit'] = array(
 		'#type' => 'submit',
 		'#value' => 'Save configuration'
 	);
 	echo afl_render_form($form);
 }
/*
 * ----------------------------------------------------------------------------
 *  Fast start bonus configs validation
 * ----------------------------------------------------------------------------
*/
	function afl_admin_fsb_compensation_config_validation($form_state = array()) {
		$rules = array();
		//create rules
		$rules[] = array(
	 		'value'=> $form_state['fast_start_bonus_amount'],
	 		'name' =>'Fast start bonus amount',
	 		'field' =>'fast_start_bonus_amount',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric'
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=> $form_state['fast_start_bonus_pv'],
	 		'name' =>'Fast start bonus pv',
	 		'field' =>'fast_start_bonus_pv',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric'
	 		)
	 	);
	 	$resp  = set_form_validation_rule($rules);
	 	return $resp;
	}
/*
 * ----------------------------------------------------------------------------
 *  Fast start bonus configs submit
 * ----------------------------------------------------------------------------
*/
	function afl_admin_fsb_compensation_config_submit ( $form_state = array()) {
		$checked_variables = array(
			'enable_fast_start_bonus'
		);
		//check the checkbox set or not, if not unset the check boxes
		foreach ($checked_variables as $key ) {
			if ( !array_key_exists($key, $form_state)) {
				afl_variable_set( $key, '' );
			}
		}
		foreach ($form_state as $key => $value) {
			afl_variable_set($key, $value);
		}
		wp_set_message('Configuration has been saved successfully', 'success');
	}



/*
 * ----------------------------------------------------------------------------
 * Incentives configurations
 * ----------------------------------------------------------------------------
*/
 function afl_admin_incentives_config () {
 	if (isset($_POST['submit'])) {
 		unset($_POST['submit']);
 		if (afl_admin_incentives_config_validation($_POST)) {
 			afl_admin_incentives_config_submit($_POST);
 		}
 	}

 	$form = array();
	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';

 	$form['rank_holding_consecutive_days'] = array(
 		'#title' 	=> 'Rank holding days',
 		'#type'  	=> 'textfield',
 		'#default_value'=> !empty($post['rank_holding_consecutive_days']) ? $post['rank_holding_consecutive_days'] : afl_variable_get('rank_holding_consecutive_days',''),
 		'#required'=>TRUE
  );
  $form['submit'] = array(
 		'#type' => 'submit',
 		'#value' => 'Save configuration'
 	);
 	echo afl_render_form($form);
 }
/*
 * ----------------------------------------------------------------------------
 * Validation
 * ----------------------------------------------------------------------------
*/
 function afl_admin_incentives_config_validation ($form_state = array()) {
 		$rules[] = array(
	 		'value'=> $form_state['rank_holding_consecutive_days'],
	 		'name' =>'Rank holding consicutive days',
	 		'field' =>'rank_holding_consecutive_days',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric'
	 		)
	 	);
	 	$resp  = set_form_validation_rule($rules);
	 	return $resp;
 }
/*
 * ----------------------------------------------------------------------------
 * Submit
 * ----------------------------------------------------------------------------
*/
 	function afl_admin_incentives_config_submit ($form_state = array()) {
 		foreach ($form_state as $key => $value) {
			afl_variable_set($key, $value);
		}
		wp_set_message('Configuration has been saved successfully', 'success');

 	}

 /*
  * -------------------------------------------------------------------------
  * Extra configurations
  * -------------------------------------------------------------------------
 */
 	function afl_admin_extra_config () {
 		if (isset($_POST['submit'])) {
 			unset($_POST['submit']);
 			if ( afl_admin_extra_config_validation($_POST) ) {
 				afl_admin_extra_config_submit($_POST);
 			}
 		}
 		$form = array();
		$form['#action'] = $_SERVER['REQUEST_URI'];
	 	$form['#method'] = 'post';
	 	$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

/*	 	$form['fieldset'] = array(
	 		'#type'=>'fieldset',
	 		'#title'=>'Cancelled spot openup'
	 	);

	 	$form['fieldset']['cancelled_genealogy_spot_openup_period'] = array(
	 		'#title' 	=> 'Spot openup period',
	 		'#type'  	=> 'select',
	 		'#options'=> array(
	 			'day' 	=> 'Day',
	 			'month'	=> 'Month',
	 			'year'	=> 'Year'
	 		),
	 		'#default_value'=> !empty($post['cancelled_genealogy_spot_openup_period']) ? $post['cancelled_genealogy_spot_openup_period'] : afl_variable_get('cancelled_genealogy_spot_openup_period',''),
	 		'#required'=>TRUE
	  );
	  $form['fieldset']['cancelled_genealogy_spot_openup_period_value'] = array(
	 		'#title' 	=> 'Spot openup period value',
	 		'#type'  	=> 'textfield',
	 		'#default_value'=> !empty($post['cancelled_genealogy_spot_openup_period_value']) ? $post['cancelled_genealogy_spot_openup_period_value'] : afl_variable_get('cancelled_genealogy_spot_openup_period_value',''),
	 		'#required'=>TRUE
	  );
*/


	  //Deactivate member if no distributor package 
	  	$form['fieldset_1'] = array(
	 		'#type'=>'fieldset',
	 		'#title'=>'Deactive Member if No distributor Package'
	 	);

	 	$form['fieldset_1']['deactive_member_if_no_distrib_pack'] = array(
	 		'#title' 	=> 'Enable / Disable Deactivate Member when he doesnt have active distributor package ',
	 		'#type'  	=> 'checkbox',
	 		'#default_value'=> !empty($post['deactive_member_if_no_distrib_pack']) ? $post['deactive_member_if_no_distrib_pack'] : afl_variable_get('deactive_member_if_no_distrib_pack',''),
	  );

   	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' => 'Save configuration'
	 	);
 		echo afl_render_form($form);

 	}
/*
 * -------------------------------------------------------------------------
 * Extra configurations validation
 * -------------------------------------------------------------------------
*/
	function afl_admin_extra_config_validation ($form_state = array()) {
		if(isset($form_state['cancelled_genealogy_spot_openup_period'])) {

			$rules[] = array(
		 		'value'=> $form_state['cancelled_genealogy_spot_openup_period'],
		 		'name' =>'Spot openup period',
		 		'field' =>'cancelled_genealogy_spot_openup_period',
		 		'rules' => array(
		 			'rule_required',
		 		)
		 	);
		}
		if(isset($form_state['cancelled_genealogy_spot_openup_period'])) {

		 	$rules[] = array(
		 		'value'=> $form_state['cancelled_genealogy_spot_openup_period_value'],
		 		'name' =>'Spot openup period value',
		 		'field' =>'cancelled_genealogy_spot_openup_period_value',
		 		'rules' => array(
		 			'rule_required',
		 			'rule_is_numeric'
		 		)
		 	);
		}
		$resp = TRUE;
		if(isset($rules)) {
	 		$resp  = set_form_validation_rule($rules);
		}
	 		return $resp;
	}
/*
 * -------------------------------------------------------------------------
 * Extra configurations validation
 * -------------------------------------------------------------------------
*/
	function afl_admin_extra_config_submit ($form_state = array()) {
		$checkboxes = array();
		$checkboxes[] = 'deactive_member_if_no_distrib_pack';

		foreach ($form_state as $key => $value) {
			afl_variable_set($key, $value);
			
		}
	  foreach ($checkboxes as $checkbox) {
			if ( !array_key_exists($checkbox, $form_state) ) {
				afl_variable_set($checkbox, '');
			}
	 	}

		wp_set_message('Configuration has been saved successfully', 'success');
	}
/*
 * -------------------------------------------------------------------------
 * free account form
 * -------------------------------------------------------------------------
*/
	function afl_admin_free_account_config () {
		$post = [];
		if (isset($_POST['submit'])) {
 			unset($_POST['submit']);
 			$post = $_POST;
 			if ( afl_admin_free_account_config_validation($_POST) ) {
 				afl_admin_free_account_config_submit($_POST);
 			}
 		}
 		$form = array();
		$form['#action'] = $_SERVER['REQUEST_URI'];
	 	$form['#method'] = 'post';
	 	$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

	  $form['free_account_rules_period'] = [
	  	'#type'=>'select',
	  	'#title'=>'Rule checking period',
	  	'#options'=>[
	  		'this_month'=>'This month','previous_month'=> 'previous month'
	  	],
	  	'#default_value' => isset($post['free_account_rules_period']) ? $post['free_account_rules_period'] : afl_variable_get('free_account_rules_period',''),
	  	'#required'=>TRUE,
	  ];
	 	
	  $form['free_account_required_distrib_pv'] = [
	  	'#type'=>'textfield',
	  	'#title'=>'Minimum Required Distributor PV',
	  	'#required'=>TRUE,
	  	'#default_value' => isset($post['free_account_required_distrib_pv']) ? $post['free_account_required_distrib_pv'] : afl_variable_get('free_account_required_distrib_pv',''),
	  ];

	  $form['free_account_minimum_required_refers'] = [
	  	'#type'=>'textfield',
	  	'#title'=>'Minimum Required referals',
	  	'#required'=>TRUE,
	  	'#default_value' => isset($post['free_account_minimum_required_refers']) ? $post['free_account_minimum_required_refers'] : afl_variable_get('free_account_minimum_required_refers',''),
	  ];

	  $form['free_account_minimum_required_refers_combined_pv'] = [
	  	'#type'=>'textfield',
	  	'#title'=>'Minimum Required referals combined PV',
	  	'#required'=>TRUE,
	  	'#default_value' => isset($post['free_account_minimum_required_refers_combined_pv']) ? $post['free_account_minimum_required_refers_combined_pv'] : afl_variable_get('free_account_minimum_required_refers_combined_pv',''),
	  ];

   	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' => 'Save configuration'
	 	);
 		echo afl_render_form($form);
	}

/*
 * -------------------------------------------------------------------------
 * free account form configurations validation
 * -------------------------------------------------------------------------
*/
	function afl_admin_free_account_config_validation ($form_state = array()) {
		if(isset($form_state['free_account_rules_period'])) {

			$rules[] = array(
		 		'value'=> $form_state['free_account_rules_period'],
		 		'name' =>'Rules checking period',
		 		'field' =>'free_account_rules_period',
		 		'rules' => array(
		 			'rule_required',
		 		)
		 	);
		}
		if(isset($form_state['free_account_required_distrib_pv'])) {

		 	$rules[] = array(
		 		'value'=> $form_state['free_account_required_distrib_pv'],
		 		'name' =>'Distributor pv',
		 		'field' =>'free_account_required_distrib_pv',
		 		'rules' => array(
		 			'rule_is_numeric_posative'
		 		)
		 	);
		}
		if(isset($form_state['free_account_minimum_required_refers'])) {

		 	$rules[] = array(
		 		'value'=> $form_state['free_account_minimum_required_refers'],
		 		'name' =>'Distributor referals count',
		 		'field' =>'free_account_minimum_required_refers',
		 		'rules' => array(
		 			'rule_is_numeric_posative'
		 		)
		 	);
		}

		if(isset($form_state['free_account_minimum_required_refers_combined_pv'])) {

		 	$rules[] = array(
		 		'value'=> $form_state['free_account_minimum_required_refers_combined_pv'],
		 		'name' =>'Distributor referals combined PV',
		 		'field' =>'free_account_minimum_required_refers_combined_pv',
		 		'rules' => array(
		 			'rule_is_numeric_posative'
		 		)
		 	);
		}

		$resp = TRUE;
		if(isset($rules)) {
	 		$resp  = set_form_validation_rule($rules);
		}
	 		return $resp;
	}

/*
 * -------------------------------------------------------------------------
 * free account form configurations validation
 * -------------------------------------------------------------------------
*/
	function afl_admin_free_account_config_submit ($form_state = array()) {
		foreach ($form_state as $key => $value) {
			afl_variable_set($key, $value);
			
		}
		wp_set_message('Configuration has been saved successfully', 'success');
	}