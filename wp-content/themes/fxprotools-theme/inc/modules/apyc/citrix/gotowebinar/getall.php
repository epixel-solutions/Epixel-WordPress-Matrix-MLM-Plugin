<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * GotoWebinar - Get All Webinars
 https://goto-developer.logmeininc.com/content/gotowebinar-api-reference#!/Webinars/getAllWebinars
 *
 *
 * @since 3.12
 * @access (protected, public)
 * */
class Apyc_Citrix_GoToWebinar_GetAll{
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
	https://api.getgo.com/G2W/rest/organizers/<organizer_key>/webinars
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
	
	public function token(){
		return apyc_get_token();
	}
	
	/**
		Get History Webinars
		https://goto-developer.logmeininc.com/content/gotowebinar-api-reference#!/Webinars/getHistoricalWebinars
	**/
	public function getHistory(){
		if( $this->token() ){
			$url = $this->url . $this->token()->organizer_key . '/historicalWebinars';
			return $this->response($url);
		}
		return false;
	}
	
	/**
		Get All Webinars
		/organizers/{organizerKey}/webinars  
	**/
	public function getAll(){
		if( $this->token() ){
			$url = $this->url . $this->token()->organizer_key . '/webinars';
			return $this->response($url);
		}
		return false;
	}
	
	/**
		Get All Upcoming Webinars
		/organizers/{organizerKey}/upcomingWebinars  
	**/
	public function getUpcomingWebinars(){
		if( $this->token() ){
			$url = $this->url . $this->token()->organizer_key . '/upcomingWebinars';
			return $this->response($url);
		}
		return false;
	}
	
	public function get($webinarKey){
		if( $this->token() ){
			$url = $this->url . $this->token()->organizer_key . '/webinars/' . $webinarKey;
			return $this->response($url);
		}
		return false;
	}
	
	public function response($url){
		if( $this->token() && isset($this->token()->access_token) ){
			$body = array();
			$args = array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
					'Authorization' => $this->token()->access_token,
				),
			); 
			
			$response = wp_remote_get( $url, $args );
			if ( is_wp_error( $response ) ) {
			   $error_message = $response->get_error_message();
			   write_log('gotowebinar get webinars error : ' . $error_message);
			   throw new Exception( $error_message );
			} else {
				$response_code = wp_remote_retrieve_response_code( $response );
				$body_res = wp_remote_retrieve_body( $response );
				if( $response_code == 200 ){
					$body = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $body_res));
					return array(
						'status' => 200,
						'data' => $body
					);
				}else{
					$body = json_decode($body_res);
					//print_r($body);
					write_log($body);
					if( $response_code == 403 ){
						return array(
							'status' => 403,
							'msg' => isset($body->errorCode) ? $body->errorCode:'' . ' ' . isset($body->description) ? $body->description : ''
						);
					}
					return false;
				}
			}
		}
		return false;
	}
	
	public function cache($reset = false){
		$db_get = Apyc_Model_DBGotoWebinar::get_instance()->get_all_webinars('r');
		if( $reset || !$db_get ){
			//$webinars = $this->get();
			try{
				$webinars = Apyc_Citrix_GoToWebinar_GetAll::get_instance()->get();
				Apyc_Model_DBGotoWebinar::get_instance()->get_all_webinars('u', $webinars);
			}catch(Exception $e){
				write_log('get access token error : ' . $e->getMessage());
				return false;
			}
		}
		return Apyc_Model_DBGotoWebinar::get_instance()->get_all_webinars('r');
	}
	
	public function query($args = array()){
		$data = array();
		$defaults = array(
			'number_post' => 5,
			'filter_by_subject' => '',
			'get_webinar' => 'upcoming'
		);
		
		$query_args = wp_parse_args( $args, $defaults );

		switch($query_args['get_webinar']){
			case 'all':
				$get_data = $this->getAll();
			break;
			case 'history':
				$get_data = $this->getHistory();
			break;
			default:
			case 'upcoming':
				$get_data = $this->getUpcomingWebinars();
			break;
		}
		
		//$get_data = $this->cache();
		//dd($get_data);
		if( isset($get_data['data']) && $get_data['status'] == 200 ){
			$number_post = $query_args['number_post'] - 1;
			$i = 0;
			foreach($get_data['data'] as $k => $v){
				$subject = str_replace(' ', '', strtolower($v->subject));
				$filter_by_subject = str_replace(' ', '', strtolower($query_args['filter_by_subject']));
				
				$parse_data = array(
					'key' => $v->webinarKey,
					'startTime' => date("l, M.jS, h:i A T", strtotime($v->times[0]->startTime)),
					'title' => $v->subject,
					'description' => $v->description
				);
				
				if( trim($query_args['filter_by_subject']) != '' ){
					if( strcasecmp($subject,$filter_by_subject) == 0 ){
						//echo $i.$number_post.strcasecmp($subject,$filter_by_subject).'-'.$subject.'-'.$filter_by_subject.$_get_data_raw->webinarKey.'<br>';
						$data[] = array(
							'raw' => $v,
							'parse' => $parse_data
						);
						if( $i++ == $number_post) break;
					}
				}else{
					$data[] = array(
						'raw' => $v,
						'parse' => $parse_data
					);
					if( $i++ == $number_post) break;
				}
				
			}
			return $data;
		}
		return $data;
	}
	
	public function __construct() {}

}
