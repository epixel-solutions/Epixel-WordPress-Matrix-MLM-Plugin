<?php 
	/**
	 * -------------------------------------------------------------------------
	 * Plugin Name: EPS-Affiliates-E-Pin
	 * Plugin URI: https://epixelmlmsoftware.com
	 * Description: Affiliates E-pin configurations
	 * Author: EPIXEL SOLUTIONS
	 * Author URI: https://epixelsolutions.com
	 * Version: 1.0
	 * Text Domain: eps-affiliates-epin
	 * Domain Path: languages
	 * -------------------------------------------------------------------------
	 *
	 * EPS-Affiliates-E-pin is contains generate epin and for the epin.
	 *
	 * @package EPS-Affiliates-E-Pin
	 * @category Core
	 * @author < sufaid@epixelsolutions.com >
	 * @version 1.0
	 *
	 *
	 *
	 * This plugin needs the core plugin Eps-affiliates
	 * -------------------------------------------------------------------------
 */
	if ( ! defined( 'ABSPATH' ) ) exit;

	if ( ! class_exists( 'Eps_affiliates_epin' ) ) :

		register_activation_hook( __FILE__, 'parent_plugin_activate' );
		
    // Require parent plugin
		function parent_plugin_activate(){
    	if ( ! is_plugin_active( 'eps-affiliates/eps-affiliates.php' ) ) {
        // Stop activation redirect and show error
        wp_die('Sorry, but this plugin requires the Parent Plugin  <b>Eps affliates</b> to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    	}
		}
	/**
	 * -------------------------------------------------------------------------
	 * Main Eps_affiliates_epin Class
	 *
	 * SINGLETON OBJECT
	 * -------------------------------------------------------------------------
	 *
	 * @since 1.0
	 *
	*/
		final class Eps_affiliates_epin {
		/**
		 * -------------------------------------------------------------------------
		 * Eps_affiliates_e_pin instance.
		 * -------------------------------------------------------------------------
		 *
		 * @access private
		 * @since  1.0
		 * @var    Eps_plan The one true Eps_plan
		 *
		*/
			private static $instance;
		/**
		 * -------------------------------------------------------------------------
		 * Eps_affiliates_e_pin Version.
		 * -------------------------------------------------------------------------
		 *
		 * @access private
		 * @since  1.0
		 * @var    string
		 *
		*/
			private $version = '1.0';
			/**
		 * -------------------------------------------------------------------------
		 * Database migation version
		 * -------------------------------------------------------------------------
		 *
		 * @access public
		 * @since  1.0
		 *
	 	*/
			public $db_version = '1.0';
		/**
		 * -------------------------------------------------------------------------
		 * The capabilities (Permissions) class instance variable.
		 * -------------------------------------------------------------------------
		 *
		 * @access public
		 * @since  1.0
		 * @var    Eps_affiliates_e_pin_Capabilities
		 *
	 	*/
			public $capabilities;
		/**
		 * -------------------------------------------------------------------------
		 * 	Main Eps_affiliates_e_pin Instance
		 * -------------------------------------------------------------------------
		 *
		 * Insures that only one instance of Eps_affiliates_e_pin exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @uses Eps_affiliates_e_pin::setup_globals() Setup the globals needed
		 * @uses Eps_affiliates_e_pin::includes() Include the required files
		 * @uses Eps_affiliates_e_pin::setup_actions() Setup the hooks and actions
		 * @uses Eps_affiliates_e_pin::updater() Setup the plugin updater
		 * @return Eps_affiliates_e_pin
		 *
	  */
			public static function instance() {
				if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Eps_affiliates_e_pin ) ) {
					self::$instance = new Eps_affiliates_epin;

					if( version_compare( PHP_VERSION, '5.3', '<' ) ) {

						add_action( 'admin_notices', array( 'Eps_affiliates_e_pin', 'below_php_version_notice' ) );

						return self::$instance;

					}

					self::$instance->setup_constants();
					self::$instance->includes();

					add_action( 'plugins_loaded', array( self::$instance, 'setup_objects' ), -1 );
					add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
				}
				return self::$instance;
			}
		/**
		 * -------------------------------------------------------------------------
		 * Throw error on object clone
		 * -------------------------------------------------------------------------
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 *
	 */
			public function __clone() {
				// Cloning instances of the class is forbidden
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'eps-affiliates-epin' ), '1.0' );
			}
		/**
		 * -------------------------------------------------------------------------
		 * Disable unserializing of the class
		 * -------------------------------------------------------------------------
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 *
	 	*/
			public function __wakeup() {
				// Unserializing instances of the class is forbidden
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'eps-affiliates-epin' ), '1.0' );
			}
	 	/**
		 * -------------------------------------------------------------------------
		 * Show a warning to sites running PHP < 5.3
		 * -------------------------------------------------------------------------
		 * @static
		 * @access private
		 * @since 1.0
		 * @return void
	 	*/
			public static function below_php_version_notice() {
				echo '<div class="error"><p>' . __( 'Your version of PHP is below the minimum version of PHP required by Eps_affiliates. Please contact your host and request that your version be upgraded to 5.3 or later.', 'eps-affiliates' ) . '</p></div>';
			}

		/**
		 * -------------------------------------------------------------------------
	 	 * Setup plugin constants
		 * -------------------------------------------------------------------------
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 *
	 	*/
			private function setup_constants() {
				// Plugin version
				if ( ! defined( 'EPSAFFILIATE_EPIN_VERSION' ) ) {
					define( 'EPSAFFILIATE_EPIN_VERSION', $this->version );
				}
				// Plugin Folder Path
				if ( ! defined( 'EPSAFFILIATE_EPIN_PLUGIN_DIR' ) ) {
					define( 'EPSAFFILIATE_EPIN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
				}
				// Plugin Folder URL
				if ( ! defined( 'EPSAFFILIATE_EPIN_PLUGIN_URL' ) ) {
					define( 'EPSAFFILIATE_EPIN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
				}
				// Plugin Root File
				if ( ! defined( 'EPSAFFILIATE_EPIN_PLUGIN_FILE' ) ) {
					define( 'EPSAFFILIATE_EPIN_PLUGIN_FILE', __FILE__ );
				}
				// Make sure CAL_GREGORIAN is defined.
				if ( ! defined( 'CAL_GREGORIAN' ) ) {
					define( 'CAL_GREGORIAN', 1 );
				}				
				// data base migation version
				if ( ! defined( 'EPSAFFILIATE_EPIN_DB_VERSION' ) ) {
					define( 'EPSAFFILIATE_EPIN_DB_VERSION', $this->db_version );
				}
				if ( ! defined( 'EPSAFFILIATE_EPIN_PLUGIN_ASSETS' ) ) {
					define( 'EPSAFFILIATE_EPIN_PLUGIN_ASSETS', plugin_dir_url('eps-affiliates-epin/assets/js'));
				}
			}



		/**
		 * -------------------------------------------------------------------------
		 * Include required files for eps-affiliates-e-pin 
		 * -------------------------------------------------------------------------
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 *
	 	*/
			private function includes() {
				//enque scripts
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/class.enque-scripts.php';

				//all the hooks
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/epin-action-hooks.php';
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/action-hooks-callbacks.php';
				// Permissions
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'eps-affliates-epin-permission.php';
				// install file
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/epin-install.php';
				// epin table insert class file
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/epin-class-tables.php';
				
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/class-menus.php';
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/menu-callback/epin-configuration.callback.php';
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/menu-callback/generate-epin-callback.php';
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/menu-callback/my-epin-table-view.php';
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/menu-callback/all-epin-table-view.php';
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/epin-plugin.php';
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/menu-callback/epin-user-purchase.php';
				require_once EPSAFFILIATE_EPIN_PLUGIN_DIR . 'inc/menu-callback/epin-history-table-view.php';
				
			}
			public function setup_objects() {
				// self::$instance->capabilities   = new Eps_affiliates_Capabilities;
			}
			/**
		 * -------------------------------------------------------------------------
		 * Loads the plugin language files
		 * -------------------------------------------------------------------------
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 *
	 	*/
		 	public function load_textdomain() {

		 	}

		}
endif; // End if class_exists check
 /**
	* -------------------------------------------------------------------------
	* The main function responsible for returning the one true Eps_affiliates_e_pin
 	* Instance to functions everywhere.
 	*
 	* Use this function like you would a global variable, except without needing
 	* to declare the global.
 	*
 	* Example: <?php $eps_affiliates_e_pin = Eps_affiliates_e_pin(); ?>
 	*
 	* @since 1.0
 	* @return Eps_affiliates The one true Eps_affiliates Instance
 	*
*/
function eps_affiliates_e_pin() {
	return Eps_affiliates_epin::instance();

}



eps_affiliates_e_pin();
