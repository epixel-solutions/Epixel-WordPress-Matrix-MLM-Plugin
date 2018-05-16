<?php
/**
 * -----------------------
 * Authorize.net Functions
 * -----------------------
 * Authorize.net API functions
 */

require ABSPATH . 'vendor/autoload.php';
	
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('AuthAPI')){
	class AuthAPI {
		
		// Test account - ben
		const AUTHORIZENET_API_LOGIN_ID = '4c6W67tnY2ex';
		const AUTHORIZENET_TRANSACTION_KEY = '5Ds238K7v3ccV794';

		// Account 1 - volishon
		// const AUTHORIZENET_API_LOGIN_ID = '2nnPd9yFA34';
		// const AUTHORIZENET_TRANSACTION_KEY = '45BH9L8HgP6m3hyM';

		//Get profile id list
		public function get_profile_ids()
		{
			$merchantAuthentication = $this->anet_authentication(self::AUTHORIZENET_API_LOGIN_ID, self::AUTHORIZENET_TRANSACTION_KEY);

		    // Get all existing customer profile ID's
		    $request = new AnetAPI\GetCustomerProfileIdsRequest();
		    $request->setMerchantAuthentication($merchantAuthentication);
		    $request->setRefId( 'ref' . time() );
		    $controller = new AnetController\GetCustomerProfileIdsController($request);
		    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
		    
		    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
		    {
		       return $response->getIds();
		    }
		    else
		    {
		        return $response->getMessages()->getMessage();
		    }
		}

		//get customer profile object using profile id
		public function get_customer_profile_request($profile_id){
			$merchantAuthentication = $this->anet_authentication(self::AUTHORIZENET_API_LOGIN_ID, self::AUTHORIZENET_TRANSACTION_KEY);
			$request = new AnetAPI\GetCustomerProfileRequest();
			$request->setMerchantAuthentication($merchantAuthentication);
			$request->setCustomerProfileId($profile_id);

			$controller = new AnetController\GetCustomerProfileController($request);
			return $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
		}

		// Get profile information
		public function get_customer_profile($customer_id)
		{
			ini_set('max_execution_time', 0);
			$profile_id = get_user_meta($customer_id, '_anet_profile_id', true);
			if( $profile_id ){
				$response = $this->get_customer_profile_request($profile_id);
				if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
				{
					$profile = $response->getProfile();
					$auth_customer_id = $profile->getMerchantCustomerId();

					if( $auth_customer_id == $customer_id){
						return $profile;
					}
				}
			}

			else{
				$profile_ids = array_reverse( $this->get_profile_ids() );
				foreach($profile_ids as $id){
					$response = $this->get_customer_profile_request($id);
					if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
					{
						$profile = $response->getProfile();
						$auth_customer_id = $profile->getMerchantCustomerId();

						if( $auth_customer_id == $customer_id){
							add_user_meta( $customer_id, '_anet_profile_id', $id);
							return $profile;
						}
					}
				}
			}

			return 0;
		}

		//create ARB Subscriptions directly to authorize.net
		public function create_customer_subscription($args, $customer_profile){
			$merchantAuthentication = $this->anet_authentication(self::AUTHORIZENET_API_LOGIN_ID, self::AUTHORIZENET_TRANSACTION_KEY);
		    
		    $subscription = new AnetAPI\ARBSubscriptionType();
		    $subscription->setName( $args['name'] );
		    $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
		    $interval->setLength( $args['interval'] );
		    $interval->setUnit( $args['unit'] );
		    $paymentSchedule = new AnetAPI\PaymentScheduleType();
		    $paymentSchedule->setInterval($interval);
		    $paymentSchedule->setStartDate( $args['start_date'] );
		    $paymentSchedule->setTotalOccurrences( $args['occurences'] );
		    $subscription->setPaymentSchedule($paymentSchedule);
		    $subscription->setAmount( $args['amount'] );

		    $payment_profiles = $customer_profile->getPaymentProfiles();

		    if( $payment_profiles ){

		    	$payment = $payment_profiles[0];
		    	$address =  $customer_profile->getShipToList()[0];

		    	$profile = new AnetAPI\CustomerProfileIdType();
			    $profile->setCustomerProfileId( $customer_profile->getCustomerProfileId() );
			    $profile->setCustomerPaymentProfileId( $payment->getCustomerPaymentProfileId() );
			    $profile->setCustomerAddressId( $address->getCustomerAddressId() );
			    $subscription->setProfile($profile);
			    $request = new AnetAPI\ARBCreateSubscriptionRequest();
			    $request->setmerchantAuthentication($merchantAuthentication);
			    $request->setRefId( 'ref' . time() );
			    $request->setSubscription($subscription);
			    $controller = new AnetController\ARBCreateSubscriptionController($request);
			    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

			    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
			    {
			       return $response->getSubscriptionId();
			    }

			    else
			    {
			        return $response->getMessages()->getMessage();
			    }
		    }
		    	  
		}

		public function cancel_customer_subscription($subscription_id){
			$merchantAuthentication = $this->anet_authentication(self::AUTHORIZENET_API_LOGIN_ID, self::AUTHORIZENET_TRANSACTION_KEY);
		    
		    $request = new AnetAPI\ARBCancelSubscriptionRequest();
		    $request->setMerchantAuthentication($merchantAuthentication);
		    $request->setRefId( 'ref' . time() );
		    $request->setSubscriptionId($subscription_id);
		    $controller = new AnetController\ARBCancelSubscriptionController($request);
		    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

		    return $response->getMessages()->getMessage();
		}

		// Authentication
		public function anet_authentication($login_id, $transaction_key)
		{
			$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
			$merchantAuthentication->setName($login_id);
			$merchantAuthentication->setTransactionKey($transaction_key);
			return $merchantAuthentication;
		}

	}

}