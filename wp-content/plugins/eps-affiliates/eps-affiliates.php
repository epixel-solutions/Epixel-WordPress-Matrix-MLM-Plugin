<?php
	/**
	 * Plugin Name: EPS-Affiliates
	 * Plugin URI: https://epixelmlmsoftware.com
	 * Description: Affiliate plans and configurations of Epixelmlm for WordPress
	 * Author: EPIXEL SOLUTIONS
	 * Author URI: https://epixelsolutions.com
	 * Version: 1.0
	 * Text Domain: eps-affiliates
	 * Domain Path: languages
	 *
	 * EPS-Affiliates is contains over all functionalities of the site which uses the
	 * matrix plan.
	 *
	 * @package EPS-Affiliates
	 * @category Core
	 * @author < pratheesh@epixelsolutions.com >
	 * @version 1.0
 */

	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) exit;



	if ( ! class_exists( 'Eps_affiliates' ) ) :
	/**
	 * -------------------------------------------------------------------------
	 * Main Eps_affiliates Class
	 *
	 * SINGLETON OBJECT
	 * -------------------------------------------------------------------------
	 *
	 * @since 1.0
	 *
	*/
	final class Eps_affiliates {
		/**
		 * -------------------------------------------------------------------------
		 * Eps_affiliates instance.
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
		 * Eps_affiliates Version.
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
		 * Eps_affiliates Plan instance Variable.
		 * -------------------------------------------------------------------------
		 *
		 * @access private
		 * @since  1.0
		 * @var    string
		 *
		*/
			private $afl_plan;
		/**
		 * -------------------------------------------------------------------------
		 * The capabilities (Permissions) class instance variable.
		 * -------------------------------------------------------------------------
		 *
		 * @access public
		 * @since  1.0
		 * @var    Eps_affiliates_Capabilities
		 *
	 	*/
			public $capabilities;
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
		 * 	Main Eps_affiliates Instance
		 * -------------------------------------------------------------------------
		 *
		 * Insures that only one instance of Eps_affiliates exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @uses Eps_affiliates::setup_globals() Setup the globals needed
		 * @uses Eps_affiliates::includes() Include the required files
		 * @uses Eps_affiliates::setup_actions() Setup the hooks and actions
		 * @uses Eps_affiliates::updater() Setup the plugin updater
		 * @return Eps_affiliates
		 *
	  */
			public static function instance() {
				if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Eps_affiliates ) ) {
					self::$instance = new Eps_affiliates;

					if( version_compare( PHP_VERSION, '5.3', '<' ) ) {

						add_action( 'admin_notices', array( 'Eps_affiliates', 'below_php_version_notice' ) );

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
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'eps-affiliates' ), '1.0' );
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
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'eps-affiliates' ), '1.0' );
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
				if ( ! defined( 'EPSAFFILIATE_VERSION' ) ) {
					define( 'EPSAFFILIATE_VERSION', $this->version );
				}

				// data base migation version
				if ( ! defined( 'EPSAFFILIATE_DB_VERSION' ) ) {
					define( 'EPSAFFILIATE_DB_VERSION', $this->db_version );
				}

				// Plugin Folder Path
				if ( ! defined( 'EPSAFFILIATE_PLUGIN_DIR' ) ) {
					define( 'EPSAFFILIATE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
				}

				// Plugin Folder URL
				if ( ! defined( 'EPSAFFILIATE_PLUGIN_URL' ) ) {
					define( 'EPSAFFILIATE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
				}

				// Plugin Root File
				if ( ! defined( 'EPSAFFILIATE_PLUGIN_FILE' ) ) {
					define( 'EPSAFFILIATE_PLUGIN_FILE', __FILE__ );
				}

				// Plugin Assets path
				if ( ! defined( 'EPSAFFILIATE_PLUGIN_ASSETS' ) ) {
					define( 'EPSAFFILIATE_PLUGIN_ASSETS', plugin_dir_url('eps-affiliates/assets/css',__FILE__));
				}

				//Affiliates plan path
				if ( ! defined( 'EPSAFFILIATE_PLUGIN_PLAN' ) ) {
					define( 'EPSAFFILIATE_PLUGIN_PLAN', plugin_dir_url('eps-affiliates/inc/plan/matrix',__FILE__));
				}

				// Make sure CAL_GREGORIAN is defined.
				if ( ! defined( 'CAL_GREGORIAN' ) ) {
					define( 'CAL_GREGORIAN', 1 );
				}
				//define variables for log messages
				
				//Critical conditions
				if ( ! defined( 'LOGS_CRITICAL' ) ) {
					define( 'LOGS_CRITICAL',0);
				}
				//Error conditions
				if ( ! defined( 'LOGS_ERROR' ) ) {
					define( 'LOGS_ERROR',1);
				}
				//Warning conditions
				if ( ! defined( 'LOGS_WARNING' ) ) {
					define( 'LOGS_WARNING',2);
				}
				//(default) Normal but significant conditions.
				if ( ! defined( 'LOGS_NOTICE' ) ) {
					define( 'LOGS_NOTICE',3);
				}
				//Informational messages
				if ( ! defined( 'LOGS_INFO' ) ) {
					define( 'LOGS_INFO',4);
				}
				//Debug-level messages
				if ( ! defined( 'LOGS_DEBUG' ) ) {
					define( 'LOGS_DEBUG',5);
				}

			}

		/**
		 * -------------------------------------------------------------------------
		 * Include required files for eps-affiliates plans
		 * -------------------------------------------------------------------------
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 *
	 	*/
			private function includes() {
			ob_start();

			/*
			 * -----------------------------------------------------------------------------------------------
			 * Enque the styles and scripts
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class.enque-scripts.php';
			
			/* 
			 * -----------------------------------------------------------------------------------------------
			 * Here load library files
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'libraries/Pagination.php';
			/* 
			 * -----------------------------------------------------------------------------------------------
			 * Common plugin needed functions
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class.common.php';

				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/eps-queue-processing-functions.php';
				
				//common functions callback
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/eps-functions.php';

			/* 
			 * -----------------------------------------------------------------------------------------------
			 * custom permissions for menu access, this will listed in the custom permission table
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'eps-permissions.php';

			

				//query variables
				// require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class-query.php';

			/* 
			 * -----------------------------------------------------------------------------------------------
			 * required conditions when install 
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/install.php';

				//required conditions when un-install
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/un_install.php';

				//common Payment functions
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/afl_payment_funs.php';

				//route
				//require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class-route-url.php';

			/* 
			 * -----------------------------------------------------------------------------------------------
			 * wordpress action hook
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/action-hooks.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/action-hooks-global-callbacks.php';
			/* 
			 * -----------------------------------------------------------------------------------------------
			 * afl dashboard menus registration
			 * -----------------------------------------------------------------------------------------------
			*/
				// require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class-dashboard-menus.php';

			/* 
			 * -----------------------------------------------------------------------------------------------
			 * Wordpress permissions
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class-capabilities.php';

			/* 
			 * -----------------------------------------------------------------------------------------------
			 * admin menus
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/class-menu.php';

			/* 
			 * -----------------------------------------------------------------------------------------------
			 * Here comes all the admin menu callback functions
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-advanced-conf.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-compensation-plan-conf.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-roles-nd-permission-conf.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-rank-conf.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-pool-bonus-conf.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-genealogy-configurations.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-payout-conf.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-business-system-members.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-manage-members.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-blocked-members.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-find-members.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-variable-configuration.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-features-and-config-settings.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-business-profit-report.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-advanced-queue-conf.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-recent-log-messages.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-bonus-summary-report.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-shortcodes-demo.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-processing-queue.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-incentive-history-report.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-business-holding-payouts.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-pagination-addedd-pages-conf.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-afl-overall-purchase.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-all-customers.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-rank-history-report.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-check-rank-condition.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-business-transactions.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-check-matrix-compensation-days.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-db-migration.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-holding-users-list.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-execute-php-code.php';
				//require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/mater.updations.php';
				//require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-cron-lock-data.php';
				//require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menus-master-tables.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-free-account-user-list.php';

			/* 
			 * -----------------------------------------------------------------------------------------------
			 * common files callback
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class.common.php';

			/* 
			 * -----------------------------------------------------------------------------------------------
			 * install tables
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class.tables.php';

			/* 
			 * -----------------------------------------------------------------------------------------------
			 * eps-afl-dashboard menus templates
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/eps-template-functions.php';
				//database 
				
			/* 
			 * -----------------------------------------------------------------------------------------------
			 * page function
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/eps-page-functions.php';
			/* 
			 * -----------------------------------------------------------------------------------------------
			 * ajax callbacks
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/eps-ajax-callbacks.php';
			/* 
			 * -----------------------------------------------------------------------------------------------
			 * member registration
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class-eps-affiliates-registration.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class-eps-affiliates-unilevel-registration.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class-eps-affiliates-customer-registration.php';
			/* 
			 * -----------------------------------------------------------------------------------------------
			 * Here comes all the member menu callback
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-add-new-member-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-add-new-customer-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-genealogy-tree-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-downline-members-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-network-exporer-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-network-holding-tank-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-refered-members-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-rank-performance-overview.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-team-purchases-overview.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-payment-methods-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-my-customers.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-holding-tank-toggle-placement.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-holding-genealogy-toggle-placement.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-direct-uplines-tree.php';

				//unilevel
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/unilevel/menu-add-new-member-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/unilevel/menu-add-new-customer-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/unilevel/menu-genealogy-tree-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/unilevel/menu-downline-members-callback.php';
				// require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/unilevel/menu-network-exporer-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/unilevel/menu-network-holding-tank-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/unilevel/menu-refered-members-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/unilevel/menu-my-customers.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/unilevel/menu-unilevel-holding-genealogy-toggle-placement.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/unilevel/menu-direct-uplines-tree.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/menu_callback/menu-my-purhcases.php';

			/* 
			 * -----------------------------------------------------------------------------------------------
			 * dashboard widgets
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/eps-dashboard-widgets.php';
      /* 
			 * -----------------------------------------------------------------------------------------------
			 * Menu Callback for ewallet transactions
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/e-wallet/eps-ewallet-ajax-callbacks.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/e-wallet/menu_callback/menu-ewallet-summary-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/e-wallet/menu_callback/menu-ewallet-withdraw-fund.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/e-wallet/menu_callback/menu-ewallet-my-withdraw-requests.php';


				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/b-wallet/eps-bwallet-ajax-callbacks.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/b-wallet/menu-callback/menu-bwallet-callback.php';

				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/eps-functions.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/eps-customer-functions.php';

				//integrations
				// require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/integrations/class-woocommerce.php';


			/* 
			 * -----------------------------------------------------------------------------------------------
			 * Actions, Short codes and schedulres
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/action-shortcodes.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/action-shortcodes-callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/action-schedulers.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/action-hooks-global.php';
			/* 
			 * -----------------------------------------------------------------------------------------------
			 * Testing codes
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/test.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/test-codes.php';
			/* 
			 * -----------------------------------------------------------------------------------------------
			 * Menu callback for payout
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/payout/menu-callback/menu-payout-withdrawal-requests.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/payout/withdraw-request-datatable.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/payout/menu-callback/menu-payout-in-remitance.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/payout/payout-in-remitance-datatable.php';
				
			/* 
			 * -----------------------------------------------------------------------------------------------
			 * data tables
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/e-wallet/eps_user_ewallet_my_withdrawal_datatable.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/member/datatables/class-team-purchases-data-table.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/datatables/class-manage-members-datatable.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/datatables/class-blocked-members-datatable.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/datatables/class-find-members-datatable.php';
				
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/eps-table-filters.php';



			/*
			 * -----------------------------------------------------------------------------------------------
			 * API
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/API/menu_callback/menu-user-remote-get.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/API/api-ajax-callbacks.php';
				// require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/API/eps-remote-users-background-process.php';

			/*
			 * -----------------------------------------------------------------------------------------------
			 * Individual status functions
			 * -----------------------------------------------------------------------------------------------
			*/
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/eps-individual-status-functions.php';



				/**
				 *
				 * Hyper Wallet Functions
				 *
				 */

				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/hyper_wallet/menu-callback/hyper_wallet_menu_callback.php';
				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/hyper_wallet/eps-hyper-wallet.php';


 				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/admin/menu_callback/menu-add-imported-users.php';
			/*
			 * -----------------------------------------------------------------------------------------------
			 * database transaction functions 
			 * -----------------------------------------------------------------------------------------------
			*/
 				require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/class.db_transaction.php';


			}
			
		/**
		 * -------------------------------------------------------------------------
		 * Setup all objects
		 * -------------------------------------------------------------------------
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 *
	 	*/
			public function setup_objects() {
				self::$instance->capabilities   = new Eps_affiliates_Capabilities;
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
	* The main function responsible for returning the one true Eps_affiliates
 	* Instance to functions everywhere.
 	*
 	* Use this function like you would a global variable, except without needing
 	* to declare the global.
 	*
 	* Example: <?php $eps_affiliate = eps_affiliate(); ?>
 	*
 	* @since 1.0
 	* @return Eps_affiliates The one true Eps_affiliates Instance
 	*
*/
function eps_affiliate() {
	return Eps_affiliates::instance();
}
/*
 * -------------------------------------------------------------------------
 * Custom print function
 * -------------------------------------------------------------------------
*/
	if (!function_exists('pr')) :
		function pr($data = array(), $ex = FALSE){
			echo '<pre>';
			print_r($data);
			echo '</pre>';
			if ($ex){
				exit();
			}
		}
	endif;
eps_affiliate();
