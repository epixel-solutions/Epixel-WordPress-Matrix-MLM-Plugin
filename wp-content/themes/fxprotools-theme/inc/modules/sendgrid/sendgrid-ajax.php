<?php
require ABSPATH . 'vendor/autoload.php';

if(!class_exists('FX_Sendgrid_Ajax')){

	class FX_Sendgrid_Ajax {

		public function __construct()
		{
			add_action('wp_ajax_fx_sendgrid_capture_email', array($this, 'capture_email') );
			add_action('wp_ajax_nopriv_fx_sendgrid_capture_email', array($this, 'capture_email') );
		}	

		public function capture_email()
		{
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
				$recipient = array('email' => $_POST['email'], 'campaign' => $_POST['funnel_id'], 'first_name' => $_POST['name'], 'contact' => $_POST['contact'], 'affiliate_user_id' => $_POST['affiliate_user_id'] );
				$recipient_id = FX_Sendgrid_Api::add_recipient($recipient);
				if($recipient_id){
					FX_Sendgrid_Api::add_recipient_to_list($recipient_id);
					echo json_encode( array( 'status' => 'OK' ) );
				}
				else{
					echo json_encode( array( 'status' => 'FAIL') );
				}
			}
			wp_die();	
		}
	}
}

return new FX_Sendgrid_Ajax();