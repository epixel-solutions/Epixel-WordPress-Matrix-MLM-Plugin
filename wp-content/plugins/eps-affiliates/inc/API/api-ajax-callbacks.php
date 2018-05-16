<?php
/*
 * ------------------------------------------------------------
 * Get the users API
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_api_embedd_remote_user_access', 'api_embedd_remote_user_access_callback');
	add_action('wp_ajax_nopriv_api_embedd_remote_user_access', 'api_embedd_remote_user_access_callback');
	function api_embedd_remote_user_access_callback () {
			$startId 	= afl_variable_get('remote_user_get_last_get_id', 1); 
	 		$url 			= afl_variable_get('remote_user_get_url', '').'?startId='.$startId;
	 		

			// $res = file_get_contents($url);
		  $ch 	= curl_init($url);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  $response = curl_exec($ch);
			$count 		= count((array)json_decode($response));
			curl_close($ch);
			if ($response) {
				echo json_encode(array(
					'users' => $response,
					'count'	=> $count
				));
			}
			die();
	} 
/*
 * ------------------------------------------------------------
 * Add user to the system
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_api_embedd_remote_user_to_system', 'api_embedd_remote_user_to_system_callback');
	add_action('wp_ajax_nopriv_api_embedd_remote_user_to_system', 'api_embedd_remote_user_to_system_callback');
	function api_embedd_remote_user_to_system_callback () {
	 	// file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/log1.txt', json_encode($_POST['data']).PHP_EOL,FILE_APPEND);
		$response = 0;

	 	//create new user to the database if not existed
	 	 $_POST = $_POST['data'];
	 	 if ( isset( $_POST['email'] ) ) {
			if ( !email_exists( $_POST['email'] )) {
				if ( isset($_POST['name']) ) {
					if ( !username_exists( $_POST['name'] ) ) {
					/*
	       	 * ------------------------------------------------------------------- 
	       	 * check remote sponsor mlmid user exist
	       	 * Creat user if exists
	       	 * ------------------------------------------------------------------- 
	       	*/
						$sponsor = _check_remote_sponsor_mlmid_exist($_POST['sponsor_mlmid']);
						if ( $sponsor ) {
							$userdata = array(
			        'user_login'    	=>   $_POST['name'],
			        'user_email'    	=>   $_POST['email'],
			        'user_pass'     	=>   '123456',
			        'first_name'    	=>   $_POST['name'],
			       );
			       $user = wp_insert_user( $userdata );
			       
			    /*
	       	 * ------------------------------------------------------------------- 
	       	 * Place the user under sponsor
	       	 * ------------------------------------------------------------------- 
	       	*/
						 if ( $user ) {
			       	do_action('eps_affiliates_place_user_under_sponsor',$user, $sponsor);
		       	/*
		       	 * ------------------------------------------------------------------- 
		       	 * set this user remote_user_mlmid && remote_sponsor_mlmid field
		       	 * ------------------------------------------------------------------- 
		       	*/
							global $wpdb;
			       	$wpdb->update(
									_table_name('afl_user_genealogy'),
									array(
										'remote_user_mlmid' 	 => $_POST['userMlmId'],
										'remote_sponsor_mlmid' => $_POST['sponsor_mlmid']
									),
									array('uid' => $user)
								);
			       	$response = 1;
						 } else {
						 	file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/notinserted.txt', json_encode($_POST).PHP_EOL,FILE_APPEND);
						 }
						} else {
							// file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/sponsor_not_exist.txt', json_encode($_POST).PHP_EOL,FILE_APPEND);

							//these users moves to holding tank
							$userdata = array(
				        'user_login'    	=>   $_POST['name'],
				        'user_email'    	=>   $_POST['email'],
				        'user_pass'     	=>   '123456',
				        'first_name'    	=>   $_POST['name']
				      );
				      $user = wp_insert_user( $userdata );

							do_action('eps_affiliates_place_user_in_holding_tank',$user,afl_root_user());
							//update the holding users remote user mlmid and remote sponsor mlm id

							global $wpdb;
			       	$wpdb->update(
									_table_name('afl_user_holding_tank'),
									array(
										'remote_user_mlmid' 	 => $_POST['userMlmId'],
										'remote_sponsor_mlmid' => $_POST['sponsor_mlmid']
									),
									array('uid' => $user)
								);
						}
					} else {
						file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/user_name_exists.txt', json_encode($_POST).PHP_EOL,FILE_APPEND);
					}
				}
			} else {
				file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/email_exists.txt', json_encode($_POST).PHP_EOL,FILE_APPEND);
			}
	 	 }
	 	
	 	// if ( ! class_exists( 'WP_Userembedd_Process', false ) ) {
	  // 	require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/API/eps-remote-users-background-process.php';
	  // }

	  // $process_all = new WP_Userembedd_Process();
	  // $process_all->push_to_queue( $_POST['data'] );
	  // $process_all->save()->dispatch();
	  // echo "success"; //return something
	 	echo $response;
	  wp_die();
 	}

/*
 * ------------------------------------------------------------
 * upload the user details to queue for processing
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_api_upload_users_to_queue', 'api_upload_users_to_queue_callback');
 add_action('wp_ajax_nopriv_api_upload_users_to_queue', 'api_upload_users_to_queue_callback');
 function api_upload_users_to_queue_callback () {

 	if (!empty($_POST['data'])) {
	 	$data = $_POST['data'];
	 	$uniqu_id   = $_POST['id'];
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
/*
 * ------------------------------------------------------------
 * Check already add the quey for the data unique id
 * ------------------------------------------------------------
*/
 function _check_queue_already_add ($data = array()	) {
 	$query = array();
 	$query['#select'] = _table_name('afl_processing_queue');
 	$query['#where'] = array(

 	);

 	foreach ($data as $key => $value) {
 		$query['#where'][] = ''.$key.'='.$value.'';
 	}
 	$res = db_select($query, 'get_row');
 	if (!empty($res)) {
 		return true;
 	} else {
 		return false;
 	}
 }

 