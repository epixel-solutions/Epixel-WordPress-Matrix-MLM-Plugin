<?php

function afl_system_genealogy_configurations () {
	echo afl_eps_page_header();
		new Afl_enque_scripts('common');


	$post = array();
	if (isset($_POST['submit']) ){
		$rules = array();
		//create rules
		$rules[] = array(
	 		'value'=>$_POST['root_user'],
	 		'name' =>'root user',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	 	//validating fields with rules
	 	$resp  = set_form_validation_rule($rules);
	 	if ($resp) {
	 		//set the root user and reset genealogy
	 		afl_genealogy_configuration_form_submit($_POST);
	 		echo wp_set_message('Configuration has been saved successfully', 'success');
	 	}
	}

	afl_genealogy_configuration_form($post);
}

/*
 * --------------------------------------------------------
 * Genealogy configurations form callback
 * --------------------------------------------------------
*/
function afl_genealogy_configuration_form ($post) {
	afl_content_wrapper_begin();
	$form = array();
	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';

 	$form['root_user'] = array(
 		'#title' 	=> 'Root user',
 		'#type'  	=> 'auto_complete',
 		'#name'		=> 'root-user',
 		'#auto_complete_path' => 'users_auto_complete',
 		'#default_value' => isset($post['root_user']) ? $post['root_user'] : (!empty(afl_variable_get('root_user')) ? afl_variable_get('root_user') : '') ,

 	);



	$form['root_user_remoteMlmId'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Root User Remote MLM Id',
	 		'#default_value' 	=> afl_variable_get('root_user_remoteMlmId',''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);

	$form['reset_root_user_rank'] = array(
	 		'#type' 					=> 'checkbox',
	 		'#title' 					=> 'Reset root user Rank',
	 		'#default_value' 	=> afl_variable_get('reset_root_user_rank',''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
 	$form['clear_data'] = array(
 		'#title' 	=> 'Clear test data',
 		'#type'  	=> 'checkbox',
 		'#name'		=> 'clear-test-data',
 		'#default_value' => isset($post['clear_data']) ? $post['clear_data'] : '',

 	);

 	$form['reset_index'] = array(
 		'#title' 	=> 'Reset Index',
 		'#type'  	=> 'checkbox',
 		'#name'		=> 'reset-index',
 		'#default_value' => isset($post['reset_index']) ? $post['reset_index'] : '',

 	);

 	$form['remove_user'] = array(
 		'#title' 	=> 'Remove system user',
 		'#type'  	=> 'checkbox',
 		'#name'		=> 'remove_system_user',
 		'#default_value' => isset($post['remove_user']) ? $post['remove_user'] : '',

 	);

 	$form['remove_customer'] = array(
 		'#title' 	=> 'Remove system customers',
 		'#type'  	=> 'checkbox',
 		'#name'		=> 'remove_customer',
 		'#default_value' => isset($post['remove_customer']) ? $post['remove_customer'] : '',

 	);
 	$form['submit'] = array(
 		'#title' => 'Submit',
 		'#type' => 'submit',
 		'#value' => 'Submit',
 		'#attributes' => array(
 			'class' => array(
 				'btn','btn-primary'
 			)
 		),

 	);
 	echo afl_render_form($form);

	afl_content_wrapper_end();
}
/*
 * --------------------------------------------------------
 * Genealogy configurations form submit callback
 * --------------------------------------------------------
*/
function afl_genealogy_configuration_form_submit ($form_state){
	if (!empty($form_state['root_user']) ){
		afl_variable_set('root_user', $form_state['root_user']);
	}
	if ( !empty($form_state['reset_root_user_rank']) ) {
		_reset_admin_rank();
	}

	if (isset($form_state['clear_data']) ){
		afl_system_reset();
	}

	if ( isset($form_state['remove_user'])) {
		afl_remove_users();
	}

	if ( isset($form_state['remove_customer'])) {
		afl_remove_customers();
	}

	if(isset($form_state['root_user_remoteMlmId'])) {
		afl_variable_set('root_user_remoteMlmId', $form_state['root_user_remoteMlmId']);
		afl_set_root_mlmid($form_state['root_user_remoteMlmId']);
	}

	if (isset($form_state['reset_index'])) {
		_reset_indexes();
	}
	echo wp_set_message('Genealogy reset', 'success');
}

function afl_set_root_mlmid($mlmid) {
	global $wpdb;
		$root_user = afl_root_user();
		$wpdb->update(
						_table_name('afl_user_genealogy'),
						array(
							'remote_user_mlmid' => $mlmid,
						),
						array( 'uid' => $root_user )
					);

		$wpdb->update(
						_table_name('afl_unilevel_user_genealogy'),
						array(
							'remote_user_mlmid' => $mlmid,
						),
						array( 'uid' => $root_user )
					);
}
/*
 * --------------------------------------------------------
 * Reset Genealogy
 * --------------------------------------------------------
*/
	function afl_system_reset ($remove_user = '') {
		global $wpdb;
		$wpdb->query("DELETE FROM `"._table_name('afl_user_genealogy')."` WHERE `uid` != ".afl_root_user()." ");
		$wpdb->query("DELETE FROM `"._table_name('afl_unilevel_user_genealogy')."` WHERE `uid` != ".afl_root_user()." ");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_business_funds')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_business_transactions')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_business_transactions_overview')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_payout_history')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_payout_requests')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_ranks')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_rank_history')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_transactions')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_transactions_errors')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_transaction_authorization')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_user_downlines')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_unilevel_user_downlines')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_user_fund')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_user_holding_tank')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_unilevel_user_holding_tank')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_user_payment_methods')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_user_transactions')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_user_transactions_overview')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_purchases')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_tree_last_insertion_positions')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_unilevel_tree_last_insertion_positions')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_customer')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_processing_queue')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_log_messages')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_user_holding_transactions')."`");

		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_user_exort_data')."`");
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_bonus_incentive_history')."`");

		$wpdb->query("DELETE FROM `"._table_name('afl_nested_set_referal')."` WHERE `node_id` != ".afl_root_user()." ");
		$wpdb->query("DELETE FROM `"._table_name('afl_nested_set_downline')."` WHERE `node_id` != ".afl_root_user()." ");


		//update the nested sets
		$query['#select'] = _table_name('afl_nested_set_referal');
	  $query['#where'] = [
	  	'node_id='.afl_root_user()
	  ];
	  $root_data = db_select($query, 'get_row');
	  if (empty( $root_data ) ){
	  	global $wpdb;
	  	$ins_data = [];
	    $ins_data['lft'] = 1;
	    $ins_data['rgt'] = 2;
	    $ins_data['parent_uid'] = 0;
	    $ins_data['node_id'] = afl_root_user();

			$ins_id = $wpdb->insert(_table_name('afl_nested_set_referal'), $ins_data);
	  } else {
	  	$update_query['#table'] = _table_name('afl_nested_set_referal');
			$update_query['#fields'] = [
				'lft' => 1,
				'rgt' => 2,
			];
			db_update($update_query);
	  }

	  $query['#select'] = _table_name('afl_unilevel_nested_set_referal');
	  $query['#where'] = [
	  	'node_id='.afl_root_user()
	  ];
	  $root_data = db_select($query, 'get_row');
	  if (empty( $root_data ) ){
	  	global $wpdb;
	  	$ins_data = [];
	    $ins_data['lft'] = 1;
	    $ins_data['rgt'] = 2;
	    $ins_data['parent_uid'] = 0;
	    $ins_data['node_id'] = afl_root_user();

			$ins_id = $wpdb->insert(_table_name('afl_unilevel_nested_set_referal'), $ins_data);
	  } else {
	  	$update_query['#table'] = _table_name('afl_unilevel_nested_set_referal');
			$update_query['#fields'] = [
				'lft' => 1,
				'rgt' => 2,
			];
			db_update($update_query);
	  }



	  $query['#select'] = _table_name('afl_nested_set_downline');
	  $query['#where'] = [
	  	'node_id='.afl_root_user()
	  ];
	  $root_data = db_select($query, 'get_row');
	  if (empty( $root_data ) ){
	  	global $wpdb;
	  	$ins_data = [];
	    $ins_data['lft'] = 1;
	    $ins_data['rgt'] = 2;
	    $ins_data['parent_uid'] = 0;
	    $ins_data['node_id'] = afl_root_user();

			$ins_id = $wpdb->insert(_table_name('afl_nested_set_downline'), $ins_data);
	  } else {
	  	$update_query['#table'] = _table_name('afl_nested_set_downline');
			$update_query['#fields'] = [
				'lft' => 1,
				'rgt' => 2,
			];
			db_update($update_query);
	  }
	}
/*
 * ------------------------------------------------------------------------
 * Remove system affiliates members
 * ------------------------------------------------------------------------
*/

	function afl_remove_users () {
		//change all the users has afl_member role
		 /*$args = array(
	      'role' => 'afl_member',
	    );*/

	    $args = array(
	      'role__in' => array(
	      	'afl_member',
	      	'holding_member'
	      	),
	       'number' => 500,
	    );
	   $users = get_users($args);
	   //remove all the role
	   foreach ($users as $key => $value) {
	   	if ($value->ID != afl_root_user())
	   		wp_delete_user($value->ID);
	   }
	}

/*
 * ------------------------------------------------------------------------
 * Remove system affiliates customers
 * ------------------------------------------------------------------------
*/

	function afl_remove_customers () {
		//change all the users has afl_member role
		 $args = array(
	      'role' => 'afl_customer',
	    );
	   $users = get_users($args);
	   //remove all the role
	   foreach ($users as $key => $value) {
	   	if ($value->ID != afl_root_user())
	   		wp_delete_user($value->ID);
	   }
	}
/*
 * ------------------------------------------------------------------------
 * Reset the admin rank
 * ------------------------------------------------------------------------
*/
	function _reset_admin_rank ( $rank = 0) {
		global $wpdb;
		$root_user = afl_root_user();
		$wpdb->update(
			_table_name('afl_user_genealogy'),
			array(
				'member_rank' => $rank,
			),
			array( 'uid' => $root_user )
		);

		$wpdb->update(
			_table_name('afl_unilevel_user_genealogy'),
			array(
				'member_rank' => $rank,
			),
			array( 'uid' => $root_user )
		);
	}
/*
 * ------------------------------------------------------------------
 * Reset Indexes
 * ------------------------------------------------------------------
*/
	function _reset_indexes () {
		global $wpdb;
		$wpdb->query( 'ALTER TABLE `'._table_name('afl_user_genealogy').'` AUTO_INCREMENT = 2;' );
		$wpdb->query( 'ALTER TABLE `'._table_name('afl_unilevel_user_genealogy').'` AUTO_INCREMENT = 2;' );
	}