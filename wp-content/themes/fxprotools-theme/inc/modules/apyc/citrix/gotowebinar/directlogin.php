<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * GotoWebinar Direct Login
 *
 *
 * @since 3.12
 * @access (protected, public)
 * */
class Apyc_Citrix_GoToWebinar_DirectLogin{
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
	**/
	protected $url = 'https://api.citrixonline.com/oauth/access_token';
	
	/**
	**/
	protected $grant_type = 'password';
	
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
	We will get the access token, which is valid for 356 days
	**/
	public function login(){
		$url = $this->url 
			. '?grant_type=' . $this->grant_type 
			. '&user_id=' . GOTOWEBINAR_USERID 
			. '&password='. GOTOWEBINAR_PASSWORD 
			. '&client_id=' . GOTOWEBINAR_CONSUMERKEY;
		$response = wp_remote_get( $url );
		if ( is_wp_error( $response ) ) {
		   $error_message = $response->get_error_message();
		   throw new Exception( $error_message );
		} else {
			$response_code = wp_remote_retrieve_response_code( $response );
			$body = json_decode( wp_remote_retrieve_body( $response ) );
			if( $response_code != 200 ){
				throw new Exception( isset($body->int_err_code) ? $body->int_err_code :'' . ' - ' . isset($body->msg) ? $body->msg:'' );
			}
			return $body;
		}
	}
	
	public function __construct() {}

}
