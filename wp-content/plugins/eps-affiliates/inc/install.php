<?php
/*
 * ------------------------------------------------------------
 * Install functions and Features 
 * ------------------------------------------------------------
*/
function eps_affiliates_install() {

	//install tables
	$afl_tables = new Eps_affiliates_tables;

	//create basic roles 
	create_eps_roles();

	//create business user
	create_business_users();

	//add all the capabilities to admin
	// Create affiliate caps
	$roles = new Eps_affiliates_Capabilities;
	$roles->add_caps();



	//create page 
	// if ( ! get_option( 'eps_afl_is_installed' ) ) {
	// 	$affiliate_area = wp_insert_post(
	// 		array(
	// 			'post_title'     => __( 'EPS-Affiliates', 'eps-affiliates' ),
	// 			'post_content'   => '[eps_affiliates]',
	// 			'post_status'    => 'publish',
	// 			'post_author'    => get_current_user_id(),
	// 			'post_type'      => 'page',
	// 			'comment_status' => 'closed'
	// 		)
	// 	);
	// //set the variable for page id
	// update_option( 'eps_affiliate_page', $affiliate_area );
	// }
	//set the variable for install
	update_option( 'eps_afl_is_installed', 1 );

	$Eps_affiliate_install  = new stdClass();
}
/*
 * ------------------------------------------------------------
 * Check the plugin installed or not
 * ------------------------------------------------------------
*/
	function eps_affiliate_check_if_installed() {
		// this is mainly for network activated installs
		if( !get_option( 'eps_afl_is_installed' ) ) {
			eps_affiliates_install();
		}
	}

/*
 * ------------------------------------------------------------
 * Create basic eps roles
 * ------------------------------------------------------------
*/
 	function create_eps_roles() {
 		if (!role_exists('afl_member')) {
		   	add_role( 'afl_member', 
	  					'AFL Member', 
	  					array( 'read' => true, 'level_0' => true,'level_1' => true,'eps_system_member'=>true) 
	  		);	
 		}
 		if (!role_exists('afl_customer')) {
		   	add_role( 'afl_customer', 
	  					'AFL Customer', 
	  					array( 'read' => true, 'level_0' => true,'level_1' => true,'eps_system_member'=>true) 
	  		);	
 		}
 		if ( !role_exists('business_admin') ){
 			add_role( 'business_admin', 
	  					'Business Admin', 
	  					array( 'read' => true, 'level_0' => true,'level_1' => true,'eps_system_member'=>true) 
	  	);
 		}
	  
	  if ( !role_exists('business_director')) {
	  	add_role( 'business_director', 
	  					'Business Director', 
	  					array('read' => true, 'level_0' => true,'level_1' => true,'eps_system_member'=>true) 
	  	);
	  }
	  
	  if ( !role_exists('holding_member')) {
	  	add_role( 'holding_member', 
	  					'Holding Member', 
	  					array('read' => true, 'level_0' => true,'level_1' => true,'eps_system_member'=>true) 
	  	);
	  }
	}
/*
 * ------------------------------------------------------------
 * Create business users
 * ------------------------------------------------------------
*/
 function create_business_users(){
 	global $wpdb;
 	if (!username_exists('business.admin')) {
	 	$userdata = array(
	    'user_login'    	=>   'business.admin',
	    'user_email'    	=>   'business.admin@eps.com',
	    'user_pass'     	=>   'business.admin',
	    'first_name'    	=>   'business.admin',
	    'last_name'     	=>   'business.admin',
	    );
	  $user = wp_insert_user( $userdata );
 	} else {
 		
 		if ( !function_exists('get_user_by') ){
			require_once ABSPATH . 'wp-includes/pluggable.php';
		}
 		
 		$user = get_user_by( 'email', 'business.admin@eps.com' );
 	}

	if ($user) {
		//add this user to genealogy
	  $afl_date_splits = afl_date_splits(afl_date());
	 	$genealogy_table = $wpdb->prefix . 'afl_user_genealogy';
	 	$unilevel_genealogy_table = $wpdb->prefix . 'afl_unilevel_user_genealogy';
	 	
	  $ins_data = array();
	  $ins_data['uid']                = $user->ID;
	  $ins_data['referrer_uid']       = 0;
	  $ins_data['parent_uid']         = 0;
	  $ins_data['level']              = 1;
	  $ins_data['relative_position']  = 0;
	  $ins_data['status']             = 1;
	  $ins_data['created']            = afl_date();
	  $ins_data['modified']           = afl_date();
	  $ins_data['joined_day']         = $afl_date_splits['d'];
	  $ins_data['joined_month']       = $afl_date_splits['m'];
	  $ins_data['joined_year']        = $afl_date_splits['y'];
	  $ins_data['joined_week']        = $afl_date_splits['w'];
	  $ins_data['joined_date']        = afl_date_combined($afl_date_splits);

	  
	  $ins_id = $wpdb->insert($genealogy_table, $ins_data);
	  
	  $ins_id = $wpdb->insert($unilevel_genealogy_table, $ins_data);
	}

		//set role to user
		$u = new WP_User( $user->ID );
		// Add role
		$u->add_role( 'business_admin' );
 }
