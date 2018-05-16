<?php
/**
 * Set up LearnDash ProPanel
 *
 * @package LearnDash_ProPanel
 * @since 2.0
 */

final class LearnDash_ProPanel {

	/**
	 * @var LearnDash_ProPanel The reference to *Singleton* instance of this class
	 */
	private static $instance;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return LearnDash_ProPanel The *Singleton* instance.
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	  * Override class function for 'this'.
	  *
	  * This function handles out Singleton logic in 
	  * @return reference to current instance
	  */
	static function this() {
		return self::$instance;
	}

	/**
	 * LearnDash_ProPanel constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'reporting_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	function reporting_page() {
		$menu_user_cap = '';
		
		if ( LearnDash_Dependency_Check::get_instance()->check_dependency_results()) {
		
			if ( learndash_is_admin_user() ) 
				$menu_user_cap = LEARNDASH_ADMIN_CAPABILITY_CHECK;
			else if ( learndash_is_group_leader_user() ) 
				$menu_user_cap = LEARNDASH_GROUP_LEADER_CAPABILITY_CHECK;
		
			if ( !empty( $menu_user_cap ) ) {
			
				$r_page = add_submenu_page(
					null,
					esc_html__( 'ProPanel Reporting', 'ld_propanel' ),
					esc_html__( 'ProPanel Reporting', 'ld_propanel' ),
					$menu_user_cap,
					'propanel-reporting',
					array( $this->reporting_widget, 'full_reporting_page_output' )
				);

				// Found out the following is needed needed mainly for group leaders to be able to see the full page reporting screen. Not really needed for admin users.  
				global $_registered_pages;
				$_registered_pages['admin_page_propanel-reporting'] = true;
			}
		}
	}


	public function init() {
		$this->load_textdomain();
		$this->includes();
	}

	/**
	 * Notify user that LearnDash is required.
	 */
	public function notify_user_learndash_required() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php _e( 'LearnDash is required to be activated before LearnDash ProPanel can work properly.', 'ld_propanel' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Load ProPanel
	 */
	private function includes() {
		if ( LearnDash_Dependency_Check::get_instance()->check_dependency_results()) {
		
			if ( is_admin() ) {
				require_once LD_PP_PLUGIN_DIR . 'includes/class-ld-propanel-base-widget.php';
	
				require_once LD_PP_PLUGIN_DIR . 'includes/class-ld-propanel-overview.php';
				$this->overview_widget = new LearnDash_ProPanel_Overview();
	
				require_once LD_PP_PLUGIN_DIR . 'includes/class-ld-propanel-reporting.php';
				$this->reporting_widget = new LearnDash_ProPanel_Reporting();
	
				require_once LD_PP_PLUGIN_DIR . 'includes/class-ld-propanel-activity.php';
				$this->activity_widget = new LearnDash_ProPanel_Activity();

				require_once LD_PP_PLUGIN_DIR . 'includes/class-ld-propanel-progress-chart.php';
				$this->progress_chart_widget = new LearnDash_ProPanel_Progress_Chart();

				//require_once LD_PP_PLUGIN_DIR . 'includes/class-ld-propanel-trends.php';
				//$this->trends_widget = new LearnDash_ProPanel_Trends();

				require_once LD_PP_PLUGIN_DIR . 'includes/functions.php';
			} else {
				
				require_once LD_PP_PLUGIN_DIR . 'includes/class-ld-propanel-base-widget.php';

				require_once LD_PP_PLUGIN_DIR . 'includes/class-ld-propanel-activity.php';
				$this->activity_widget = new LearnDash_ProPanel_Activity();

				require_once LD_PP_PLUGIN_DIR . 'includes/functions.php';

				require_once LD_PP_PLUGIN_DIR . 'includes/class-ld-propanel-shortcodes.php';
				LearnDash_ProPanel_Shortcodes_Activity::get_instance();
			}
		
		}
	}

	/**
	 * Load ProPanel Text Domain
	 */
	private function load_textdomain() {
		load_plugin_textdomain( 'ld_propanel', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Register scripts for any widgets that may need to enqueue them.
	 */
	public function scripts( $force_load_scripts = false ) {
		if ( LearnDash_Dependency_Check::get_instance()->check_dependency_results()) {
				
			if ( is_admin() ) {
				$screen = get_current_screen();
				if ( in_array( $screen->id, array( 'dashboard', 'dashboard_page_propanel-reporting' ) ) ) {
					$force_load_scripts = true;
				}
			}

			if ( true === $force_load_scripts ) {

				$ld_script_prereq = array( 'jquery' );
			
				// For now these are only loaded on admin Dashboard
				if ( is_admin() ) {
					wp_register_script( 'ld-propanel-tablesorter', LD_PP_PLUGIN_URL . 'assets/js/vendor/jquery.tablesorter.combined.js', array(
						'jquery'
					) );
					$ld_script_prereq[] = 'ld-propanel-tablesorter';
				
					wp_register_script( 'ld-propanel-tablesorter-pager', LD_PP_PLUGIN_URL . 'assets/js/vendor/jquery.tablesorter.pager.js', array(
						'jquery',
						'ld-propanel-tablesorter'
					) );
					$ld_script_prereq[] = 'ld-propanel-tablesorter-pager';
				
					wp_register_script( 'ld-propanel-tablesorter-widget-output', LD_PP_PLUGIN_URL . 'assets/js/vendor/jquery.tablesorter.widget-output.js', array(
						'jquery',
						'ld-propanel-tablesorter',
					) );
					$ld_script_prereq[] = 'ld-propanel-tablesorter-widget-output';

					wp_register_script( 'ld-propanel-chartjs', LD_PP_PLUGIN_URL . 'assets/js/vendor/Chart.js', array(
						'jquery'
					) );
					$ld_script_prereq[] = 'ld-propanel-chartjs';

					wp_register_script( 'ld-propanel-select2', LD_PP_PLUGIN_URL . 'assets/js/vendor/select2.js', array(
						'jquery'
					) );
					$ld_script_prereq[] = 'ld-propanel-select2';
					wp_register_style( 'ld-propanel-select2', LD_PP_PLUGIN_URL . 'assets/css/vendor/select2.min.css' );
				}
			
				wp_register_script( 'ld-propanel', LD_PP_PLUGIN_URL . 'assets/js/ld-propanel.js', $ld_script_prereq );

				wp_localize_script( 'ld-propanel', 'ld_propanel', array( 
						'nonce' 	=> 	wp_create_nonce( 'ld-propanel' ), 
						'ajaxurl'	=>	admin_url( 'admin-ajax.php' )
					) 
				);
				wp_enqueue_script( 'ld-propanel' );

				wp_enqueue_style( 'dashicons' );
				wp_register_style( 'ld-propanel', LD_PP_PLUGIN_URL . 'assets/css/ld-propanel.css' );
				wp_enqueue_style( 'ld-propanel' );
			}
		}
	}
}