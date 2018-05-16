<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Printful_Admin_Status {

	const PF_STATUS_OK = 1;
	const PF_STATUS_WARNING = 0;
	const PF_STATUS_FAIL = -1;

	const API_KEY_SEARCH_STRING = 'Printful';
	const PF_WEBHOOK_NAME = 'Printful Integration';
	const PF_REMOTE_REQUEST_URL = 'webhook/woocommerce?store=1';
	const PF_REMOTE_REQUEST_TOPIC = 'woo.plugin.test';
	const PF_STATUS_ISSUE_COUNT = 'printful_status_issue_count';
	const PF_CACHED_CHECKLIST = 'printful_cached_checklist';

    public static $_instance;
	public static $checklist_items = array(
		array(
			'name'        => 'Connection to Printful API',
			'description' => 'Is your store successfully connected to Printful API?',
			'method'      => 'check_PF_API_connect',
		),
		array(
			'name'        => 'Printful API key is set',
			'description' => 'Your store needs access to Printful API to use most of it\'s features like shipping rates, tax rates and other settings.',
			'method'      => 'check_PF_API_key',
		),
		array(
			'name'        => 'WordPress Permalinks',
			'description' => 'WooCommerce API will not work unless your permalinks in Settings > Permalinks are set up correctly. Make sure you that they are NOT set to "plain".',
			'method'      => 'check_permalinks',
		),
		array(
			'name'        => 'WordPress version',
			'description' => 'WordPress should always be updated to the latest version. Updates can be installed from your WordPress admin dashboard.',
			'method'      => 'check_WP_version',
		),
		array(
			'name'        => 'WooCommerce API enabled',
			'description' => 'Your site needs to enbable WooCommerce API so that Printful can connect to it.',
			'method'      => 'check_WC_API_enabled',
		),
		array(
			'name'        => 'WooCommerce Webhooks',
			'description' => 'Printful requires WooCommerce webhooks to be set up to quickly capture you incoming orders, products updates etc.',
			'method'      => 'check_PF_webhooks',
		),
		array(
			'name'        => 'WooCommerce API keys are set',
			'description' => 'Printful needs access to your WooCommerce API for the integration to work - otherwise we can\'t sync your store, push or pull your products etc.',
			'method'      => 'check_WC_API_access',
		),
		array(
			'name'        => 'WooCommerce authentication URL access',
			'description' => 'Some hosts have unnecessarily intrusive security checks in place that prevent WooCommerce API authentication from working (mod_security rule #1234234). If this check fails, you will not be able authorize Printful app.',
			'method'      => 'check_WC_auth_url_access',
		),
		array(
			'name'        => 'WordPress remote requests',
			'description' => 'WordPress needs to be able to connect to Printful server to call webhooks. If this check fails, contact your hosting support.',
			'method'      => 'check_remote_requests',
		),
		array(
			'name'        => 'Recent store sync errors',
			'description' => 'Printful will connect to your store\'s API regularly and sync your latest products, orders etc. If there have been any recent issues with sync, this check will fail.',
			'method'      => 'check_PF_sync_errors',
		),
		array(
			'name'        => 'Write permissions',
			'description' => 'Make the uploads directory writable. This is required for mockup generator product push to work correctly. Contact your hosting provider if you need help with this.',
			'method'      => 'check_uploads_write',
		),
		array(
			'name'        => 'PHP memory limit',
			'description' => 'Set PHP allocated memory limit to at least 128mb. Contact your hosting provider if you need help with this.',
			'method'      => 'check_PHP_memory_limit',
		),
		array(
			'name'        => 'PHP script time limit',
			'description' => 'Set PHP script execution time limit to at least 30 seconds. This is required to successfully push products with many variants. Contact your hosting provider if you need help with this.',
			'method'      => 'check_PHP_time_limit',
		),
//		array(
//			'name'        => 'Error logs',
//			'description' => 'Your WordPress site needs to have it\'s error logging set up correctly so in case of issues you can figure out what\'s wrong. Note: your hosting might already have an independent log from WordPress.',
//			'method'      => 'check_WP_error_logs',
//		),
		array(
			'name'        => 'W3 Total Cache DB Cache',
			'description' => 'If you are using W3 Total Cache, the database caching feature needs to be disabled since it can cause issues with product push to store.',
			'method'      => 'check_W3_db_cache',
			'silent'      => true,
		),
		array(
			'name'        => 'WP SpamShield',
			'description' => 'If you are using WP SpamShield, you might experiance problems connecting to Printful and pushing products.',
			'method'      => 'check_wp_spamshield',
			'silent'      => true,
		),
		array(
			'name'        => 'Remove Print Aura plugin',
			'description' => 'Print Aura plugin is known to cause issues so it needs to be removed.',
			'method'      => 'check_printaura_plugin',
			'silent'      => true,
		),
	);

    /**
     * @return Printful_Admin_Status
     */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Setup the view variables
	 */
	public static function view() {

		$status = self::instance();
		$status->render();
	}

	/**
	 * Render the view
	 */
	public function render() {

		Printful_Admin::load_template( 'header', array( 'tabs' => Printful_Admin::get_tabs() ) );

		$checklist = self::get_checklist( true );
		if ( $checklist ) {
			Printful_Admin::load_template( 'status-table', array( 'checklist' => $checklist ) );
		} else {
			Printful_Admin::load_template( 'ajax-loader', array( 'action' => 'get_printful_status_checklist', 'message' => 'Testing your store (this may take up to 30 seconds)...' ) );
		}

		Printful_Admin::load_template( 'footer' );
	}

	/**
	 * Build the content for status page
	 */
	public static function render_status_table_ajax() {

		$checklist = self::get_checklist();
		Printful_Admin::load_template( 'status-table', array( 'checklist' => $checklist ) );

		exit;
	}

	/**
	 * Run the tests
	 * @param bool $only_cached_results
	 * @return array
	 */
	public static function get_checklist($only_cached_results = false) {

		$status = self::instance();

		$list = get_transient(Printful_Admin_Status::PF_CACHED_CHECKLIST);

		if ( $only_cached_results || $list ) {
			return $list;
		}

		$list                   = array();
		$list['overall_status'] = true;
		$issueCount             = 0;

		foreach ( self::$checklist_items as $item ) {
			$list_item                = array();
			$list_item['name']        = $item['name'];
			$list_item['description'] = $item['description'];

			if ( method_exists( $status, $item['method'] ) ) {
				$list_item['status'] = $status->{$item['method']}();

				if ( $status->should_result_be_visible( $list_item['status'], $item ) ) {
					$list['items'][] = $list_item;
				}

				if ( $list_item['status'] == self::PF_STATUS_FAIL) {
					$list['overall_status'] = false;
					$issueCount ++;
				}
			}
		}

		set_transient( Printful_Admin_Status::PF_CACHED_CHECKLIST, $list, MINUTE_IN_SECONDS );
		set_transient( Printful_Admin_Status::PF_STATUS_ISSUE_COUNT, $issueCount, HOUR_IN_SECONDS );

		return $list;
	}

	/**
	 * Execute only one test
	 * @param $method
	 * @return mixed
	 */
	public function run_single_test( $method ) {
		if ( method_exists( $this, $method ) ) {
			return $this->{$method}();
		}
		return false;
	}

	/**
	 * @param $status
	 * @param bool $item
	 *
	 * @return int
	 */
	private function should_result_be_visible( $status, $item = false ) {

		if ( ! isset( $item['silent'] ) || ( $item['silent'] === true && $status === self::PF_STATUS_FAIL ) ) {   //silent items are only shown on FAIL
			return true;
		}

		return false;
	}

    /**
     * Function for checking if thumbnails are resized
     */
	private function check_uploads_write() {

		$upload_dir = wp_upload_dir();
		if ( is_writable( $upload_dir['basedir'] ) ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}

	/**
	 * @return int
	 */
	private function check_PHP_memory_limit() {

		$memory_limit = ini_get( 'memory_limit' );

		if ( preg_match( '/^(\d+)(.)$/', $memory_limit, $matches ) ) {
			if ( $matches[2] == 'M' ) {
				$memory_limit = $matches[1] * 1024 * 1024; // nnnM -> nnn MB
			} else if ( $matches[2] == 'K' ) {
				$memory_limit = $matches[1] * 1024; // nnnK -> nnn KB
			}
		}

		$ok = ( $memory_limit >= 128 * 1024 * 1024 ); // at least 128M?

		if ( $ok ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}

	/**
	 * @return int
	 */
	private function check_WP_version() {

		$current = get_bloginfo( 'version' );

		try {
			$url      = 'https://api.wordpress.org/core/version-check/1.7/';
			$response = wp_remote_get( $url );

			if ( ! is_wp_error( $response ) ) {
				$json = $response['body'];
				$obj  = json_decode( $json );
			}

			if ( empty( $obj ) ) {
				return self::PF_STATUS_FAIL;
			}

			$version = $obj->offers[0];
			$latest  = $version->version;

		} catch ( Exception $e ) {
			return self::PF_STATUS_FAIL;
		}

		if ( ! $latest ) {
			return self::PF_STATUS_FAIL;
		}

		if ( version_compare( $current, $latest, '==' ) ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}

	/**
	 * @return int
	 */
	private function check_PF_webhooks() {

		// Query args
		$args = array(
			'post_type'           => 'shop_webhook',
			'nopaging'            => true,
			'ignore_sticky_posts' => true,
			's'                   => self::PF_WEBHOOK_NAME,
			'post_status'         => 'published',
		);

		// Get the webhooks
		$webhook_results = new WP_Query( $args );
		$webhooks        = $webhook_results->posts;

		if ( count( $webhooks ) > 0 ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}

	/**
	 * @return int
	 */
	private function check_WC_API_access() {

		global $wpdb;

		//if any keys are set
		$query  = "SELECT COUNT(*) as key_count FROM {$wpdb->prefix}woocommerce_api_keys";
		$count    = $wpdb->get_var( $query );

		if ( $count == 0 ) {
			return self::PF_STATUS_FAIL;
		}

		// Get the API key with matching description
		$search = "AND description LIKE '%" . esc_sql( $wpdb->esc_like( wc_clean( self::API_KEY_SEARCH_STRING ) ) ) . "%' ";
		$query  = "SELECT * FROM {$wpdb->prefix}woocommerce_api_keys WHERE 1 = 1 {$search} ORDER BY last_access DESC LIMIT 1";
		$key    = $wpdb->get_row( $query );

		if ( ! empty( $key ) && $key->permissions == 'read_write' ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_WARNING;
	}

	/**
	 * @return int
	 */
	private function check_PF_API_key() {

		$option = get_option( 'woocommerce_printful_settings', array() );
		if ( ! empty( $option['printful_key'] ) && strlen( $option['printful_key'] ) == 36 ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}

	/**
	 * @return int
	 */
	private function check_PF_API_connect() {

		if ( Printful_Integration::instance()->is_connected(true) ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}

	/**
	 * @return int
	 */
	private function check_PHP_time_limit() {
		$time_limit = ini_get( 'max_execution_time' );

		if ( $time_limit >= 30 ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}

	/**
	 * @return int
	 */
	private function check_PF_sync_errors() {

		$sync_log = get_option( Printful_Request_log::PF_OPTION_INCOMING_API_REQUEST_LOG, array() );
		if ( empty( $sync_log ) ) {
			return self::PF_STATUS_OK;    //no results means no errors
		}

		$sync_log = array_reverse( $sync_log );
		$sync_log = array_slice( $sync_log, 0, 6 );   //we only care about last to syncs

		foreach ( $sync_log as $sl ) {
			if ( ! empty( $sl['result'] ) && $sl['result'] == 'ERROR' ) {
				return self::PF_STATUS_FAIL;
			}
		}

		return self::PF_STATUS_OK;
	}

	/**
	 * @return int
	 */
	private function check_W3_db_cache() {

		if ( ! is_plugin_active( 'w3-total-cache/w3-total-cache.php' ) ) {
			return self::PF_STATUS_OK;
		}

		$w3tc_config_file = get_home_path() . 'wp-content/w3tc-config/master.php';
		if ( file_exists( $w3tc_config_file ) && is_readable( $w3tc_config_file ) ) {
			$content = @file_get_contents( $w3tc_config_file );
			$config  = @json_decode( substr( $content, 14 ), true );

			if ( is_array( $config ) && ! empty( $config['dbcache.enabled'] ) ) {
				return ! $config['dbcache.enabled'];
			}
		}

		return self::PF_STATUS_OK;
	}

	/**
	 * @return int
	 */
    private function check_WC_API_enabled() {

        $enabled = get_option('woocommerce_api_enabled', false);

        if($enabled == 'yes') {
            return self::PF_STATUS_OK;
        }

	    return self::PF_STATUS_FAIL;
    }

	/**
	 * @return int
	 */
	private function check_permalinks() {

		$permalinks = get_option( 'permalink_structure', false );

		if ( $permalinks && strlen( $permalinks ) > 0 ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}

	/**
	 * @return int
	 */
	private function check_printaura_plugin() {

		if ( ! is_plugin_active( 'printaura-woocommerce-api/printaura-woocommerce-api.php' ) ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}

	/**
	 * @return int
	 */
	private function check_wp_spamshield() {

		if ( ! is_plugin_active( 'wp-spamshield/wp-spamshield.php' ) ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}

	/**
	 * @return int
	 */
	private function check_remote_requests() {

		// Setup request args.
		$http_args = array(
			'method'      => 'POST',
			'timeout'     => MINUTE_IN_SECONDS,
			'redirection' => 0,
			'httpversion' => '1.0',
			'blocking'    => true,
			'user-agent'  => sprintf( 'WooCommerce/%s Hookshot (WordPress/%s)', WC_VERSION, $GLOBALS['wp_version'] ),
			'body'        => trim( json_encode( array( 'test' => true ) ) ),
			'headers'     => array( 'Content-Type' => 'application/json' ),
			'cookies'     => array(),
		);

		// Add custom headers.
		$http_args['headers']['X-WC-Webhook-Source'] = home_url( '/' ); // Since 2.6.0.
		$http_args['headers']['X-WC-Webhook-Topic']  = self::PF_REMOTE_REQUEST_TOPIC;

		// Webhook away!
		$response = wp_safe_remote_request( Printful_Base::get_printful_api_host() . self::PF_REMOTE_REQUEST_URL, $http_args );

		if ( is_wp_error( $response ) ) {
			return self::PF_STATUS_FAIL;
		}

		return self::PF_STATUS_OK;
	}

	/**
	 * @return int
	 */
	private function check_WC_auth_url_access() {

		$url       = home_url( '/' ) . 'wc-auth/v1/authorize?app_name=Printful&scope=read_write&user_id=1&return_url=https%3A%2F%2Fwww.printful.com%2Fdashboard%2Fwoocommerce%2Freturn&callback_url=https%3A%2F%2Fapi.printful.com%2Fwebhook%2Fwoocommerce-auth-callback';
		$http_args = array(
			'timeout'    => 60,
			'method'     => 'GET',
			'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.82 Safari/537.36',
		);

		$response = wp_safe_remote_get( $url, $http_args );

		if ( is_wp_error( $response ) ) {
			return self::PF_STATUS_FAIL;
		}

		$code = $response['response']['code'];

		if ( $code == 200 ) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}

	/**
	 * @return int
	 */
	private function check_WP_error_logs() {

		if (
			( defined( 'WP_DEBUG' ) && WP_DEBUG == true )
			&&
			( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG == true )
		) {
			return self::PF_STATUS_OK;
		}

		return self::PF_STATUS_FAIL;
	}
}