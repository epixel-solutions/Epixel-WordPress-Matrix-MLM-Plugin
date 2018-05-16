<?php 
/*
 * ---------------------------------------------
 * Add new customer
 * ---------------------------------------------
*/
 function afl_unilevel_add_new_customer () {
 	do_action('eps_affiliate_page_header');
 		afl_unilevel_add_new_customer_form();
 	do_action('afl_content_wrapper_begin');
 	do_action('afl_content_wrapper_end');
 }
 function afl_unilevel_add_new_customer_form () {
	$obje = new Afl_enque_scripts('eps-add-new-member');
	new Afl_enque_scripts('common');
	
 	
 	$post = array();
 	if ( isset($_POST['submit'] ) ) {
        $rules = unilevel_create_customer_validation_rules($_POST);
			 	$resp  = set_form_validation_rule($rules);
			 	if (!$resp) {
			 		$post = $_POST;
			 	} else {
			 		// // sanitize user form input
	        global $username, $password, $email, $sponsor, $first_name, $sur_name;
	        $username   =   sanitize_user( $_POST['user_name'] );
	        $password   =   esc_attr( $_POST['password'] );
	        $email      =   sanitize_email( $_POST['email'] );
	        $first_name =   sanitize_text_field( $_POST['first_name'] );
	        $sur_name  	=   sanitize_text_field( $_POST['sur_name'] );
	        $sponsor   	=   sanitize_text_field( $_POST['sponsor'] );
	        $enrollment_amount		=		sanitize_text_field( $_POST['enrollment_amount']);
	 	
	        // // call @function complete_registration to create the user
	        // // only when no WP_error is found

	        $user_uid = unilevel_complete_customer_registration(
		        $username,
		        $password,
		        $email,
		        $first_name,
		        $sur_name,
		        $sponsor
	        );
	        $post_data = array();
	        if ($user_uid) {

	        	//add new role if he has this role
	        	$theUser = new WP_User($user_uid);
 						$theUser->add_role( 'afl_customer' );

	        	$post_data['uid'] = $user_uid;
	        	//extract sponsor uid
	        	// preg_match_all('/\d+/', $sponsor, $matches);
						preg_match('#\((.*?)\)#', $sponsor, $matches);

    				$post_data['sponsor_uid'] = $matches[1];
	        
	        	
	        	/*
	        	 * ---------------------------------------------------------------------
	        	 * If the sponsor is a customer, then place directly to the unilevel
	        	 * genealogy
	        	 * ---------------------------------------------------------------------
	        	*/
	        		$roles = afl_user_roles($post_data['sponsor_uid']);
	        		
	        		if (array_key_exists('afl_customer', $roles)) {
	    					do_action('eps_affiliates_place_customer_under_sponsor',$post_data['uid'] ,$post_data['sponsor_uid'] );
	    					do_action('eps_affiliates_unilevel_place_user_under_sponsor',$post_data['uid'] ,$post_data['sponsor_uid'] );
	        			
	        		} else {
			        	//place the user into the sposnosrs customer table
		    				do_action('eps_affiliates_place_customer_under_sponsor',$post_data['uid'] ,$post_data['sponsor_uid'] );
		    				do_action('eps_affiliates_unilevel_place_user_in_holding_tank',$post_data['uid'] ,$post_data['sponsor_uid'] );
		    			}


		    				//create a purchase
		  				$args['order_id']		 = 1;
							$args['afl_point']	 = 145;
							$args['uid'] 				 = $post_data['uid'];
							$args['amount_paid'] = 145;
		  				apply_filters('eps_commerce_purchase_complete',$args);

    				
	        	$post_data['uid'] = $user_uid;
	        	//extract sponsor uid
	        	preg_match_all('/\d+/', $sponsor, $matches);
    				$post_data['sponsor_uid'] = $matches[0];


				 	wp_set_message('Customer has been created successfully', 'success');

	      }
			}
		}
 	afl_unilevel_add_new_customer_form_callback($post);
 }
/*
 * ---------------------------------------------
 * Add new member Form
 * ---------------------------------------------
*/
 function afl_unilevel_add_new_customer_form_callback ($post) {
 	// pr(get_users());
 	afl_content_wrapper_begin();

 	$form = array();
 	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';

 	$form['first_name'] = array(
 		'#title' => 'First Name',
 		'#type' => 'text',
 		'#name' => 'first name',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#default_value' => isset($post['first_name']) ? $post['first_name'] : '',
 		
 	);
	$form['sur_name'] = array(
 		'#title' => 'Sur Name',
 		'#type' => 'text',
 		'#name' => 'Sur name',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#default_value' => isset($post['sur_name']) ? $post['sur_name'] : '',
 		
 	);
 	$form['user_name'] = array(
 		'#title' => 'User Name',
 		'#type' => 'text',
 		'#name' => 'User name',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#default_value' => isset($post['user_name']) ? $post['user_name'] : '',
 		
 	);
 	$form['email'] = array(
 		'#title' => 'Email address',
 		'#type' => 'text',
 		'#name' => 'Email address',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#default_value' => isset($post['email']) ? $post['email'] : '',
 		
 	);
 	$form['password'] = array(
 		'#title' => 'Password',
 		'#type' => 'password',
 		'#name' => 'Password',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		
 	);
 	$form['confirm_password'] = array(
 		'#title' => 'Confirm Password',
 		'#type' => 'password',
 		'#name' => 'Confirm Password',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		
 	);

 	$default_value = '';
 	if (eps_is_admin()) {
 		$default_value = afl_variable_get('root_user','');
 	} else {
 		$user = wp_get_current_user();

 		$default_value = $user->data->user_login.' ('.$user->ID.')';
 	}
 	$form['sponsor'] = array(
 		'#title' => 'Sponsor',
 		'#type' => 'auto_complete',
 		'#name' => 'sponsor',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		//for autocomplete call action action hook
 		'#auto_complete_path' => 'users_auto_complete',
 		'#default_value' => isset($post['sponsor']) ? $post['sponsor'] :$default_value ,
 		
 	);
 	$form['mobile'] = array(
 		'#title' => 'Mobile number',
 		'#type' => 'text',
 		'#name' => 'Mobile number',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		
 	);
 $form['enrollment_amount'] = array(
 		'#title' => 'Enrollment Amount',
 		'#type' => 'text',
 		'#name' => 'Enrollment Amount',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#default_value' => isset($post['enrollment_amount']) ? $post['enrollment_amount'] : '100',
 		'#prefix'=>'<div class="form-group row"> ',
 		'#suffix' =>'</div>'
 	);
/* 	$form['enrollment_amount'] = array(
 		'#title' => 'Enrollment Amount',
		'#type' 					=> 'select',
		'#options' 				=> array('1'=>100, '2'=>200),
		'#default_value' 	=> afl_variable_get('enrollment_amount',1),
		'#prefix'=>'<div class="form-group row"> ',
 		'#suffix' =>'</div>'
 	
 	);*/

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
 }

function unilevel_complete_customer_registration($username, $password, $email, $first_name, $sur_name, $sponsor) {
	
    global $reg_errors;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    	=>   $username,
        'user_email'    	=>   $email,
        'user_pass'     	=>   $password,
        'first_name'    	=>   $first_name,
        'last_name'     	=>   $sur_name,
        );
        $user = wp_insert_user( $userdata );
        return $user;
    }
}
/*
 * ---------------------------------------------
 * Create validation rules array
 * ---------------------------------------------
*/
	function unilevel_create_customer_validation_rules ($POST) {
		$rules = array();
			 	$rules[] = array(
			 		'value'=>$POST['user_name'],
			 		'name' =>'user name',
			 		'field'=>'user_name',
			 		'rules' => array(
			 			'rule_required',
			 			'rule_name_length',
			 			'rule_user_name_valid',
			 			'rule_user_already_name_exists',
			 		)
			 	);
			 	$rules[] = array(
			 		'value'=>$POST['password'],
			 		'name' =>'Password',
			 		'field'=>'password',
			 		'rules' => array(
			 			'rule_required',
			 			'rule_name_length',
			 		)
			 	);
			 	$rules[] = array(
			 		'value'=> $POST['email'],
			 		'name' =>'Email',
			 		'field'=>'email',
			 		'rules' => array(
			 			'rule_required',
			 			'rule_valid_email',
			 			'rule_email_exists',
			 		)
			 	);
			 	//extract the name from the sponsor name
			 	$split = explode('(', $_POST['sponsor']);
			 	$sponsor_name = $split[0];
			 	$rules[] = array(
			 		'value'=> $sponsor_name,
			 		'name' =>'Sponsor',
			 		'field'=>'sponsor',
			 		'rules' => array(
			 			'rule_user_name_exists',
			 			'rule_required',
			 		)
			 	);
		return $rules;
	}

