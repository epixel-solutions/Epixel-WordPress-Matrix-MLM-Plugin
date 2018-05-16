<?php
if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('AuthAjax')){

	class AuthAjax {
		
		public function __construct()
		{
			add_action('wp_ajax_get_customers', array($this, 'get_customers'));
			add_action('wp_ajax_view_lead', array($this, 'view_lead'));
			add_action('wp_ajax_nopriv_fx_customer_pause_account',  array($this,'customer_pause_account'));
			add_action('wp_ajax_fx_customer_pause_account',  array($this,'customer_pause_account'));

		}	

		// Get all customers in authorize.net CIM
		public function get_customers()
		{
			$anet = new AuthAPI();
			$customers = $anet->get_all_users();
			$response['data'] = $customers['data'];
			wp_send_json($response);
			wp_die();
		}

		// View lead info for customers and subscriptions
		public function view_lead()
		{
			$source = $_POST['source'];
			$id     = $_POST['id']; // Can be profile id or subscription id

			// dd($id);
			// Customer Information
			if ( $source == 'info_customer' ) {

			}

			if ( $source == 'info_subscription' ) {

			}

			$response['status'] = 'success';
			wp_send_json(@$response);
			wp_die();
		}

		public function customer_pause_account()
		{
			$user_id = get_current_user_id();
			$subscription_id = $_POST['subscription_id'];

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX && $user_id > 1) { 
				$auth_api = new AuthAPI();
				$subscription = array( 
					'name' => 'Pause Account Subscription',
					'interval' => 1,
					'unit' => 'months',
					'start_date' => new DateTime(),
					'occurences' => 9999,
					'amount' => 9.99 
				);

				$customer_profile = $auth_api->get_customer_profile( $user_id );

				if( $customer_profile ){
					$result = $auth_api->create_customer_subscription( $subscription, $customer_profile );
					
					if( is_numeric($result) ) {
						add_user_meta( $user_id, '_pause_subscription_id', $result);
						do_action( 'user_subscription_paused', $subscription_id );
						$response['status'] = 'success';
						$response['message'] = 'created new pause account subscription';
						$response['args'] = array('subscription_id' => $result);

						wp_send_json($response);
						wp_die();

					} else {
						$response['status'] = 'fail';
						$response['message'] = $result;
						wp_send_json($response);
						wp_die();
					}

				} else {
					$response['status'] = 'fail';
					$response['message'] = 'cannot find anet profile record';
					wp_send_json($response);
					wp_die();
				}
				
			}
			wp_die();
		}

	}

}

return new AuthAjax();