<?php
/**
 * -----------------------
 * Fxprotools - AJAX Calls
 * -----------------------
 * All hooks for ajax calls
 */

add_action("wp_ajax_check_username", "check_username");
add_action("wp_ajax_nopriv_check_username", "check_username");
function check_username()
{
	$new_username = $_REQUEST['new_username'];
	if (validate_username($new_username) && !username_exists($new_username))
	{
		echo "1";
	}
	else{
		echo "0";
	}

	wp_die();
}

add_action("wp_ajax_email_inbox", "email_inbox");
add_action("wp_ajax_email_trash", "email_trash");
add_action("wp_ajax_email_read", "email_read");
add_action("wp_ajax_email_delete", "email_delete");
add_action("wp_ajax_email_sent", "email_sent");
add_action( 'wp_ajax_fx_renew_password', 'fx_renew_password' );
add_action( 'wp_ajax_nopriv_fx_renew_password', 'fx_renew_password' );
add_action( 'wp_ajax_checklist_pass', 'checklist_pass' );
add_action( 'wp_ajax_skip_referral', 'skip_referral' );

function email_from_status($status)
{
	$response = get_emails_for_user($status);
	header("Content-Type: application/json");
	$mails = array();
	
	foreach ($response as $mail) {
		$mails[] = array(
			'id' => $mail->ID,
			'status' => get_post_meta($mail->ID, '_user_' . get_current_user_id() . '_state')[0],
			'content' => get_post_meta($mail->ID, 'email_content')[0],
			'modified' => date('c', strtotime($mail->post_modified_gmt)),
			'subject' => $mail->post_title
		);
	}
	
	
	wp_send_json($mails);
}

function email_inbox()
{
	email_from_status(array('unread', 'read'));
}

function email_trash()
{
	email_from_status(array('trash'));
}

function email_read()
{
	foreach (explode(',', $_POST['ids']) as $id) {
		update_post_meta($id, '_user_' . get_current_user_id() . '_state', 'read');
	}
	
	echo 'OK';
	wp_die();
}

function email_delete()
{
	foreach (explode(',', $_POST['ids']) as $id) {
		update_post_meta($id, '_user_' . get_current_user_id() . '_state', 'trash');
	}
	
	echo 'OK';
	wp_die();
}

function email_sent()
{
	$response = get_posts(array(
		'posts_per_page'	=> -1,
		'orderby'			=> 'modified',
		'order'				=> 'DESC',
		'post_type'			=> 'fx_email',
		'post_status'		=> 'publish'
	));
	
	header("Content-Type: application/json");
	$mails = array();
	
	foreach ($response as $mail) {
		$mails[] = array(
			'id' => $mail->ID,
			'status' => get_post_meta($mail->ID, '_user_' . get_current_user_id() . '_state')[0],
			'content' => get_post_meta($mail->ID, 'email_content')[0],
			'modified' => $mail->post_modified_gmt . ' GMT+0',
			'subject' => $mail->post_title
		);
	}
	
	echo json_encode($mails);
	
	wp_die();
}

add_action("wp_ajax_send_email", "ajax_send_email");

function ajax_send_email() {
	$postid = wp_insert_post(array(
		'post_type' => 'fx_email',
		'post_title' => $_POST["subject"],
		'post_status' => 'publish'
	));
	
	update_post_meta($postid, "email_recipient_type", $_POST["email_recipient_type"]);
	update_post_meta($postid, "recipient_group", $_POST["recipient_group"]);
	update_post_meta($postid, "recipient_product", $_POST["recipient_product"]);
	update_post_meta($postid, "recipient_individual_type", $_POST["recipient_individual_type"]);
	update_post_meta($postid, "recipient_individual_name", $_POST["recipient_individual_name"]);
	update_post_meta($postid, "recipient_individual_email", $_POST["recipient_individual_email"]);
	update_post_meta($postid, "recipient_individual_user", $_POST["recipient_individual_user"]);
	update_post_meta($postid, "email_content", $_POST["body"]);
	
	post_email_published($postid);
	
	echo "OK";
	wp_die();
}

add_action("wp_ajax_send_sms", "ajax_send_sms");

function ajax_send_sms() {
	$postid = wp_insert_post(array(
		'post_type' => 'fx_sms',
		'post_status' => 'publish'
	));
	
	update_post_meta($postid, "sms_recipient_type", $_POST["sms_recipient_type"]);
	update_post_meta($postid, "recipient_group", $_POST["recipient_group"]);
	update_post_meta($postid, "recipient_product", $_POST["recipient_product"]);
	update_post_meta($postid, "recipient_individual_type", $_POST["recipient_individual_type"]);
	update_post_meta($postid, "recipient_individual_sms", $_POST["recipient_individual_sms"]);
	update_post_meta($postid, "recipient_individual_user", $_POST["recipient_individual_user"]);
	update_post_meta($postid, "sms_content", $_POST["body"]);
	
	post_sms_published($postid);
	
	echo "OK";
	wp_die();
}

add_action("wp_ajax_nopriv_sendgrid_callback", "sendgrid_callback");

function sendgrid_callback() {
	$data = json_decode(file_get_contents('php://input'), true);
	
	foreach ($data as $message) {
		$categories = $message['category'];
		
		if (!is_array($categories)) {
			$categories = [$categories];
		}
		
		foreach ($categories as $category) {
			preg_match("/wpemail-id-(\d+)/", $category, $matches);
			
			if (count($matches) > 0) {
				$emailID = $matches[1];
				
				// Find user by email.
				$user = get_user_by('email', $message['email']);
				
				if ($user) {
					$userID = $user->ID;
					
					// Register the event.
					update_post_meta($emailID, '_user_' . $userID . '_' . $message['event'], true);
					wp_send_json(['success' => true, 'email' => $emailID, 'user' => $userID]);
				} else {
					// Register the event.
					update_post_meta($emailID, '_user_' . rand() . '_' . $message['event'], true);
					wp_send_json(['success' => true, 'email' => $emailID, 'user' => null]);
				}
			}
		}
	}
	
	wp_send_json(['success' => false]);
}

add_action("wp_ajax_nopriv_twilio_callback", "twilio_callback");

function twilio_callback() {
	global $wpdb;
	
	try
	{
		$sid = $_POST['MessageSid'];
		
	    $results = $wpdb->get_results("SELECT post_id, meta_value
	        FROM {$wpdb->postmeta} WHERE meta_key='_{$sid}_user'
	    ");
		
		$postid = $results[0]->post_id;
		$userid = $results[0]->meta_value;
		
		switch ($_POST['MessageStatus']) {
			case 'delivered':
				update_post_meta($postid, '_user_' . $userid . '_delivered', true);
				break;
			case 'undelivered':
			case 'failed':
				update_post_meta($postid, '_user_' . $userid . '_bounce', true);
				break;
		}
		
		wp_send_json(['success' => true]);
	}
	catch (\Error $e) {
		wp_send_json(['success' => false, 'message' => $e->getMessage()]);
	}
	catch (\Exception $e) {
		wp_send_json(['success' => false, 'message' => $e->getMessage()]);
	}
}

function fx_renew_password() {
	if ( isset( $_POST['fx_action'] ) && $_POST['fx_action'] == 'renew_password' ) {
		$current_user = wp_get_current_user();
		//Sanitize received password
		$password = sanitize_text_field( $_POST['new_password'] );
		$confirm_password = sanitize_text_field( $_POST['confirm_password'] );

		if($password == $confirm_password) {
			$userdata = array(
				'ID'        => $current_user->ID,
				'user_pass' => $password // Wordpress automatically applies the wp_hash_password() function to the user_pass field.
			);
			$user_id = wp_update_user( $userdata );

			if ( $user_id == $current_user->ID ) {
				update_user_meta( $current_user->ID, '_imported_user_password_changed', 1 );
				wp_send_json_success();
			}
		}
	}
	wp_send_json_error();
}

function checklist_pass() {
	if ( isset( $_POST['step'] ) ) {
		pass_onboarding_checklist( sanitize_text_field( $_POST['step'] ) );
		wp_send_json_success();
	}
}

function skip_referral() {
	update_user_meta (get_current_user_id(), '_skip_referral', 1);
	wp_send_json_success();
}
