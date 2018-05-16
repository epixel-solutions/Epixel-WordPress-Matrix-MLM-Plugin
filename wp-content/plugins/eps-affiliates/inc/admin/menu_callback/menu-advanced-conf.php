<?php 
/*
 * -------------------------------------------------------------------
 * All the menu callbacks for admin menus
 * -------------------------------------------------------------------
*/
function afl_admin_advanced_configuration() {
	echo afl_eps_page_header();
	afl_admin_advanced_config_();
}
/*
 * -------------------------------------------------------------------
 * Admin advanced configuration form
 * -------------------------------------------------------------------
*/
function afl_admin_advanced_config_(){
	// new Afl_enque_scripts('test');
	new Afl_enque_scripts('common');
	
	 if ( isset($_POST['submit']) ) {
	 	$validation = afl_admin_advanced_configuration_form_validation($_POST);
	 	if (!empty($validation)) {
	 		afl_admin_advanced_configuration_form_submit($_POST);
	 	}
	 }

 	afl_content_wrapper_begin();

	$table 								= array();
	$table['#name'] 			= '';
	$table['#title'] 			= '';
	$table['#prefix'] 		= '';
	$table['#suffix'] 		= '';
	$table['#attributes'] = array(
					'class' => array(
							'table',
							'table-bordered',
							'table-responsive'
						)
					);

	$table['#header'] 		= array('Features','Settings');
	
	/* -------------- ROWS -----------------*/
	$rows[0]['label_1'] = array(
		'#type' => 'label',
		'#title'=> 'Enable Test Mode',
	);
	$rows[0]['var[afl_enable_test_mode]'] = array(
		'#type' 					=> 'checkbox',
		'#attributes'			=> array('switch'=>'switch'),
		'#default_value' 	=> isset($_POST['var']['afl_enable_test_mode']) ? TRUE : afl_variable_get('afl_enable_test_mode',''),
 	);
	$rows[1]['label_2'] = array(
		'#type' => 'label',
		'#title'=> 'Override system date with test date',
 	);
	$rows[1]['var[afl_enable_test_date]'] = array(
		'#type' 					=> 'checkbox',
		'#attributes'			=> array('switch'=>'switch'),
		'#default_value' 	=> isset($_POST['var']['afl_enable_test_date']) ? TRUE : afl_variable_get('afl_enable_test_date',''),
 	);
	$rows[2]['label_3'] = array(
		'#type' => 'label',
		'#title'=> 'Override Testing date',
 	);
	$rows[2]['var[afl_testing_date_date]'] = array(
		'#type' 				=> 'date_time',
		'#preffix' 			=> '<div class="form-item clearfix form-type-textfield form-group" data-toggle="tooltip">',
		'#suffix' 			=> '</div>',
		'#attributes'		=> array(
						'class' => array(
								'form-text',
								'form-control',
						)
		),
		'#default_value' 	=> isset($_POST['var']['afl_testing_date_date']) ? $_POST['var']['afl_testing_date_date'] :afl_variable_get('afl_testing_date_date',''),
 	);
	$rows[3]['label_3'] = array(
		'#type' => 'label',
		'#title'=> 'Enable Demo Mode',
 	);
	$rows[3]['var[afl_enable_demo_mode]'] = array(
		'#type' 			=> 'checkbox',
		'#attributes'			=> array('switch'=>'switch'),
		'#default_value' 	=> isset($_POST['var']['afl_enable_demo_mode']) ? TRUE : afl_variable_get('afl_enable_demo_mode',''),
 	);
	$rows[4]['label_4'] = array(
		'#type' => 'label',
		'#title'=> 'Enable Live Mode',
 	);
	$rows[4]['var[afl_enable_live_mode]'] = array(
		'#type' 			=> 'checkbox',
		'#attributes'			=> array('switch'=>'switch'),
		'#default_value' 	=> isset($_POST['var']['afl_enable_demo_mode']) ? TRUE : afl_variable_get('afl_enable_live_mode',''),
 	);
	
	/* -------------- ROWS ----------------- */

	$table['#rows'] = $rows;


	$render_table  = '';
	$render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-afl-advcd-config'));
	$render_table .= afl_render_table($table);
	$render_table .= afl_input_button('submit', 'Save configuration', '',array('class'=>'btn btn-default btn-primary'));

	$render_table .= afl_form_close();

	echo $render_table;

 	afl_content_wrapper_end();

}

/*
 * -------------------------------------------------------------------
 * Advanced System form validations
 * -------------------------------------------------------------------
*/
function afl_admin_advanced_configuration_form_validation($POST){
	global $reg_errors;
	$reg_errors = new WP_Error;
	$flag 			= 1;
	$values = $POST['var'];
	if ( empty($values['afl_testing_date_date'] ) && $values['afl_enable_test_mode'] == TRUE) {
	    $reg_errors->add('Testing_date', 'Testing date required.');
	}
	if ( is_wp_error( $reg_errors ) ) {
    foreach ( $reg_errors->get_error_messages() as $error ) {
				$flag = 0;
    		wp_set_message($error, 'danger');
    }
	}
	return $flag;
}
/* 
 * ----------------------------------------------------------------------------
 * Form Submit action
 * ----------------------------------------------------------------------------
*/
function afl_admin_advanced_configuration_form_submit($POST){
		$val 			 = $POST['var'];

		$variables = array(
			'afl_enable_test_mode',
			'afl_enable_test_date',
			'afl_testing_date_date',
			'afl_enable_demo_mode',
			'afl_enable_live_mode'
		);
		foreach ($variables as $var) {
			if (array_key_exists($var,$val) && !empty($val[$var])) {
				afl_variable_set($var, maybe_serialize($val[$var]));
			} else {
				afl_variable_set($var,'');
			}
		}
	wp_set_message(__('Configuration has been saved successfully.'), 'success');
}