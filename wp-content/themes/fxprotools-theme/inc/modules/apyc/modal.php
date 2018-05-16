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
class Apyc_Modal{
	/**
	 * instance of this class
	 *
	 * @since 3.12
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;
	
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

	public function init(){
		$data = array();
		$current_user = wp_get_current_user();
		$data['current_user'] = false;
		if ( 0 != $current_user->ID ) {
			$data['current_user'] = $current_user;
		}

		//$data['webinars'] = Apyc_Citrix_GoToWebinar_GetAll::get_instance()->query();
		Apyc_View::get_instance()->view_theme(TEMPLATE_PATH . 'modal.php', $data);
	}
	
	public function get_webinars(){
		$data = array();

		$webinars = apyc_get_webinar_free();
		$data['webinars'] = $webinars;

		if( is_array($webinars)
			&& !empty($webinars)
		){
			Apyc_View::get_instance()->view_theme(TEMPLATE_PATH . 'modal-ajax-data.php', $data);
		}else{
			$ret = array(
				'status' => 'no-webinar',
				'msg' => 'No Webinar'
			);
			echo json_encode($ret);
		}
		wp_die();
	}
	
	/**
	* register webinar
	* https://goto-developer.logmeininc.com/content/gotowebinar-api-reference#!/Registrants/createRegistrant
	**/
	public function register_webinar(){
		$data = array();
		$post = $_POST;
		$error = false;
		$error_array = array();
		
		$data = json_decode($post['post_data'], true);
		parse_str($post['post_data'], $ajax);
		//dd($ajax);
		
		/*$full_name = explode(' ', $ajax['fullName']);
		$first_name = '';
		if( isset($full_name[0]) ){
			$first_name = $full_name[0];
		}
		
		$last_name = isset($full_name[1]) ? $full_name[1]:'';
		
		if( isset($ajax['fullName']) 
			&& trim($ajax['fullName']) == '' 
		){
			$error_array[] = _('Please dont leave name blank');
		}*/
		
		if( isset($ajax['firstName']) 
			&& trim($ajax['firstName']) == '' 
		){
			$error_array[] = _('Please dont leave first name blank');
		}
		if( isset($ajax['lastName']) 
			&& trim($ajax['lastName']) == '' 
		){
			$error_array[] = _('Please dont leave last name blank');
		}
		if( isset($ajax['email']) 
			&& trim($ajax['email']) == '' 
		){
			$error_array[] = _('Please dont leave email blank');
		}
		if( isset($ajax['phone']) 
			&& trim($ajax['phone']) == '' 
		){
			$error_array[] = _('Please dont leave phone blank');
		}
		//echo count($ajax['webinars']);
		if( !isset($ajax['webinars']) 
			&& count($ajax['webinars']) == 0
		){
			$error_array[] = _('Please choose webinar to join');
		}
		if( empty($error_array) 
			&& count($error_array) == 0 
		){
			$body_input = array(
				'lastName' => $ajax['lastName'],
				'firstName' => $ajax['firstName'],
				'phone' => isset($ajax['phone']) ? $ajax['phone']:'',
				'email' => isset($ajax['email']) ? $ajax['email']:''
			);
			$webinars_key = false;
			if( isset($ajax['webinars']) 
				&& !empty($ajax['webinars'])
			){
				$webinar_key = array();
				$webinar_ret = array();
				//print_r($body_input);
				foreach($ajax['webinars'] as $v){
					$webinar_key[] = $v;
					$ret = apyc_create_registrant($v, $body_input);
					$code = $ret['code'];
					switch($code){
						case 201:
							$msg_code = 'Success';
							pass_onboarding_checklist('scheduled_webinar');
						break;
						case 401:
							$msg_code = 'Bad Request';
						break;
						case 403:
							$msg_code = 'Forbidden';
						break;
						case 404:
							$msg_code = 'Not Found';
						break;
						case 409:
							$msg_code = 'The user is already registered';
						break;
						default:
							$msg_code = '';	
						break;
					}
					$webinar_ret[$v] = array(
						'data' => $ret,
						'code' => $code,
						'msg' => $msg_code
					);
				}
			}

			$ret = array(
				'status' => 'success',
				'msg' => _('Webinar Scheduled, please check your email for confirmation'),
				'data' => $ajax,
				'webinar_keys' => $webinar_key,
				'webinar_ret' => $webinar_ret
			);
		}else{
			$ret = array(
				'status' => 'error',
				'msg' => $error_array,
				'data' => $ajax
			);
		}

		echo json_encode($ret);
		wp_die();
	}
	
	public function equeue_scripts(){
		global $theme_version;
		wp_enqueue_script('modal-js-script', ASSETS_JS_PATH. 'modal.js', $theme_version);
	}
	
	public function __construct() {
		//add_action( 'wp_enqueue_scripts', array($this,'equeue_scripts') );
		add_action( 'wp_footer', array($this,'init') );
		add_action( 'wp_ajax_get_webinars', array($this, 'get_webinars') );
		add_action( 'wp_ajax_nopriv_get_webinars', array($this, 'get_webinars') );
		add_action( 'wp_ajax_register_webinars', array($this, 'register_webinar') );
		add_action( 'wp_ajax_nopriv_register_webinars', array($this, 'register_webinar') );
	}
}
