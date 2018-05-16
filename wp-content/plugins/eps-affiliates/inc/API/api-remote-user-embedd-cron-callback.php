<?php
/**
 * ----------------------------------------------------
 * Get the un processed users from the queue
 *
 * 
 *
 *
 * ----------------------------------------------------
*/
 function _process_embedd_users_queue () {
 	global $wpdb;
 	$max_re_process_time = afl_variable_get('queue_max_re_process',5);
	$query = array();
	$query['#select']  = _table_name('afl_processing_queue');
	$query['#where']	=	array(
		'runs <='.$max_re_process_time,
		'name="remote_users_embedd"',
		'status != 1',
		'status != 3'
	);
	$query['#order_by'] = array(
		'runs' => 'ASC'
	);
	$query['#limit'] = 500;
	$queued_data = db_select($query, 'get_results');
	
	foreach ($queued_data as $key => $value) {
		$unique_id = $value->item_id;
		//update the status as processing
		processing_queue_status_update($unique_id,0);

		$data = maybe_unserialize($value->data);
		$processed_times = $value->runs;
		$response  = _api_user_create_in_system( $data, $processed_times );

		if ( $response > 0 ) {
			//update the status as success
			// processing_queue_status_update($unique_id,1);
			afl_log('embedd_remote_user_cron','remote users embedd cron queue processed successfully',array('uid'=> $response ));
			
			// file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/log1.txt', '--------------------------------------------------------------------------'.PHP_EOL,FILE_APPEND);
			// file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/log1.txt', print_r($data, TRUE).PHP_EOL,FILE_APPEND);
			// file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/log1.txt', '--------------------------------------------------------------------------'.PHP_EOL,FILE_APPEND);
			//remove the queue data
			processing_queue_remove($unique_id);
			

		} else {
			//means the user email already exists
			if ( $response == -1 ) {
				//delete the details
				processing_queue_remove($unique_id);
				afl_log('embedd_remote_user_cron','remote users embedd cron queue processed successfully',array('uid'=> $response ));
			
			// file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/log.txt', '--------------------------------------------------------------------------'.PHP_EOL,FILE_APPEND);
			// file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/log.txt', print_r($data, TRUE).PHP_EOL,FILE_APPEND);
			// file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/log.txt', '--------------------------------------------------------------------------'.PHP_EOL,FILE_APPEND);
			//could not create the user
			} else if ( $response == -100) {
				processing_queue_processed_time_set($unique_id);
			} else  {
				//update the status as re-processed
				processing_queue_status_update($unique_id,2);
			}
		}

	 //increment the processing count
		processing_queue_processed_increment($unique_id,($max_re_process_time+1));

	 //update processed date
		$wpdb->update(
			_table_name('afl_processing_queue'),
			array(
				'processed' => afl_date()
			),
			array(
				'item_id' => $unique_id
			)
		);
	}
	//log cron run
 	if ( afl_variable_get('cron_logs_enable')) {
		afl_log('process_embedd_users_scheduler','cron run completed',array(),LOGS_INFO);
 	}
 }
/*
 * -------------------------------------------------------
 * Create the user in the system if the user is not 
 * existed
 * If the sponsor of the user doesnot exist,add these user
 * to the holding tank of root user
 * -------------------------------------------------------
*/
	function _api_user_create_in_system ($data = array(), $processed_times = '') {
			$response = 0;

		 	//create new user to the database if not existed
		 	 if ( isset( $data['email'] ) ) {
				if ( !email_exists( $data['email'] )) {
					if ( isset($data['name']) ) {
						if ( !username_exists( $data['name'] ) ) {
						/*
		       	 * ------------------------------------------------------------------- 
		       	 * check remote sponsor mlmid user exist
		       	 * Creat user if exists
		       	 * ------------------------------------------------------------------- 
		       	*/
							$sponsor 	= _check_remote_mlmid_exist($data['sponsor_mlmid']);
							// $sponsor 	= afl_root_user();
							$response = _api_insert_user_( $sponsor, $data, $processed_times );
						} else {
							// afl_log('cron','remote users embedd cron queue failed.User name already exists',array('queue_data'=>$data),LOGS_ERROR);
							/*
							 * ------------------------------------------------------------
							 * The user name already exists
							 * 
							 * add an integer value with the user name
							 * Add 
							 * ------------------------------------------------------------
							*/

								$sponsor 	= _check_remote_mlmid_exist($data['sponsor_mlmid']);
								// $sponsor 	= afl_root_user();
								$begin 		= _api_get_last_inserted($data['name']); 
								$data['name'] = $data['name'].'-'.$begin;
								$response = _api_insert_user_( $sponsor, $data, $processed_times );
						}
					}
				} else {
					afl_log('embedd_remote_user_cron','remote users embedd cron queue failed.User email already exists',array('queue_data'=>$data),LOGS_ERROR);
					$response = -1;
				}
		 	 }
		 	return $response;
	}
/**
 * ------------------------------------------------------------------
 * Check the sponsor mlm id exist and return the user id
 *
 * @var $sponsor_mlmid : remote sponsor mlm id
 *
 * @return integer user id having the remote_uer_mlmid $sponsor_mlmid
 *				 else return 0	
 * ------------------------------------------------------------------
*/
 	function _check_remote_mlmid_exist ( $sponsor_mlmid = '') {

	 	if ( $sponsor_mlmid ) {
	 		$query['#select'] = _table_name('afl_user_genealogy');
	 		$query['#where']  = array(
	 			'remote_user_mlmid = '.$sponsor_mlmid
	 		);
	 		$result = db_select($query, 'get_row');
	 		if ( $result ) {
	 			return $result->uid;
	 		} else {
	 			return 0;
	 		}
	 	} else {
	 		return 0;
	 	}
	}
/*
 * ------------------------------------------------------------------
 * User adds to genealogy
 * ------------------------------------------------------------------
*/
 function _api_user_add_to_genealogy($user = '', $sponsor = '', $data =  array()	) {
 		$status = (!empty($data['status']) && $data['status'] == 'Active') ? 1 : 0;
   	do_action('eps_affiliates_place_user_under_sponsor',$user, $sponsor);
   	do_action('eps_affiliates_unilevel_place_user_under_sponsor',$user, $sponsor);
 	/*
 	 * ------------------------------------------------------------------- 
 	 * set this user remote_user_mlmid && remote_sponsor_mlmid field
 	 * ------------------------------------------------------------------- 
 	*/
		global $wpdb;
   	$wpdb->update(
				_table_name('afl_user_genealogy'),
				array(
					'remote_user_mlmid' 	 => $data['userMlmId'],
					'remote_sponsor_mlmid' => $data['sponsor_mlmid'],
					'status' 							 => $status
				),
				array('uid' => $user)
			);
   	$wpdb->update(
				_table_name('afl_unilevel_user_genealogy'),
				array(
					'remote_user_mlmid' 	 => $data['userMlmId'],
					'remote_sponsor_mlmid' => $data['sponsor_mlmid'],
					'status' 							 => $status
				),
				array('uid' => $user)
			);
 }
/*
 * ------------------------------------------------------------------
 * User adds to Holding data
 * ------------------------------------------------------------------
*/
 function _api_user_add_to_holding_tank($user = '', $sponsor = '', $data =  array()	) {
 		$status = (!empty($data['status']) && $data['status'] == 'Active') ? 1 : 0;
		do_action('eps_affiliates_place_user_in_holding_tank',$user,afl_root_user());
		do_action('eps_affiliates_unilevel_place_user_in_holding_tank',$user,afl_root_user());
		//update the holding users remote user mlmid and remote sponsor mlm id

		global $wpdb;
   	$wpdb->update(
				_table_name('afl_user_holding_tank'),
				array(
					'remote_user_mlmid' 	 => $data['userMlmId'],
					'remote_sponsor_mlmid' => $data['sponsor_mlmid'],
					'status' 							 => $status
				),
				array('uid' => $user)
			);
   	$wpdb->update(
				_table_name('afl_unilevel_user_holding_tank'),
				array(
					'remote_user_mlmid' 	 => $data['userMlmId'],
					'remote_sponsor_mlmid' => $data['sponsor_mlmid'],
					'status' 							 => $status
				),
				array('uid' => $user)
			);
 }
/*
 * --------------------------------------------------------------------
 * Add the user to the system
 * Either genealogy or Holding tank
 * --------------------------------------------------------------------
*/
  function _api_insert_user_($sponsor='', $data = array(), $processed_times = '') {
  	$response = 0;
  	
  	if ( $sponsor ) {
			$userdata = array(
      'user_login'    	=>   $data['name'],
      'user_email'    	=>   $data['email'],
      'user_pass'     	=>   '123456',
      'first_name'    	=>   $data['name'],
     );
     $user = wp_insert_user( $userdata );
			

  /*
 	 * ------------------------------------------------------------------- 
 	 * Place the user under sponsor
 	 * ------------------------------------------------------------------- 
 	*/
		 if ( $user && is_numeric( $user )) {
		 	
		 	_api_user_add_to_genealogy( $user, $sponsor, $data );
		 	// _api_user_add_to_holding_tank( $user, $sponsor, $data );
		 	
     	$response = $user;

		 } else {
		 	afl_log('embedd_remote_user_cron','remote users embedd cron queue failed.Cannot create the user',array('queue_data'=>$data,),LOGS_ERROR);
		 	$response = -100;
		 }
		} else {
		/*
		 * ------------------------------------------------------
		 * The user adds to holding tank only when the processed 
		 * times is maximum processing time
		 * ------------------------------------------------------
		*/
		  if ( $processed_times == afl_variable_get('queue_max_re_process',5) ) {
		  	//these users moves to holding tank
				$userdata = array(
	        'user_login'    	=>   $data['name'],
	        'user_email'    	=>   $data['email'],
	        'user_pass'     	=>   '123456',
	        'first_name'    	=>   $data['name']
	      );
	      
	      $user = wp_insert_user( $userdata );
	      if ( $user && is_numeric( $user )){
	      _api_user_add_to_holding_tank( $user, afl_root_user(), $data);
        	$response = $user;
	      }
 

		  }
		}
		return $response;
  }
/*
 * --------------------------------------------------------------------
 * get the last inserted integer number with the user name
 * --------------------------------------------------------------------
*/
	function _api_get_last_inserted($string_prefix = '') {
		$query = array();
		$query['#select'] = _table_name('users');
		$query['#fields'] = array(
			 _table_name('users') => array(
				 'user_login'
			 )
		);
		$query['#order_by'] = array(
			'ID' => 'ASC'
		);
		// $query['#like']   = array(
		// 	'`'._table_name('users').'`.`user_login`' => $string_prefix.'-'
		// );
		$query['#reg_exp'] = array(
			'`'._table_name('users').'`.`user_login`' => $string_prefix.'-[0-9]+$'
		);
		$existed_users = db_select($query, 'get_results');
		
		end($existed_users);         // move the internal pointer to the end of the array
		$key = key($existed_users);
		$begin = 0;
		if (isset($existed_users[$key])) {
			$begin = $existed_users[$key]->user_login;
			$integer = explode('-',$begin);
			end($integer);
			$key = key($integer);
			$begin = $integer[$key];

		}
		return ($begin + 1);
	}