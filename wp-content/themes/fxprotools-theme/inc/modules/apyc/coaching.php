<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Coaching related class
 * /coaching/
 *
 *
 * @since 3.12
 * @access (protected, public)
 * */
class Apyc_Coaching{
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
	
	//get the upcoming webinars
	public function get_webinars(){
		$data = array();

		$webinars = apyc_get_upcoming_webinars();
		$data['webinars'] = $webinars;
		$data['table_heading_date'] = _('Date');
		$data['table_heading_time'] = _('Time');
		$data['table_heading_title'] = _('Title');
		$data['table_heading_join'] = _('Join Link');
		$data['insession_join_meeting'] = _('Join Meeting');
		$data['register_join_meeting'] = _('Meeting Link');
		
		if( is_array($webinars)
			&& !empty($webinars)
		){
			Apyc_View::get_instance()->view_theme(TEMPLATE_PATH . 'coaching/ajax-upcoming-webinars.php', $data);
		}else{
			$ret = array(
				'status' => 'no-webinar',
				'msg' => _('No Webinar')
			);
			echo json_encode($ret);
		}
		wp_die();
	}
	
	//get the history webinars
	public function get_history_webinars(){
		$data = array();

		$webinars = apyc_get_history_webinars();
		$data['webinars'] = $webinars;
		$data['table_heading_date'] = _('Date');
		$data['table_heading_time'] = _('Time');
		$data['table_heading_title'] = _('Title');
		$data['table_heading_join'] = _('Join Link');
		$data['insession_join_meeting'] = _('Join Meeting');
		$data['register_join_meeting'] = _('Meeting Link');
		
		if( is_array($webinars)
			&& !empty($webinars)
		){
			Apyc_View::get_instance()->view_theme(TEMPLATE_PATH . 'coaching/ajax-history-webinars.php', $data);
		}else{
			$ret = array(
				'status' => 'no-webinar',
				'msg' => _('No Webinar')
			);
			echo json_encode($ret);
		}
		wp_die();
	}
	
	//get private coaching
	public function get_private_coaching(){
		$data = array();
		$webinar_details = false;
		
		$current_user = wp_get_current_user();
		$current_user_id = $current_user->ID;
		$webinar_key = get_user_meta($current_user_id, 'webinar_key');
		//dd($webinar_key);
		if( $webinar_key ){
			$webinar_order_ids = array();
			$product_ids = array();
			foreach($webinar_key as $k => $v){
				$webinar_detail = $v . '_webinar_details';
				$get_webinar_detail = get_user_meta($current_user_id, $webinar_detail, 1);
				if( $get_webinar_detail ){
					$webinar_details[$v] = $get_webinar_detail;
				}
				$webinar_order_id = $v .'_order_id';
				$get_order_id = get_user_meta($current_user_id, $webinar_order_id, 1);
				if( $get_order_id ){
					$webinar_details[$v]['order_id'] = $get_order_id;
				}
				
				$webinar_product_id = $v .'_product_id';
				$get_product_id = get_user_meta($current_user_id, $webinar_product_id, 1);
				if( $get_product_id ){
					$webinar_details[$v]['product_id'] = $get_product_id;
				}
			}
		}
		//dd($webinar_details);
		//$webinars = apyc_get_history_webinars();
		$data['webinars'] = $webinar_details;
		$data['Apyc_Woo_CoachingTemplate'] = new Apyc_Woo_CoachingTemplate;
		$data['table_heading_date'] = _('Date');
		$data['table_heading_time'] = _('Time');
		$data['table_heading_title'] = _('Title');
		$data['table_heading_join'] = _('Join Link');
		$data['insession_join_meeting'] = _('Join Meeting');
		$data['register_join_meeting'] = _('Meeting Link');
		
		if( is_array($data['webinars'])
			&& !empty($data['webinars'])
		){
			Apyc_View::get_instance()->view_theme(TEMPLATE_PATH . 'coaching/ajax-private-coaching.php', $data);
		}else{
			$ret = array(
				'status' => 'no-webinar',
				'msg' => _('No Private Webinar')
			);
			echo json_encode($ret);
		}
		wp_die();
	}
	
	public function resched_modal(){
		$data = array();
		Apyc_View::get_instance()->view_theme(TEMPLATE_PATH . 'coaching/resched-modal.php', $data);
	}
	
	public function resched_webinar(){
		$data = array();
		$post = $_POST;
		$error = false;
		$error_array = array();
		
		$data = json_decode($post['post_data'], true);
		parse_str($post['post_data'], $ajax);
		dd($ajax);
		wp_die();
	}
	
	public function __construct() {
		add_action( 'wp_ajax_coach_get_webinars', array($this, 'get_webinars') );
		add_action( 'wp_ajax_nopriv_coach_get_webinars', array($this, 'get_webinars') );
		add_action( 'wp_ajax_coach_get_history_webinars', array($this, 'get_history_webinars') );
		add_action( 'wp_ajax_coach_get_private_coaching', array($this, 'get_private_coaching') );
		add_action( 'wp_footer', array($this,'resched_modal') );
		add_action( 'wp_ajax_resched_webinar', array($this, 'resched_webinar') );
	}
}
