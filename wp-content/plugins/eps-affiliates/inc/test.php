<?php
function afl_generate_users () {
	echo afl_eps_page_header();

	echo afl_content_wrapper_begin();
		afl_generate_users_form();
		afl_generate_customers_form();
	echo afl_content_wrapper_end();
}

function afl_generate_users_form () {
	
	new Afl_enque_scripts('test');
		new Afl_enque_scripts('common');

	$website = "http://example.com";

	afl_generate_users_form_callback();
	if (!empty($_POST['submit'])) {
		
		$error_count 		= 0;
		$success_count 	= 0;

		$start_with = $_POST['user_name_start'];
		$count = $_POST['user_count'];

		$sponsor = $_POST['sponsor'];
		preg_match('#\((.*?)\)#', $sponsor, $matches);
		$sponsor_uid = $matches[1];

		$begin_from = _get_last_inserted($start_with);
		// pr($begin_from,1);
		for ($i = $begin_from ; $i <= ($count + $begin_from - 1) ; $i++) {
			$name = $start_with.'-'.$i;
			if (!username_exists( $name )) {
				$userdata = array(
	        'user_login'    	=>  $name ,
	        'user_email'    	=>   $name.'@eps.com',
	        'user_pass'     	=>   $name,
	        'first_name'    	=>   $name,
	        'last_name'     	=>   $name,
        );
        $user = wp_create_user( $name, $name, $name.'@eps.com' );

        if ($user) {

        	// do_action('eps_affiliates_place_user_in_holding_tank',$user ,$sponsor_uid );
    			// do_action('eps_affiliates_unilevel_place_user_in_holding_tank',$user ,$sponsor_uid );

    			   	//create a purchase
  				$args['order_id']		 = 1;
					$args['afl_point']	 = 145;
					$args['uid'] 				 = $user;
					$args['amount_paid'] = 145;
  				apply_filters('eps_commerce_purchase_complete',$args);

    			do_action('eps_affiliates_unilevel_place_user_under_sponsor',$user ,$sponsor_uid );
        	do_action('eps_affiliates_place_user_under_sponsor',$user ,$sponsor_uid );

        	// $reg_object = new Eps_affiliates_registration;
	        // $reg_object->afl_join_member(
	        // 	array(
	        // 		'uid'=>$user,
	        // 		'sponsor_uid' => $sponsor_uid
	        // 		)
	        // );

	        // $reg_object = new Eps_affiliates_unilevel_registration;
	        // $reg_object->afl_join_unilevel_member(
	        // 	array(
	        // 		'uid'=>$user,
	        // 		'sponsor_uid' => $sponsor_uid
	        // 		)
	        // );


				//add afl_member role to the user
				$theUser = new WP_User($user);
				$theUser->add_role( 'afl_member' );
				//add the Enrollment fee to the business vallet
				$business_transactions['associated_uid'] = $user;
  			$business_transactions['uid'] = afl_root_user(); /*Business admin uid or root user id*/;
  			$business_transactions['credit_status'] = 1;
        $business_transactions['category'] 			= 'ENROLMENT FEE';
  			$business_transactions['additional_notes'] 	= 'Enrolment joining Fee';
  			$business_transactions['amount_paid'] 			= 100;
  			$business_transactions['notes'] 				= 'Enrolment Fee';
  			$business_transactions['currency_code'] = 'USD';
  			$business_transactions['order_id'] 			= 1;
       	
       	$response = apply_filters('eps_commerce_joining_package_purchase_complete',$business_transactions);


        	$success_count+=1;
        } else {
        	$error_count+=1;
        }
			} else {
        $error_count+=1;
			}
		}
		if ($success_count) {
			echo wp_set_message('Generated users count : '.$success_count, 'success');
		}
		if ($error_count) {
			echo wp_set_message('Errro occured count : '.$error_count, 'error');
		}
	}
}

function _get_last_inserted($string_prefix = '') {
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
function afl_generate_users_form_callback( ){
	new Afl_enque_scripts('test');
	new Afl_enque_scripts('common');


	$form = array();
	$form['#method'] = 'post';
	$form['#action'] = $_SERVER['REQUEST_URI'];
	$form['fieldset']  = array(
		'#type' => 'fieldset',
		'#title' => 'Generate Members'
	);
	$form['fieldset']['user_name_start'] = array(
		'#type' =>'text',
		'#title' =>'starting-with',

	);
	$form['fieldset']['sponsor'] = array(
		'#type' =>'auto_complete',
		'#title' =>'sponsor',
		'#auto_complete_path' => 'users_auto_complete',

	);
	$form['fieldset']['user_count'] = array(
		'#type' =>'text',
		'#title' =>'No.of users',

	);
	$form['fieldset']['submit'] = array(
		'#type' =>'submit',
		'#value' =>'Generate'
	);
	echo afl_render_form($form);
}
function afl_generate_users_form_validation ($name) {

}


function afl_generate_customers_form () {
	new Afl_enque_scripts('test');
	new Afl_enque_scripts('common');


	$website = "http://example.com";

	afl_generate_customers_form_callback();
	if (!empty($_POST['generate_customer'])) {

		$error_count 		= 0;
		$success_count 	= 0;

		$start_with = $_POST['user_name_start'];
		$count = $_POST['user_count'];

		$sponsor = $_POST['sponsor'];
		preg_match('#\((.*?)\)#', $sponsor, $matches);
		$sponsor_uid = $matches[1];

		$begin_from = _get_last_inserted($start_with);
		// pr($begin_from,1);
		for ($i = $begin_from ; $i <= ($count + $begin_from - 1) ; $i++) {
			$name = $start_with.'-'.$i;
			if (!username_exists( $name )) {
				$userdata = array(
	        'user_login'    	=>  $name ,
	        'user_email'    	=>   $name.'@eps.com',
	        'user_pass'     	=>   $name,
	        'first_name'    	=>   $name,
	        'last_name'     	=>   $name,
        );
        $user = wp_create_user( $name, $name, $name.'@eps.com' );

        if ($user) {
        	$reg_object = new Eps_affiliates_customer_registration;
	        $reg_object->afl_join_customer(
	        	array(
	        		'uid'=>$user,
	        		'sponsor_uid' => $sponsor_uid
	        		)
	        );
	        do_action('eps_affiliates_unilevel_place_user_in_holding_tank',$user ,$sponsor_uid );

	         	//create a purchase
  				$args['order_id']		 = 1;
					$args['afl_point']	 = 145;
					$args['uid'] 				 = $user;
					$args['amount_paid'] = 145;
  				apply_filters('eps_commerce_purchase_complete',$args);


				//add afl_member role to the user
				$theUser = new WP_User($user);
				$theUser->add_role( 'afl_customer' );

        	$success_count+=1;
        } else {
        	$error_count+=1;
        }
			} else {
        $error_count+=1;
			}
		}
		if ($success_count) {
			echo wp_set_message('Generated customers count : '.$success_count, 'success');
		}
		if ($error_count) {
			echo wp_set_message('Errro occured count : '.$error_count, 'error');
		}
	}
}

function afl_generate_customers_form_callback () {
	$form = array();
	$form['#method'] = 'post';
	$form['#action'] = $_SERVER['REQUEST_URI'];
	$form['fieldset']  = array(
		'#type' => 'fieldset',
		'#title' => 'Generate Customers'
	);
	$form['fieldset']['user_name_start'] = array(
		'#type' =>'text',
		'#title' =>'starting-with',

	);
	$form['fieldset']['sponsor'] = array(
		'#type' =>'auto_complete',
		'#title' =>'sponsor',
		'#auto_complete_path' => 'users_auto_complete',

	);
	$form['fieldset']['user_count'] = array(
		'#type' =>'text',
		'#title' =>'No.of users',

	);
	$form['fieldset']['generate_customer'] = array(
		'#type' =>'submit',
		'#name' => 'generate_customer',
		'#value' =>'Generate'
	);
	echo afl_render_form($form);
}
/* ------------------- Purchases ------------------------------------------*/
function afl_test_purchases () {
	echo afl_eps_page_header();

	echo afl_content_wrapper_begin();
		afl_test_purchses_form();
	echo afl_content_wrapper_end();
}

function afl_test_purchses_form() {
	new Afl_enque_scripts('test');
	new Afl_enque_scripts('common');

	
	if (isset($_POST['submit']) && !empty($_POST['product'])) {
		afl_test_purchses_form_submit($_POST);
	}
	$html_tag = '';

	$html_tag .= '<style>.pricingTable{
    text-align: center;
    background: #727cb6;
    padding-top: 5px;
    transition: all 0.5s ease-in-out 0s;
}
.pricingTable > .pricingTable-header{
    color:#fff;
    background: #273238;
    height: 190px;
    position: relative;
    transition: all 0.5s ease 0s;
}
.pricingTable > .pricingTable-header:after{
    content: "";
    border-bottom: 40px solid #727cb6;
    border-left: 248px solid transparent;
    position: absolute;
    right:0px;
    bottom: 0px;
}

.pricingTable-header > .heading{
    display: block;
    padding: 20px 0;
}
.heading > h3{
    margin: 0;
    text-transform: uppercase;
}
.pricingTable-header > .price-value{
    display: block;
    font-size: 60px;
    line-height: 60px;
}
.pricingTable-header > .price-value > .mo{
    font-size: 14px;
    display: block;
    line-height: 0px;
    text-transform: uppercase;
}
.pricingTable-header > .price-value > .currency{
    font-size: 24px;
    margin-right: 4px;
    position: relative;
    bottom:30px;
}
.pricingTable > .pricingContent{
    text-transform: uppercase;
    color:#fff
}
.pricingTable > .pricingContent > ul{
    list-style: none;
    padding: 0;
}
.pricingTable > .pricingContent > ul > li{
    padding: 15px 0;
    border-bottom: 1px solid #fff;
}
.pricingTable > .pricingContent > ul > li:last-child{
    border: 0px none;
}
.pricingTable-sign-up{
    padding: 30px 0;
}
.pricingTable-sign-up > .btn-block{
    width: 80%;
    margin: 0 auto;
    background: #273238;
    border:2px solid #fff;
    color:#fff;
    padding: 15px 12px;
    text-transform: uppercase;
    font-size: 18px;
}

.pink{
    background: #ed687c;
}
.pink .pricingTable-header:after{
    border-bottom-color: #ed687c;
}
.orange{
    background: #e67e22;
}
.orange .pricingTable-header:after{
    border-bottom-color: #e67e22;
}
.blue{
    background: #3498db;
}
.blue .pricingTable-header:after{
    border-bottom-color: #3498db;
}

.pricingTable input{
			visibility:hidden;
		}
.selected {
		border: 4px solid #ccc;
		background: #273238;
		padding: 2px;
		filter: alpha(opacity=100);
		opacity: 1;
}
.pricingTable:hover{cursor:pointer;}
</style>';

	$html_tag .= '<form action = "" method ="post">';
	$html_tag .= '<div class="container">';
	$html_tag .= '<div class="row">';

	$html_tag .= '<div class="col-md-3 col-sm-6">';
	$html_tag .= '<div class="pricingTable">';
	$html_tag .= '<input type = "radio" name = "product" value = "professional">';
	$html_tag .= '<div class="pricingTable-header">';
	$html_tag .= '<span class="heading">';
	$html_tag .= '<h3>Professional</h3>';
	$html_tag .= '</span>';
	$html_tag .= '<span class="price-value">';
	$html_tag .= '<span class="currency">$</span>345';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingContent">';
	$html_tag .= '<ul>';
	$html_tag .= '<li>$200 initial setup fee</li>';
	$html_tag .= '<li>+$145 monthly renewal</li>';
	$html_tag .= '</ul>';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingTable-sign-up">
                  <span class="btn btn-block btn-default">145 point</span>
              </div>';
	$html_tag .= '</div>';
	$html_tag .= '</div>';


	$html_tag .= '<div class="col-md-3 col-sm-6">';
	$html_tag .= '<div class="pricingTable">';
	$html_tag .= '<input type = "radio" name = "product" value = "business">';
	$html_tag .= '<div class="pricingTable-header">';
	$html_tag .= '<span class="heading">';
	$html_tag .= '<h3>Business</h3>';
	$html_tag .= '</span>';
	$html_tag .= '<span class="price-value">';
	$html_tag .= '<span class="currency">$</span>360';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingContent">';
	$html_tag .= '<ul>';
	$html_tag .= '<li>$200 initial setup fee</li>';
	$html_tag .= '<li>+$160 monthly renewal</li>';
	$html_tag .= '</ul>';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingTable-sign-up">
                  <span class="btn btn-block btn-default">145 point</span>
              </div>';
	$html_tag .= '</div>';
	$html_tag .= '</div>';

	$html_tag .= '<div class="col-md-3 col-sm-6">';
	$html_tag .= '<div class="pricingTable">';
	$html_tag .= '<input type = "radio" name = "product" value = "auto-trader">';
	$html_tag .= '<div class="pricingTable-header">';
	$html_tag .= '<span class="heading">';
	$html_tag .= '<h3>Auto-Trader</h3>';
	$html_tag .= '</span>';
	$html_tag .= '<span class="price-value">';
	$html_tag .= '<span class="currency">$</span>410';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingContent">';
	$html_tag .= '<ul>';
	$html_tag .= '<li>$225 initial setup fee</li>';
	$html_tag .= '<li>+$185 monthly renewal</li>';
	$html_tag .= '</ul>';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingTable-sign-up">
                  <span class="btn btn-block btn-default">145 point</span>
              </div>';
	$html_tag .= '</div>';
	$html_tag .= '</div>';

	$html_tag .= '<div class="col-md-3 col-sm-6">';
	$html_tag .= '<div class="pricingTable">';
	$html_tag .= '<input type = "radio" name = "product" value = "advanced">';
	$html_tag .= '<div class="pricingTable-header">';
	$html_tag .= '<span class="heading">';
	$html_tag .= '<h3>Advanced (1 on 1)</h3>';
	$html_tag .= '</span>';
	$html_tag .= '<span class="price-value">';
	$html_tag .= '<span class="currency">$</span>5185';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingContent">';
	$html_tag .= '<ul>';
	$html_tag .= '<li>$5000 initial setup fee</li>';
	$html_tag .= '<li>+$185 monthly renewal</li>';
	$html_tag .= '</ul>';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingTable-sign-up">
                  <span class="btn btn-block btn-default">145 point</span>
              </div>';
	$html_tag .= '</div>';
	$html_tag .= '</div>';


	$html_tag .= '<div class="col-md-3 col-sm-6">';
	$html_tag .= '<div class="pricingTable">';
	$html_tag .= '<input type = "radio" name = "product" value = "distrib_kit">';
	$html_tag .= '<div class="pricingTable-header">';
	$html_tag .= '<span class="heading">';
	$html_tag .= '<h3>Distributor Kit</h3>';
	$html_tag .= '</span>';
	$html_tag .= '<span class="price-value">';
	$html_tag .= '<span class="currency">$</span>0';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingContent">';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingTable-sign-up">
                  <span class="btn btn-block btn-default">0 point</span>
              </div>';
	$html_tag .= '</div>';
	$html_tag .= '</div>';




	$html_tag .= '</div>';
	$html_tag .= '</div>';

	$html_tag .= '<input type ="submit" class = "btn btn-primary" name = "submit" value = "Purchase">';
	$html_tag .= '</form>';
	echo $html_tag;
}

function afl_test_purchses_form_submit () {
	$product = $_POST['product'];
	$args 	 = array();

	$args['uid'] 			= get_uid();
	$args['order_id']	=	10;
	$args['afl_point']=	145;

	switch ($product) {
		case 'professional':
			$args['amount_paid']	=	345;
		break;
		case 'business':
			$args['amount_paid']	=	360;
		break;
		case 'auto-trader':
			$args['amount_paid']	=	410;
		break;
		case 'advanced':
			$args['amount_paid']	=	5185;
		break;
		case 'distrib_kit':

			$args['category']			=	'Distributor Kit';
			$args['amount_paid']	=	0;
			$args['afl_point'] 		= 0;
		break;
	}

	if ( apply_filters('check_free_account_criterias',get_uid())) {
		$args['amount_paid'] = 0;
		$args['afl_point'] 	 = 0;
		$args['category']			=	'Free Business Product';

	}
	if ($product == 'distrib_kit') {
		$resp = apply_filters('eps_commerce_distributor_kit_purchase_complete', $args);
	} else {
		$resp = apply_filters('eps_commerce_purchase_complete', $args);
	}

	if ($resp['status'] == 1) {
		echo wp_set_message('Purchase product', 'success');
	} else {
		echo wp_set_message('Error occured', 'error');
	}

}
/* --------------------------------------------------- Generate Purchase --------------------------*/
function afl_generate_purchase () {
	echo afl_eps_page_header();

	echo afl_content_wrapper_begin();
		afl_generate_purchase_form();
		afl_customer_generate_purchase_form();
	echo afl_content_wrapper_end();
}
/*
 * ------------------------------------------------------
 * Generate afl member purchase
 * ------------------------------------------------------
*/
	function afl_generate_purchase_form () {
		new Afl_enque_scripts('common');

		if ( isset ( $_POST['submit'] ) ) {
			if (isset($_POST['uid'])) 
					$uid = $_POST['uid'];
			else
				$uid = extract_sponsor_id($_POST['user']);
			
			$product = $_POST['package'];
			$count 	 = !empty($_POST['count'] && is_numeric($_POST['count'])) ? $_POST['count'] : 1;

			$args = array();
			$args['uid'] = $uid;
			$args['order_id'] = 10;
			$args['afl_point'] = 145 * $count;

			switch ($product) {
				case 'professional':
					$args['amount_paid']	=	345 * $count;
				break;
				case 'business':
					$args['amount_paid']	=	360 * $count;
				break;
				case 'auto-trader':
					$args['amount_paid']	=	410 * $count;
				break;
				case 'advanced':
					$args['amount_paid']	=	5185 * $count;
				break;
				case 'distrib_kit':
					$args['category']			=	'Distributor Kit';
					$args['amount_paid']	=	0;
					$args['afl_point'] 		= 0;
				break;
			}

			if ($product == 'distrib_kit') {
				$resp = apply_filters('eps_commerce_distributor_kit_purchase_complete', $args);
			} else {
				$resp = apply_filters('eps_commerce_purchase_complete', $args);
			}

			if ($resp['status'] == 1) {
				echo wp_set_message('Purchase product', 'success');
			} else {
				echo wp_set_message('Error occured', 'error');
			}

		}
		afl_generate_purchase_form_callback();
	}
/*
 * ------------------------------------------------------
 * Generate afl member purchase form callback
 * ------------------------------------------------------
*/
	function afl_generate_purchase_form_callback () {
		$form = array();
		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];

		$form['fieldset'] = array(
			'#type' => 'fieldset',
			'#title' =>'Member purchase'
		);

		$form['fieldset']['user'] = array(
			'#type' =>'auto_complete',
			'#title' =>'user',
			'#auto_complete_path' => 'users_auto_complete',
		);
		$form['fieldset']['uid'] = array(
			'#type' =>'textfield',
			'#title' =>'user id',
		);
		$form['fieldset']['package'] = array(
			'#type' =>'select',
			'#title' =>'package',
			'#options' => array(
				'professional' 	=> 'Professional',
				'business' 			=> 'Business',
				'auto-trader'		=> 'Auto Trader',
				'advanced'			=> 'Advanced',
				'distrib_kit'			=> 'Distributor Kit',
			)

		);

		$form['count'] = array(
			'#type' =>'text',
			'#title' =>'product count',
		);

		$form['submit'] = array(
			'#type' =>'submit',
			'#value' =>'Generate'
		);
		echo afl_render_form($form);
	}
/* --------------------------------------------------- Generate Purchase --------------------------*/




/* ------------------------------------------ Customer ---- Generate Purchase ----------------------*/

	
/*
 * ------------------------------------------------------
 * Generate afl customer purchase form 
 * ------------------------------------------------------
*/
	function afl_customer_generate_purchase_form () {
		if ( isset ( $_POST['customer_purchase'] ) ) {
			if (isset($_POST['uid'])) 
					$uid = $_POST['uid'];
			else
				$uid = extract_sponsor_id($_POST['user']);

			$product = $_POST['package'];
			$count 	 = !empty($_POST['count'] && is_numeric($_POST['count'])) ? $_POST['count'] : 1;

			$args = array();
			$args['uid'] = $uid;
			$args['order_id'] = 10;
			$args['afl_point'] = 145 * $count;

			switch ($product) {
				case 'professional':
					$args['amount_paid']	=	345 * $count;
				break;
				case 'business':
					$args['amount_paid']	=	360 * $count;
				break;
				case 'auto-trader':
					$args['amount_paid']	=	410 * $count;
				break;
				case 'advanced':
					$args['amount_paid']	=	5185 * $count;
				break;
				case 'distrib_kit':
						$args['category']			=	'Distributor Kit';
						$args['amount_paid']	=	0;
						$args['afl_point'] 		= 0;
					break;
			}

				if ($product == 'distrib_kit') {
					$resp = apply_filters('eps_commerce_distributor_kit_purchase_complete', $args);
				} else {
					$resp = apply_filters('eps_commerce_purchase_complete', $args);
				}


			if ($resp['status'] == 1) {
				echo wp_set_message('customer Purchase product', 'success');
			} else {
				echo wp_set_message('Error occured', 'error');
			}

		}
		afl_customer_generate_purchase_form_callback();
	}
/*
 * ------------------------------------------------------
 * Generate afl customer purchase form  callback
 * ------------------------------------------------------
*/
	function afl_customer_generate_purchase_form_callback () {
		$form = array();
		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];

		$form['fieldset'] = array(
			'#type' => 'fieldset',
			'#title' =>'customer purchase'
		);
		$form['fieldset']['user'] = array(
			'#type' =>'auto_complete',
			'#title' =>'user',
			'#auto_complete_path' => 'customers_auto_complete',

		);
		$form['fieldset']['uid'] = array(
			'#type' =>'textfield',
			'#title' =>'user id',
		);
		$form['fieldset']['package'] = array(
			'#type' =>'select',
			'#title' =>'package',
			'#options' => array(
				'professional' 	=> 'Professional',
				'business' 			=> 'Business',
				'auto-trader'		=> 'Auto Trader',
				'advanced'			=> 'Advanced',
				'distrib_kit'			=> 'Distributor Kit',
			)

		);

		$form['fieldset']['count'] = array(
			'#type' =>'text',
			'#title' =>'product count',
		);

		$form['submit'] = array(
			'#type' =>'submit',
			'#value' =>'Generate',
			'#name' =>'customer_purchase'
		);
		echo afl_render_form($form);
	}

/* ------------------------------------------ Customer ---- Generate Purchase ----------------------*/

function afl_admin_fund_deposit () {
	echo afl_eps_page_header();

	echo afl_content_wrapper_begin();
		afl_admin_fund_deposit_callback();
	echo afl_content_wrapper_end();
}

function afl_admin_fund_deposit_callback () {
		new Afl_enque_scripts('common');
	
	if ( isset($_POST['submit'])) {
		unset($_POST['submit']);
		$validation = afl_admin_fund_deposit_validation($_POST); 

		if ($validation) {
			afl_admin_fund_deposit_submit($_POST);
		} 
	}

	$form = array();
	$form['#method'] = 'post';
	$form['#action'] = $_SERVER['REQUEST_URI'];

	$form['user'] = array(
		'#type' =>'auto_complete',
		'#title' =>'user',
		'#auto_complete_path' => 'users_auto_complete',

	);
	$form['fund'] = array(
		'#type' =>'text',
		'#title' =>'Amount',
	);
	$form['submit'] = array(
		'#type' =>'submit',
		'#value' =>'Generate'
	);
	echo afl_render_form($form);
}

function afl_admin_fund_deposit_validation($form_state = array()) {
		$rules = array();
		
		$split = explode('(', $_POST['user']);
		$user = $split[0];

		$rules[] = array(
		 	'value'=> $user,
	 		'name' =>'user',
	 		'field'=>'user',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_user_name_exists',
	 		)
	 	);
	 $rules[] = array(
		 	'value'=> $form_state['fund'],
	 		'name' =>'user',
	 		'field'=>'fund',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	return set_form_validation_rule($rules);
}

function afl_admin_fund_deposit_submit($form_state = array()){
	$uid 		= extract_sponsor_id($form_state['user']);
	$amount = $form_state['fund'];

	$transaction = array();
  $transaction['uid'] 								= $uid;
  $transaction['associated_user_id'] 	= afl_root_user();
  $transaction['currency_code'] 			= afl_currency();
  $transaction['order_id'] 						= 1;
  $transaction['int_payout'] 					= 0;
  $transaction['hidden_transaction'] 	= 0;
  $transaction['credit_status'] 			= 1;
  $transaction['amount_paid'] 				= afl_commerce_amount($amount);
  $transaction['category'] 						= 'FUND DEPOSIT';
  $transaction['notes'] 							= 'Fund Deposited';

	afl_member_transaction($transaction, TRUE);
	wp_set_message('Fund Deposited', 'success');

}