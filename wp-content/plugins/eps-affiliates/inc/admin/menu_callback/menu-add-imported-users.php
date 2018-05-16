<?php

function afl_import_users () {
 	do_action('eps_affiliate_page_header');
 	do_action('afl_content_wrapper_begin');
 	afl_admin_add_exported_users();
 	do_action('afl_content_wrapper_end');
 }

/*
 * --------------------------------------------------------------------------------------
 * Get the Exported users 
 * --------------------------------------------------------------------------------------
*/
 	
function afl_admin_add_exported_users() {
	new Afl_enque_scripts('common');
	// new Afl_enque_scripts('import-user-batch');
	if ( isset($_POST['submit']) ) {
	 		afl_admin_add_exported_users_submit($_POST);
	 }

	$form = array();
	$form['#method'] = 'post';
	$form['#action'] = $_SERVER['REQUEST_URI'];
	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';

 	/*$query['#select'] = _table_name('afl_user_exort_data');

	$result = db_select($query, 'get_results');

	$count_users = count($result);*/

 
 
 	$form['import_users'] = array(
 		'#type' => 'submit',
 		'#value' =>'Import Users',
 		'#attributes' => array(
		 			'class' => array(
		 				'btn','btn-primary'/*,'import-user-batch'*/
		 			)
		 		),
 		/*'#prefix' => '<div class="form-label">Count of importing users : '.$count_users,
 		'#suffix' => '</div>',*/
 	);

 	/*	$form['markup'] = array(
		 		'#type' => 'markup',
		 		'#markup' => '<div id="progress"></div><div id="message"></div>'
		 	);*/
 	echo afl_render_form($form);

}

/* 
 * ----------------------------------------------------------------------------
 * Form Submit action
 * ----------------------------------------------------------------------------
*/
function afl_admin_add_exported_users_submit($POST){
	

	/**
	 * 
	 * Get Users form temporory table
	 * 
	 */

	$query['#select'] = _table_name('afl_user_exort_data');

	$result = db_select($query, 'get_results');
	// pr(count($result),1);
	// pr($result[0],1);
	foreach ($result as $key => $value) {


		$data['userDbId'] = $value->userDbId;
    $data['userMlmId'] = $value->userMlmId;
    $data['name'] = $value->name; 
    $data['email'] = $value->email;
    $data['phone_number'] = $value->phone_number;
    $data['status'] = $value->status;
    $data['auth_sub_date'] = array (
									            'date' => $value->auth_sub_date__date,
									            'timezone_type' => $value->auth_sub_date__timezone_type,
									            'timezone' => $value->auth_sub_date__timezone,
									        		);

    $data['auth_merchant_number'] = $value->auth_merchant_number;
    $data['sponsor_name'] = $value->sponsor_name; 
    $data['sponsor_mlmid'] = $value->sponsor_mlmid;

    $uid   = $data['userDbId'];
		$exists = _check_queue_already_add(array('uid'=> $uid,'name'=>'"remote_users_embedd"'));

		if (!$exists) {
		 		$queue_data = array();
			 	$queue_data['name'] 			= 'remote_users_embedd';
			 	$queue_data['uid'] 				= $uid;
			 	$queue_data['title'] 			= 'remote_users_embedd';
			 	$queue_data['data']  			= maybe_serialize($data);
			 	$queue_data['expire'] 		= afl_date();
			 	$queue_data['status'] 		= -1;
			 	$queue_data['created'] 		= afl_date();
			 	$queue_data['processed'] 	= afl_date();
			 	$queue_data['runs'] 			= 0;
			 	
			 	global $wpdb;
			 	$insid = $wpdb->insert(
			 		_table_name('afl_processing_queue'),
			 		$queue_data
			 	);
			 	if ( $insid ) {
			 		if ( !empty( $data['userDbId'] ) ){
				 		afl_variable_set('remote_user_get_last_get_id', $data['userDbId']);
				 		$wpdb->delete(_table_name('afl_user_exort_data'), array('userDbId' => $data['userDbId']));
			 		}
				 	// echo 'success';
			 	} else {
			 		echo 'Queue Insertion Error';
			 	}

		 	}


		// var_dump($exists);
    // pr($data);
		// pr($value,1);
	}


	echo wp_set_message(__('Importing successfully completed.'), 'success');

	}


	




	add_action('wp_ajax_api_get_imported_users', 'api_get_imported_users_callback');

function api_get_imported_users_callback () {
	$query['#select'] = _table_name('afl_user_exort_data');

	$result = db_select($query, 'get_results');

	foreach ($result as $key => $value) {

		$data[$key]['userDbId'] = $value->userDbId;
    $data[$key]['userMlmId'] = $value->userMlmId;
    $data[$key]['name'] = $value->name; 
    $data[$key]['email'] = $value->email;
    $data[$key]['phone_number'] = $value->phone_number;
    $data[$key]['status'] = $value->status;
    $data[$key]['auth_sub_date'] = array (
									            'date' => $value->auth_sub_date__date,
									            'timezone_type' => $value->auth_sub_date__timezone_type,
									            'timezone' => $value->auth_sub_date__timezone,
									        		);

    $data[$key]['auth_merchant_number'] = $value->auth_merchant_number;
    $data[$key]['sponsor_name'] = $value->sponsor_name; 
    $data[$key]['sponsor_mlmid'] = $value->sponsor_mlmid;


	}
// pr($data);
	if ($result) {
		echo json_encode(array('users'=>$data,'count' => count($result)));
	}
	die();
	} 



	add_action('wp_ajax_api_upload_imported_users_to_queue', 'api_upload_imported_users_to_queue_callback');


	function api_upload_imported_users_to_queue_callback () {

 	if (!empty($_POST['data'])) {
	 	$data = $_POST['data'];
	 	$uniqu_id   = $_POST['data']['userDbId'];
	 	$exists = _check_queue_already_add(array('uid'=> $uniqu_id,'name'=>'"remote_users_embedd"'));
	 	if (!$exists) {
	 		$queue_data = array();
		 	$queue_data['name'] 			= 'remote_users_embedd';
		 	$queue_data['uid'] 				= $uniqu_id;
		 	$queue_data['title'] 			= 'remote_users_embedd';
		 	$queue_data['data']  			= maybe_serialize($data);
		 	$queue_data['expire'] 		= afl_date();
		 	$queue_data['status'] 		= -1;
		 	$queue_data['created'] 		= afl_date();
		 	$queue_data['processed'] 	= afl_date();
		 	$queue_data['runs'] 			= 0;
		 	
		 	global $wpdb;
		 	$insid = $wpdb->insert(
		 		_table_name('afl_processing_queue'),
		 		$queue_data
		 	);
		 	if ( $insid ) {
		 		if ( !empty( $data['userDbId'] ) ){
			 		afl_variable_set('remote_user_get_last_get_id', $data['userDbId']);
		 		}
			 	echo 'success';
		 	} else {
		 		echo 'Queue Insertion Error';
		 	}

		 	die();
	 	}
	 	
 	}
 }