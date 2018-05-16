<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Create a product type GotoWebinar Appointment
 *
 *
 * @since 3.12
 * @access (protected, public)
 * */
class Apyc_Woo_Webinar{
	/**
	 * instance of this class
	 *
	 * @since 3.12
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;
	
	protected $product_type_key = 'apyc_woo_gotowebinar_appointment';
	protected $product_type_label = 'GotoWebinar Appointment';
	
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
	
	public function addCustomProductType($types){
		$types[ $this->product_type_key ] = __( $this->product_type_label );
		return $types;
	}
	
	public function addCustomSettings(){
		global $woocommerce, $post;
		$data = array();
		$data['product_type_key'] = $this->product_type_key;
		$data['woocommerce'] = $woocommerce;
		$data['post'] = $post;
		
		Apyc_View::get_instance()->view_theme(TEMPLATE_PATH . 'coaching/woo/data-type-custom-fields.php', $data);
	}
	
	public function enqueue_scripts(){
		global $theme_version;
		wp_enqueue_script('woo-gotowebinar-js-script', ASSETS_ADMIN_JS_PATH. 'admin-woo-gotowebinar.js', $theme_version);
	}
	
	public function save_custom_settings($post_id){

		$_woogotowebinar_scheduling_window_num = $_POST['_woogotowebinar_scheduling_window_num'];
		if( !empty( $_woogotowebinar_scheduling_window_num ) )
		update_post_meta( $post_id, '_woogotowebinar_scheduling_window_num', esc_attr( $_woogotowebinar_scheduling_window_num) );
		
		$_woogotowebinar_scheduling_window_date = $_POST['_woogotowebinar_scheduling_window_date'];
		if( !empty( $_woogotowebinar_scheduling_window_date ) )
		update_post_meta( $post_id, '_woogotowebinar_scheduling_window_date', esc_attr( $_woogotowebinar_scheduling_window_date) );
		
		$_woogotowebinar_range_time_from = $_POST['_woogotowebinar_range_time_from'];
		if( !empty( $_woogotowebinar_range_time_from ) )
		update_post_meta( $post_id, '_woogotowebinar_range_time_from', esc_attr( $_woogotowebinar_range_time_from) );

		$_woogotowebinar_range_time_from_meridiem = $_POST['_woogotowebinar_range_time_from_meridiem'];
		if( !empty( $_woogotowebinar_range_time_from_meridiem ) )
		update_post_meta( $post_id, '_woogotowebinar_range_time_from_meridiem', esc_attr( $_woogotowebinar_range_time_from_meridiem) );

		$_woogotowebinar_range_time_to = $_POST['_woogotowebinar_range_time_to'];
		if( !empty( $_woogotowebinar_range_time_to ) )
		update_post_meta( $post_id, '_woogotowebinar_range_time_to', esc_attr( $_woogotowebinar_range_time_to) );

		$_woogotowebinar_range_time_to_meridiem = $_POST['_woogotowebinar_range_time_to_meridiem'];
		if( !empty( $_woogotowebinar_range_time_to_meridiem ) )
		update_post_meta( $post_id, '_woogotowebinar_range_time_to_meridiem', esc_attr($_woogotowebinar_range_time_to_meridiem) );

	}
		
	public function __construct() {
		add_filter( 'product_type_selector', array($this, 'addCustomProductType') );
		add_action( 'woocommerce_product_options_general_product_data', array($this, 'addCustomSettings') );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') );
		add_action( 'woocommerce_process_product_meta', array($this,'save_custom_settings') );
	}
}
