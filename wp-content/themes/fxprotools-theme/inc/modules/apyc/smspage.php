<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * This will auto created a page name 'sms'
 * @access (protected, public)
 * */
class Apyc_SMSPage{
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
	
	public $page_title = 'SMS';
	public $page_slug = 'sms';
	
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
	* Check if slug exists
	* @param	$post_name	string	name of the slug post
	* @return $wpdb->get_row()
	**/
	public function isSlugExists($post_name){
		global $wpdb;
		if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Create page
	* @see https://developer.wordpress.org/reference/functions/wp_insert_post/
	* @return wp_insert_post
	**/
	public function createPage(){
		$page_slug = $this->page_slug;
		$page_title = $this->page_title;
		$page_check = get_page_by_title($page_title);
		$page = array(
			'post_type' => 'page',
			'post_title' => $page_title,
			'post_status' => 'publish',
			'post_slug' => 'sms'
		);
		if(!isset($page_check->ID) && !$this->isSlugExists($page_slug)){
			return wp_insert_post($page);
		}
		return false;
	}
	
	public function __construct() {
		$this->createPage();
	}

}
