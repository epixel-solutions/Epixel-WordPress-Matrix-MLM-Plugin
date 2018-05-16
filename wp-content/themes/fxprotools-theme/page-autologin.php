<?php  
if($_SESSION['sec_password'] == "^%fxpro%$#@56&"){
	$user_id = $_SESSION["sec_user_id"];
	if($_SESSION["sec_login"] != 0){
		wp_set_current_user($user_id);
		wp_set_auth_cookie($user_id);
	}
	$redir = $_SESSION["sec_redir"];
	unset($_SESSION["sec_user_id"]);
	unset($_SESSION["sec_password"]);
	unset($_SESSION["sec_redir"]);
	unset($_SESSION["sec_login"]);
	wp_redirect( $redir );
	exit;
}else{
	wp_redirect( home_url() );;
}
?>