<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * GotoWebinar - Create Registrants
 https://goto-developer.logmeininc.com/content/gotowebinar-api-reference#!/Registrants/createRegistrant
 *
 *
 * @since 3.12
 * @access (protected, public)
 * */
class Apyc_Citrix_GoToWebinar_CreateRegistrant{
	/**
	 * instance of this class
	 *
	 * @since 3.12
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

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
	The url endpoint
	/organizers/{organizerKey}/webinars/{webinarKey}/registrants  
	**/
	protected $url = 'https://api.getgo.com/G2W/rest/organizers/';
	
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
		Create Registrants
		/organizers/{organizerKey}/webinars/{webinarKey}/registrants  
	**/
	public function create($webinarKey, $body = array()){
		global $wp_version;
		
		$token = apyc_get_token();
		if( $token 
			&& !empty($body)
		){
			$args = array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
					'Authorization' => $token->access_token,
				),
				'body' => json_encode($body),
			); 
			$url = $this->url . $token->organizer_key . '/webinars/' . $webinarKey . '/registrants';
			$response = wp_remote_post( $url, $args );
			//dd($response);
			if ( is_wp_error( $response ) ) {
			   $error_message = $response->get_error_message();
			   write_log('gotowebinar create registrar error : ' . $error_message);
			   throw new Exception( $error_message );
			} else {
				$response_code = wp_remote_retrieve_response_code( $response );
				if( $response_code == 200 || $response_code == 201 ){
					$body = json_decode( wp_remote_retrieve_body( $response ) );
					write_log('gotowebinar create registrar : ' . serialize($body));				
					return array(
						'code' => $response_code,
						'body' => $body
					);
				}else{
					write_log('gotowebinar create registrar error : ' . $body);
					return array(
						'code' => $response_code,
						'body' => $body
					);
				}
			}
		}
	}
	
	public function __construct() {}

}
