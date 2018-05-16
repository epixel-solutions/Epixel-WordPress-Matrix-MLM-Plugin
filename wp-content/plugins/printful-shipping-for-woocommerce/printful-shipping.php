<?php
/**
Plugin Name: Printful Integration for WooCommerce
Plugin URI: https://wordpress.org/plugins/printful-shipping-for-woocommerce/
Description: Calculate correct shipping and tax rates for your Printful-Woocommerce integration.
Version: 2.0.1
Author: Printful
Author URI: http://www.printful.com
License: GPL2 http://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 3.0.0
WC tested up to: 3.2.0
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class Printful_Base {

    const VERSION = '2.0.1';
	const PF_HOST = 'https://www.printful.com/';
	const PF_API_HOST = 'https://api.printful.com/';

    /**
     * Construct the plugin.
     */
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    /**
     * Initialize the plugin.
     */
    public function init() {

        if (!class_exists('WC_Integration')) {
            return;
        }

	    //Register API endpoint
	    add_filter('woocommerce_api_classes', array($this, 'add_api_resource'));

	    //Add settings to WooCommerce Index response (legacy - v2)
	    add_filter('woocommerce_api_index', array($this, 'add_extra_info_to_api_index'));

        //load required classes
	    require_once 'includes/class-printful-integration.php';
	    require_once 'includes/class-printful-carriers.php';
	    require_once 'includes/class-printful-taxes.php';
	    require_once 'includes/class-printful-shipping.php';
	    require_once 'includes/class-printful-request-log.php';
	    require_once 'includes/class-printful-admin.php';
	    require_once 'includes/class-printful-admin-dashboard.php';
	    require_once 'includes/class-printful-admin-settings.php';
	    require_once 'includes/class-printful-admin-status.php';
	    require_once 'includes/class-printful-admin-support.php';
	    require_once 'includes/class-printful-size-chart-tab.php';
	    require_once 'includes/class-printful-size-chart-tab.php';

	    //launch init
	    Printful_Taxes::init();
	    Printful_Shipping::init();
	    Printful_Request_log::init();
	    Printful_Admin::init();
	    Printful_Size_Chart_Tab::init();

	    //hook ajax callbacks
	    add_action( 'wp_ajax_save_printful_settings', array( 'Printful_Admin_Settings', 'save_printful_settings' ) );
	    add_action( 'wp_ajax_ajax_force_check_connect_status', array( 'Printful_Integration', 'ajax_force_check_connect_status' ) );
	    add_action( 'wp_ajax_get_printful_stats', array( 'Printful_Admin_Dashboard', 'render_stats_ajax' ) );
	    add_action( 'wp_ajax_get_printful_orders', array( 'Printful_Admin_Dashboard', 'render_orders_ajax' ) );
	    add_action( 'wp_ajax_get_printful_status_checklist', array( 'Printful_Admin_Status', 'render_status_table_ajax' ) );
	    add_action( 'wp_ajax_get_printful_status_report', array( 'Printful_Admin_Support', 'render_status_report_ajax' ) );
	    add_action( 'wp_ajax_get_printful_carriers', array( 'Printful_Admin_Settings', 'render_carriers_ajax' ) );
    }

	/**
	 * Added API endpoints
	 * @param $endpoints
	 * @return array
	 */
    public function add_api_resource($endpoints) {
        require_once 'includes/class-printful-api-resource.php';
        $endpoints[]= 'Printful_API_Resource';
        return $endpoints;
    }

	/**
	 * @param $available
	 * Include plugin version in WC API Index
	 * @return mixed
	 */
    public static function add_extra_info_to_api_index($available) {
	    $available['printful_plugin_version'] = self::VERSION;
	    $available['locale'] = get_locale();
    	return $available;
    }

	/**
	 * @return string
	 */
    public static function get_asset_url() {
		return trailingslashit(plugin_dir_url(__FILE__)) . 'assets/';
    }

    /**
	 * @return string
	 */
	public static function get_printful_host() {
		if ( defined( 'PF_DEV_HOST' ) ) {
			return PF_DEV_HOST;
		}

		return self::PF_HOST;
	}

	/**
	 * @return string
	 */
	public static function get_printful_api_host() {
		if ( defined( 'PF_DEV_API_HOST' ) ) {
			return PF_DEV_API_HOST;
		}

		return self::PF_API_HOST;
	}

}

new Printful_Base();    //let's go