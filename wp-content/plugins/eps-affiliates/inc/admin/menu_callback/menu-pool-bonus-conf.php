<?php 

/*
 * -------------------------------------------------------------------
 * Compensation plan menu callbacks for Global pool bonus
 * -------------------------------------------------------------------
*/
function afl_admin_pool_bonus_configuration(){
		new Afl_enque_scripts('common');
	
	echo afl_eps_page_header();
	echo afl_content_wrapper_begin();
	
	if ( isset($_POST['submit']) ) {  
	 $validation = afl_admin_pool_bonus_configuration_form_validation($_POST);
	 	if (!empty($validation)) {
	 		afl_admin_pool_bonus_configuration_form_submit($_POST);
	 	}
	}

	$max_rank = afl_variable_get('number_of_ranks');

	$table 								= array();
	$table['#name'] 			= '';
	$table['#title'] 			= '';
	$table['#prefix'] 		= '';
	$table['#suffix'] 		= '';
	$table['#attributes'] = array(
					'class'=> array(
										'table',
										'table-responsive',
										// 'table-bordered'
									)
					);
	$table['#header'] = array(
		'Rank Name', 'Percentage (%)', 'Maximum Monthly Earning ($)'
	);
	
	for ($i=1; $i <=$max_rank; $i++) { 
		$rows[$i]['label_'.$i] = array(
			'#type' => 'label',
			'#title'=> afl_get_rank_names($i,FALSE),
		);
		$rows[$i]['pool_bonus_percentage_rank_'.$i] = array(
			'#type' 				=> 'text',
			'#default_value' 	=> isset($_POST['pool_bonus_percentage_rank_'.$i]) ? $_POST['pool_bonus_percentage_rank_'.$i] :    afl_variable_get('pool_bonus_percentage_rank_'.$i,-100),
	 	);
	 	$rows[$i]['pool_bonus_maximum_amount_rank_'.$i] = array(
			'#type' 				=> 'text',
			'#default_value' 	=> isset($_POST['pool_bonus_maximum_amount_rank_'.$i]) ? $_POST['pool_bonus_maximum_amount_rank_'.$i] :    afl_variable_get('pool_bonus_maximum_amount_rank_'.$i,-100),
	 	);
	}
	$table['#rows'] = $rows;
	$render_table  = '';
	$render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-rank-config'));
	$render_table .= afl_render_table($table);
	$render_table .= afl_input_button('submit', 'Save configuration', '',array('class'=>'btn btn-default btn-primary'));
	$render_table .= afl_form_close();
	echo $render_table;

	echo afl_content_wrapper_end();

}

function afl_admin_pool_bonus_configuration_form_validation($POST){
	global $reg_errors;
	$reg_errors = new WP_Error;
	$flag 			= 1;
	$posative_int = array(
		'pool_bonus_percentage_rank_',
		'pool_bonus_maximum_amount_rank_',
		);
	
	foreach ($POST as $key => $value) {
		if (empty($value)) { 
			$msg = str_replace('_', ' ', $key);
	    $reg_errors->add($key, $msg.'is required.');
		}	
		$posative = preg_replace('/[0-9]+/', '', $key);
		// pr($posative);
		if (in_array($posative, $posative_int)){  
  		if (!is_numeric($value) || $value < 0 ) {  
  			$msg = str_replace('_', ' ', $key);
	    		$reg_errors->add($key, $msg.'	is not correct value.');
			}
  	}
	}
	if ( is_wp_error( $reg_errors ) ) {
    foreach ( $reg_errors->get_error_messages() as $error ) {
				$flag = 0;
    		wp_set_message($error, 'danger');
    }
	}
	return $flag;
}


function afl_admin_pool_bonus_configuration_form_submit($POST){

		foreach ($POST as $key => $value) {
				afl_variable_set($key, maybe_serialize($value));
			}
	wp_set_message(__('Configuration has been saved successfully.'), 'success');
}
