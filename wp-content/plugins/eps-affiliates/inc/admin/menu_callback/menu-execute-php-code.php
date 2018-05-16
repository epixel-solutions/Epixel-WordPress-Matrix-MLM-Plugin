<?php 
	function afl_admin_execute_php_code () {
		do_action('eps_affiliate_page_header');
 	do_action('afl_content_wrapper_begin');
		new Afl_enque_scripts('common');
 		afl_admin_execute_php_code_callback();
 	do_action('afl_content_wrapper_end');
	}

	function afl_admin_execute_php_code_callback () {
	  if ( isset($_POST['submit']) ) {
	 		ob_start();
		  print( eval(stripslashes($_POST['execute_php_code'])));
		  // (ob_get_clean());
		  // ob_end_clean();
	  }
	  
		$form = array();
		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';
	 
	 
	 	$form['execute_php_code'] = array(
	 		'#type' => 'textarea',
	 		'#value' =>'Execute Php code',
	 		'#default_value' => !empty($_POST['execute_php_code']) ?stripslashes($_POST['execute_php_code']) : ''
	 	);
	 	$form['#markup'] = [
	 		'#type' =>'markup',
	 		'#markup' =>'Enter some code. Do not use <?php ?> tags.'
	 	];
	 	$form['execute_btn'] = array(
	 		'#type' => 'submit',
	 		'#value' =>'Execute',
	 		'#attributes' => array(
			 			'class' => array(
			 				'btn','btn-primary'
			 			)
			 		),
	 	);
	 	echo afl_render_form($form);
	}