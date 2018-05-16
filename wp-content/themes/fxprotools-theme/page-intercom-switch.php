<?php
if ( ! is_user_logged_in() ) {
	auth_redirect();
}
if ( current_user_can( 'edit_users' ) && ! empty( $_GET['uid'] ) ) {
	echo 'Switching user...';
	$switch_to_user_id = sanitize_text_field( $_GET['uid'] );
	$switch_to_url = user_switching::switch_to_url( get_user_by( 'ID', $switch_to_user_id ) );
	//redirect to user switching
	header( 'Location: ' . htmlspecialchars_decode( $switch_to_url ), true );
	die();
}

