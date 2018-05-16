<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
use Twilio\Rest\Client;
/**
 * Send SMS using Twilio API
 *
 * 
 * 
 * @access (protected, public)
 * */
class Apyc_SendSMS{
	/**
	 * instance of this class
	 *
	 * @since 3.12
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;
	
	protected $sid = TWILIO_ACCOUNT_SID;
	protected $token = TWILIO_TOKEN;
	
    /**
     * use for magic setters and getter
     * we can use this when we instantiate the class
     * it holds the variable from __set
     *
     * @see function __get, function __set
     * @access protected
     * @var array
     * */
    protected $vars = array();
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	/**
	* This will send SMS using Twilio API
	*
	* @param	$data	array	array elements should be:
	*								send_to => array | list of users to send | sample array,
	*									Array
	*									(
	*										[id] => 2918
	*										[name] => amrinder
	*										[mobile] => 45646456456
	*									)
	*								from_number	=> default to empty	| mobile number, 
	*								msg
	*								
	*								sample: array(
	*									from_number => 1122,
	*									msg => 'String | Text',
	*								)
	* @return Twilio Send SMS 
	**/
	public function send($data = array()){
		//we get the current user login, we will get the id and we can log twilio sent sms
		$current_user = wp_get_current_user();
		//this will hold the return for services api
		$service_array = array();
		//this will hold the phonenumbers api
		$phone_numbers_array = array();
		
		//initialize the twilio api
		$client = new Client($this->sid, $this->token);
		
		/**
		* If our $data['from_number'] is empty meaning we need to use the account / account sid phone number
		**/
		$from = '';
		if( isset($data['from_number']) 
			&& trim($data['from_number']) != ''
		){
			//if its there, then copy it, just pass it
			$from = $data['from_number'];
		}else{
			//if its empty we get it from account
			//but first we need to get the service id
			$services = $client->messaging->v1->services->read();
			
			if( $services ){
				foreach($services as $service){
					$service_array = array(
						'sid' => $service->sid
					);
				}
			}
			//then we get phone numbers in account
			if( isset($service_array['sid']) ){
				$phoneNumbers = $client->messaging->v1->services($service_array['sid']) 
				->phoneNumbers->read();
				if( $phoneNumbers ){
					foreach($phoneNumbers as $phoneNumber){
						$phone_numbers_array = array(
							'from_phone_number' => $phoneNumber->phoneNumber
						);
					}
				}
				$from = $phone_numbers_array['from_phone_number'];
			}

		}
		
		//set body sms
		$msg = '';
		if( isset($data['msg']) 
			&& trim($data['msg']) != ''
		){
			//limit the msg to 1600 characters
			$msg = substr($data['msg'], 0, 1600);
		}
		if( !empty($data['send_to']) ){
			foreach($data['send_to'] as $k => $v){
				try{
					$message = $client->messages->create(
					  $v['mobile'], // Text this number
					  array(
						'from' => $from, // From a valid Twilio number
						'body' => $msg
					  )
					);
					if ( 0 != $current_user->ID ) {
						// Logged in.
						$meta_value = array(
							'date' => current_time( 'timestamp' ),
							'to_user_id' => $v['id'],
							'msg' => $message
						);
						//we add a log report to the current user login
						add_user_meta( get_current_user_id(), 'twilio_success_send_msg', $meta_value );
					} 
				}catch(Twilio\Exceptions\RestException $e){
					if ( 0 != $current_user->ID ) {
						// Logged in.
						$meta_value = array(
							'date' => current_time( 'timestamp' ),
							'to_user_id' => $v['id'],
							'msg' => $e->getMessage()
						);
						//we add a log report to the current user login
						add_user_meta( get_current_user_id(), 'twilio_error_send_msg', $meta_value );
					}
				}
			}
			return true;
		}
		return false;
	}
	
	public function __construct() {}

}
