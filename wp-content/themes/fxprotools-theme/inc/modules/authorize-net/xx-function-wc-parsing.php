<?php

/**
 * ----------------
 * Add Custom Users
 * ----------------
 * Parsing custom users to wp users and subscribe to business
 */
function wp_parse_user($username, $password, $email, $phone_number, $start_date)
{	
	if (!username_exists($username)  && !email_exists($email)){
		$user_data = array(
			'user_login'    => $username,
			'user_pass'     => $password,
			'user_nicename' => $username,
			'user_email'    => $email,
			'role'          => 'subscriber'
		);
		$user_id = wp_insert_user($user_data);
		add_user_meta( $user_id, '_imported_user', '1' ); 
		$status = ( !is_wp_error($user_id) ? 'success' : 'failed' );
		
		if($status == 'success') {
			// Update WooCommerce Billing Phone Nummber
			update_user_meta($user_id, 'billing_phone', $phone_number);
			// Added automatically to wocommerce subscriptions
			wc_parse_subscription($email, 48, $start_date);
		}
		
	} else {
		$status = 'failed';
	}
	return $status;
}

/**
 * --------------------------------------
 * WC Add Subscription - Business Product
 * --------------------------------------
 * Add subscription to business product(id: 48)
 */
function wc_parse_subscription($user_email, $product_id, $start_date)
{
	$user     = get_user_by('email', $user_email);
	$quantity = 1;
	$period   = WC_Subscriptions_Product::get_period($product_id);
	$interval = WC_Subscriptions_Product::get_interval($product_id);

	$date['start_date']   = date('Y-m-d H:i:s', strtotime($start_date));
	$date['next_payment'] = date('Y-m-d H:i:s', strtotime('2017/9/20'));

	$sub_args = array(
		'status'           => 'active',
		'customer_id'      => $user->id,
		'start_date'       => $date['start_date'],
		'billing_period'   => $period,
		'billing_interval' => $interval,
	);

	$subscription = wcs_create_subscription($sub_args);
	$subscription->update_dates(array('schedule_next_payment' => $date['next_payment']));
	$subscription->add_product(wc_get_product($product_id), $quantity, $price_args);
	$subscription->calculate_totals();
}

/**
 * --------------------------------
 * Parse Users - from authorize.net
 * --------------------------------
 * Add users from authorize.net to wordpress users and woocommerce subscriptions
 */
function parse_user_subscriptions()
{
	ini_set('max_execution_time', 0);
	global $wpdb;

	$accounts = $wpdb->get_results("SELECT * FROM tbl_accounts WHERE status='Active'");
	// $accounts = $wpdb->get_results('SELECT * FROM tbl_accounts  WHERE CHAR_LENGTH(phone_number) > 14');
	foreach($accounts as $act) {

		$p_username     = $act->email;
		$p_password     = 'password123';
		$p_email        = $act->email;
		$p_phone_number = $act->phone_number;
		$p_date         = $act->auth_sub_date_date;

		if(!empty($p_date)){
			$date = date('Y/m/d', strtotime($p_date));
			$p_sub_date = date('Y-m-d H:i:s', strtotime($date));
		} else {
			$p_sub_date = date('Y-m-d H:i:s', strtotime('2017/06/01'));
		}

		$check[] = array(
			'p_username'     => $act->email,
			'p_password'     => 'password123',
			'p_email'        => $act->email,
			'p_phone_number' => str_replace(' ', '', $act->phone_number),
			'p_date'         => $p_sub_date,
		);
		// wp_parse_user($p_username, $p_password, $p_email, $p_phone_number, $p_sub_date);
	}
}