<?php 
/*
 * -------------------------------------------------------------------
 * Compensation plan menu callbacks for admin menus
 * -------------------------------------------------------------------
*/
	function afl_admin_rank_configuration() {  
		echo afl_eps_page_header();
		
		echo afl_content_wrapper_begin();
		afl_rank_configuration_form_tabs();
		echo afl_content_wrapper_end();
		
	} 

/*
 * -------------------------------------------------------------------
 * render the tabs and contents in it
 * -------------------------------------------------------------------
*/
	function afl_rank_configuration_form_tabs() {
		new Afl_enque_scripts('common');
		
	 $post = array();
	 /*
	  * ------------------------------------
	  * Validation and submission comes here
	  * ------------------------------------
	 */
		 if (isset($_POST['submit'])) {
		 	
		 	$active_rank = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'basic_configuration';  
		 	if ($active_rank == 'basic_configuration') {
		 		if ( render_basic_configurations_form_validation($_POST)){
		 			render_basic_configurations_form_submit($_POST);
		 		} else {
		 			$post = $_POST;
		 		}
		 	} else {
			 	if (render_rank_form_validate($_POST,$active_rank)){
			 		render_rank_form_submit($_POST,$active_rank);
			 	} else {
			 		$post = $_POST;
			 	}
			}
		 }
	 /*
	  * ------------------------------------
	  * Rank tabs and fields render comes here
	  * ------------------------------------
	 */
		 $max_rank = afl_variable_get('number_of_ranks');
		 if ($max_rank) {
		  
		  $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'basic_configuration';  

		  //here render the tabs
		  echo '<ul class="tabs--primary nav nav-tabs">';

		  echo '<li class="'.(($active_tab == 'basic_configuration') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-rank-configurations&tab=basic_configuration" >Basic configuration</a>  
		          </li>';
		 	for ($i = 1; $i <= $max_rank ; $i++) { 
		 		$active = '';
		 		if ($active_tab == $i ) {
		 			$active = 'active';
		 		}
		 		//<a href="?page=rank-configurations&tab='.$i.'" >'.afl_variable_get('rank_'.$i.'_name','Rank '.$i.'').'</a>  
		 		echo '<li class="'.$active.'">
		            	<a href="?page=affiliate-eps-rank-configurations&tab='.$i.'" >Rank '.$i.'</a>  
		          </li>';
		 	}
		  echo '</ul>';

		  //here render the tab contents
		  for ($i = 1; $i <= $max_rank; $i++) {
		  	if ($active_tab == $i) {
		  		render_rank_form($i, $post);
		  	}
		  }
		  if ($active_tab == 'basic_configuration') {
		  		render_basic_configurations_form();
		  	}
		 }
	}

/*
 * -------------------------------------------------------------------
 * Rank configuration form
 * -------------------------------------------------------------------
*/
	function render_rank_form ($rank = '', $form_state = array()) {
		$form = array();
		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

		$form['field_'.$rank] = array(
		 		'#type' => 'fieldset',
		 		'#title'=>'Rank '.$rank,
		 	);
		$form['field_'.$rank]['rank_'.$rank.'_name'] = array(
			'#type' 				=> 'text',
			'#title'				=> 'Rank Name',
			'#default_value' 	=> isset($form_state['rank_'.$rank.'_name']) ? $form_state['rank_'.$rank.'_name'] : afl_variable_get('rank_'.$rank.'_name'),
			'#required'=>TRUE,
			'#prefix'=>'<div class="form-group row">',
	 		'#suffix' =>'</div>'
		);

		$form['field_'.$rank]['rank_'.$rank.'_pv'] = array(
			'#type' 				=> 'text',
			'#title'				=> 'Personal Volume',
			'#required'=>TRUE,
			'#default_value' 	=> isset($form_state['rank_'.$rank.'_pv']) ? $form_state['rank_'.$rank.'_pv'] : afl_variable_get('rank_'.$rank.'_pv'),
			'#prefix'=>'<div class="form-group row">',
	 		'#suffix' =>'</div>'
		);

	 	$form['field_'.$rank]['rank_'.$rank.'_gv'] = array(
			'#type' 				=> 'text',
			'#title'				=> 'Group Volume',
			'#required'			=> TRUE,
			'#default_value'=> isset($form_state['rank_'.$rank.'_gv']) ? $form_state['rank_'.$rank.'_gv'] : afl_variable_get('rank_'.$rank.'_gv'),
			'#prefix'=>'<div class="form-group row">',
	 		'#suffix' =>'</div>'
		);

	 	$form['field_'.$rank]['rank_'.$rank.'_max_gv_taken_1_leg'] = array(
			'#type' 				=> 'text',
			'#title'				=> 'Maximum Volume taken in 1 Leg',
			'#required'=>TRUE,
			'#default_value' 	=> isset($form_state['rank_'.$rank.'_max_gv_taken_1_leg']) ? $form_state['rank_'.$rank.'_max_gv_taken_1_leg'] : afl_variable_get('rank_'.$rank.'_max_gv_taken_1_leg'),
			'#prefix'=>'<div class="form-group row">',
	 		'#suffix' =>'</div>'
		);

	 	$form['field_'.$rank]['rank_'.$rank.'_customer_rule_from_1_leg'] = array(
			'#type' 				=> 'text',
			'#title'				=> 'Customer Rule for 1 leg',
			'#required'=>TRUE,
			'#default_value' 	=> isset($form_state['rank_'.$rank.'_customer_rule_from_1_leg']) ? $form_state['rank_'.$rank.'_customer_rule_from_1_leg'] : afl_variable_get('rank_'.$rank.'_customer_rule_from_1_leg'),
			'#prefix'=>'<div class="form-group row">',
	 		'#suffix' =>'</div>'
		);

	 	$form['field_'.$rank]['rank_'.$rank.'_no_of_distributors'] = array(
			'#type' 				=> 'text',
			'#title'				=> 'No.of distributors required',
			'#required'=>TRUE,
			'#default_value' 	=> isset($form_state['rank_'.$rank.'_no_of_distributors']) ? $form_state['rank_'.$rank.'_no_of_distributors']:afl_variable_get('rank_'.$rank.'_no_of_distributors'),
			'#prefix'=>'<div class="form-group row">',
	 		'#suffix' =>'</div>'
		);

		$form['field_'.$rank]['rank_'.$rank.'_monthly_income'] = array(
			'#type' 				=> 'text',
			'#title'				=> 'Monthly Income',
			'#required'=>TRUE,
			'#default_value' 	=> isset($form_state['rank_'.$rank.'_monthly_income']) ? $form_state['rank_'.$rank.'_monthly_income']:afl_variable_get('rank_'.$rank.'_monthly_income'),
			'#prefix'=>'<div class="form-group row">',
	 		'#suffix' =>'</div>'
		);

		$form['field_'.$rank]['rank_'.$rank.'_incentives'] = array(
			'#type' 				=> 'textarea',
			'#title'				=> 'Incentives',
			'#default_value' 	=> isset($form_state['rank_'.$rank.'_incentives']) ? $form_state['rank_'.$rank.'_incentives']:afl_variable_get('rank_'.$rank.'_incentives'),
			'#prefix'=>'<div class="form-group row">',
	 		'#suffix' =>'</div>'
		);

		$form['field_'.$rank]['rank_'.$rank.'_color'] = array(
			'#type' 				=> 'text',
			'#title'				=> 'Rank Color',
			'#default_value' 	=> isset($form_state['rank_'.$rank.'_color']) ? $form_state['rank_'.$rank.'_color']:afl_variable_get('rank_'.$rank.'_color'),
			'#prefix'=>'<div class="form-group row">',
	 		'#suffix' =>'</div>'
		);

		$color_hr = afl_variable_get('mlm_hr_color', '#7266ba');
	 	$form['field_'.$rank]['markup_rank_'.$rank] = array(
	    '#type' => 'markup',
	    '#markup' => '<hr style="border:2px solid '.$color_hr.'; color:'.$color_hr.'; margin:60px 0px 60px 0px">',
	  );

	  /* 
	   *----------------------------------------------------------------------------
	   *  Rank Qualification 
	   *----------------------------------------------------------------------------
	   */
		 	
		  $below_rank = $rank - 1;
		  if ($below_rank > 0 ) {
	  	  $form['rank_qualification'] = array(
			 		'#type' => 'fieldset',
			 		'#title'=>'Rank Qualifications',
			 	);

			 	$form['rank_qualification']['markup'] = array(
			 		'#type' => 'markup',
			 		'#markup'=>'<div class="alert alert-info" role="alert"><strong>Info : </strong>If you specify the required count and leave the required in legs blank.The rank user counted from all the legs.</div>',
			 		'#prefix'=>'<div class="form-group row ">',
			 		'#suffix' =>'</div>',
			 	);


	  	  $form['rank_qualification']['rank_label_header'] = array(
			 		'#type' => 'label',
			 		'#title'=>'Rank Name',
			 		'#prefix'=>'<div class="form-group row col-md-2 col-sm-2">',
			 		'#suffix' =>'</div>',
			 	);
	  	  $form['rank_qualification']['rank_label_required_header'] = array(
			 		'#type' => 'label',
			 		'#title'=>'Required Count (In 1 Leg)',
			 		'#prefix'=>'<div class="form-group row col-md-5 col-sm-5">',
			 		'#suffix' =>'</div>',
			 	);
			 	// $form['rank_qualification']['rank_label_required_in_each_leg_header'] = array(
			 	// 	'#type' => 'label',
			 	// 	'#title'=>'Required In each leg',
			 	// 	'#prefix'=>'<div class="form-group row col-md-4 col-sm-4">',
			 	// 	'#suffix' =>'</div>',
			 	// );
			 	$form['rank_qualification']['rank_label_required_within_legs_header'] = array(
			 		'#type' => 'label',
			 		'#title'=>'Required in howmany legs',
			 		'#prefix'=>'<div class="form-group row col-md-5 col-sm-5">',
			 		'#suffix' =>'</div>',
			 	);

		  	for ($i = 1; $i <= $below_rank ; $i++) { 
		  		$form['rank_qualification']['rank_'.$rank.'_rank_'.$i.'_label'] = array(
				 		'#type' => 'label',
				 		'#title'=>afl_variable_get('rank_'.$i.'_name', 'Rank '.$i),
				 		'#prefix'=>'<div class="form-group row col-md-2 col-sm-2">',
				 		'#suffix' =>'</div>',
				 	);
	  			$form['rank_qualification']['rank_'.$rank.'_rank_'.$i.'_required_count'] = array(
				 		'#type' => 'text',
				 		'#title'=>	'',
				 		'#default_value' => isset($form_state['rank_'.$rank.'_rank_'.$i.'_required_count']) ? $form_state['rank_'.$rank.'_rank_'.$i.'_required_count']: afl_variable_get('rank_'.$rank.'_rank_'.$i.'_required_count'),
				 		'#prefix'=>'<div class="form-group row col-md-5 col-sm-5">',
				 		'#suffix' =>'</div>',
				 	);

				 	//require in each leg
				 	// $form['rank_qualification']['rank_'.$rank.'_rank_'.$i.'_required_each_leg'] = array(
				 	// 	'#type' => 'checkbox',
				 	// 	'#title'=>	'',
				 	// 	'#default_value' => isset($form_state['rank_'.$rank.'_rank_'.$i.'_required_each_leg']) ? $form_state['rank_'.$rank.'_rank_'.$i.'_required_each_leg']: afl_variable_get('rank_'.$rank.'_rank_'.$i.'_required_each_leg'),
				 	// 	'#prefix'=>'<div class="form-group row col-md-2 col-sm-2 text-center">',
				 	// 	'#suffix' =>'</div>',
				 	// );

				 	//required in legs
				 	$form['rank_qualification']['rank_'.$rank.'_rank_'.$i.'_required_in_legs'] = array(
				 		'#type' => 'text',
				 		'#title'=>	'',
				 		'#default_value' => isset($form_state['rank_'.$rank.'_rank_'.$i.'_required_in_legs']) ? $form_state['rank_'.$rank.'_rank_'.$i.'_required_in_legs']: afl_variable_get('rank_'.$rank.'_rank_'.$i.'_required_in_legs'),
				 		'#prefix'=>'<div class="form-group row col-md-5 col-sm-5">',
				 		'#suffix' =>'</div>',
				 	);
		  	}
		  	 //required rank
			 	// $form['rank_qualification']['rank_'.$rank.'_required_rank'] = array(
			 	// 	'#type' => 'select',
			 	// 	'#title'=>'Required Rank',
			 	// 	'#options' => afl_get_ranks_names(),
			 	// 	'#default_value' => isset($form_state['rank_'.$rank.'_required_rank']) ? $form_state['rank_'.$rank.'_required_rank']: afl_variable_get($rank.'_required_rank'),
			 	// 	'#prefix'=>'<div class="form-group row">',
			 	// 	'#suffix' =>'</div>'
			 	// );

			 	// //required count
			 	// $form['rank_qualification']['rank_'.$rank.'_required_count'] = array(
			 	// 	'#type' => 'text',
			 	// 	'#title'=>'Required rank count',
			 	// 	'#default_value' => isset($form_state['rank_'.$rank.'_required_count']) ? $form_state['rank_'.$rank.'_required_count']: afl_variable_get('rank_'.$rank.'_required_count'),
			 	// 	'#prefix'=>'<div class="form-group row">',
			 	// 	'#suffix' =>'</div>'
			 	// );
		  }
		 	

	 	$form['rank_qualification']['markup_rank_qualification'] = array(
	    '#type' => 'markup',
	    '#markup' => '<hr style="border:2px solid '.$color_hr.'; color:'.$color_hr.'; margin:60px 0px 60px 0px">',
	    '#prefix'=>'<div class="form-group row col-md-12 col-sm-12 col-lg-12">',
				 		'#suffix' =>'</div>',
	  );
	 	
	 	$form['submit'] = array(
			'#type' 				=> 'submit',
			'#value'				=> 'Save configuration',

		);
	 	echo afl_render_form($form);

	}
/*
 * -----------------------------------------------------------
 * Validate the form fields
 * -----------------------------------------------------------
*/
	function render_rank_form_validate ($form_state = array(), $rank = '') {
		$rules = array();
		//create rules
		$rules[] = array(
	 		'value'=> $_POST['rank_'.$rank.'_name'],
	 		'name' =>'rank name',
	 		'field' =>'rank_'.$rank.'_name',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=>$_POST['rank_'.$rank.'_pv'],
	 		'name' =>'rank personal volume',
	 		'field' =>'rank_'.$rank.'_pv',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric',
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=>$_POST['rank_'.$rank.'_gv'],
	 		'name' =>'rank group volume',
	 		'field' =>'rank_'.$rank.'_gv',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric',
	 		)
	 	);
	 	$rules[] = array(
	 		'value'=>$_POST['rank_'.$rank.'_max_gv_taken_1_leg'],
	 		'name' =>'Maximum group volume taken from 1 leg',
	 		'field' =>'rank_'.$rank.'_max_gv_taken_1_leg',
	 		'rules' => array(
	 			'rule_required',
	 			// 'rule_is_numeric',
	 		)
	 	);
	 	$rules[] = array(
	 		'value'=>$_POST['rank_'.$rank.'_no_of_distributors'],
	 		'name' =>'Number of distributors',
	 		'field' =>'rank_'.$rank.'_no_of_distributors',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric',
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
 * -----------------------------------------------------------
 * form submit
 * -----------------------------------------------------------
*/
	function render_rank_form_submit ($form_state = array(), $rank = '') {
		foreach ($form_state as $key => $value) {
			afl_variable_set($key,maybe_serialize($value));
		}
		echo wp_set_message(__('Configuration has been saved successfully.'), 'success');
	}


/*
 * -----------------------------------------------------------
 * Render basic configurations form
 * -----------------------------------------------------------
*/
 function render_basic_configurations_form () {
	$form['field'] = array(
 		'#type' => 'fieldset',
 		'#title'=>'Basic Configuration',
 	);
	
	$form['field_customer_leg_rule'] = array(
 		'#type' => 'fieldset',
 		'#title'=>'Enable / Disable customer leg rule',
 	);

	$form['field_customer_leg_rule']['enable_rank_customer_rule'] = array(
		'#type' 				=> 'checkbox',
		'#title'				=> '',
		'#default_value' 	=> afl_variable_get('enable_rank_customer_rule'),
		
	);


	$form['field_pool_bonus'] = array(
 		'#type' => 'fieldset',
 		'#title'=>'Enable / Disable pool bonus',
 	);

	$form['field_pool_bonus']['afl_enable_pool_bonus'] = array(
		'#type' 				=> 'checkbox',
		'#title'				=> '',
		'#default_value' 	=> afl_variable_get('afl_enable_pool_bonus'),
		
	);

	$form['field']['rank_expiry'] = array(
		'#type' 				=> 'select',
		'#title'				=> 'Rank Expiry',
		'#options'			=>afl_get_periods(),	
		'#default_value' 	=> afl_variable_get('rank_expiry'),
		
	);
	$form['field']['rank_updation_period'] = array(
		'#type' 				=> 'select',
		'#title'				=> 'Rank Updation period',
		'#options'			=>afl_get_periods(),	
		'#default_value' 	=> afl_variable_get('rank_updation_period'),
		
	);
	$form['submit'] = array(
		'#type' 				=> 'submit',
		'#value'				=> 'Save configuration',

	);
	echo afl_render_form($form);
 }
/*
 * -----------------------------------------------------------
 * BAsic config form validation
 * -----------------------------------------------------------
*/
 function render_basic_configurations_form_validation ($form_state = array()) {
 		$rules = array();
		//create rules
		$rules[] = array(
	 		'value'=> $form_state['rank_expiry'],
	 		'name' =>'Rank expiry ',
	 		'field' =>'rank_expiry',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
		$rules[] = array(
	 		'value'=> $form_state['rank_updation_period'],
	 		'name' =>'Rank updation period',
	 		'field' =>'rank_updation_period',
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
 * -----------------------------------------------------------
 * BAsic config form Submit
 * -----------------------------------------------------------
*/	
	function render_basic_configurations_form_submit ($form_state = array()) {
		
		foreach ($form_state as $key => $value) {
			afl_variable_set($key,maybe_serialize($value));
		}
		//here set unset the values for the checkboxes
		$checkboxes = array(
			'enable_rank_customer_rule',
			'afl_enable_pool_bonus',
		);
		foreach ($checkboxes as $checkbox) {
		if ( !array_key_exists($checkbox, $form_state)) {
			afl_variable_set($checkbox, '');
		}
	}
		echo wp_set_message(__('Configuration has been saved successfully.'), 'success');
	}