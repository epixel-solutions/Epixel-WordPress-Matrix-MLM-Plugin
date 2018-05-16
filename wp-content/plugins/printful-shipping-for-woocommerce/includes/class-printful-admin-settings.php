<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Printful_Admin_Settings {
	
	public static $_instance;
	const CARRIER_TYPE_STANDARD = 'standard';
	const CARRIER_TYPE_EXPEDITED = 'expedited';

	public static $integration_fields = array(
		'printful_key'  => array(
			'title'       => 'Printful store API key',
			'type'        => 'text',
			'desc_tip'    => true,
			'description' => 'Your store\'s Printful API key. Create it in the Prinful dashboard',
			'default'     => false,
		),
		'calculate_tax' => array(
			'title'   => 'Calculate sales tax',
			'type'    => 'checkbox',
			'label'   => 'Calculated for all products shipped to North Carolina and California',
			'default' => 'no',
		),
		'disable_ssl'   => array(
			'title'   => 'Disable SSL',
			'type'    => 'checkbox',
			'label'   => 'Use HTTP instead of HTTPS to connect to the Printful API (may be required if the plugin does not work for some hosting configurations)',
			'default' => 'no',
		),
	);

	/**
	 * @return Printful_Admin_Settings
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Setup the view
	 */
	public static function view() {

		$settings = self::instance();
		$settings->render();
	}

	/**
	 * Display the view
	 */
	public function render() {

		Printful_Admin::load_template( 'header', array( 'tabs' => Printful_Admin::get_tabs() ) );

		echo '<form method="post" name="printful_settings" action="' . admin_url( 'admin-ajax.php?action=save_printful_settings' ) . '">';

		//integration settings
		$integration_settings = $this->setup_integration_fields();
		Printful_Admin::load_template( 'setting-group', $integration_settings );

		Printful_Admin::load_template( 'shipping-notification' );

		//carriers settings
		Printful_Admin::load_template( 'ajax-loader', array( 'action' => 'get_printful_carriers', 'message' => 'Loading your carriers...' ) );

		Printful_Admin::load_template( 'setting-submit', array( 'nonce' => wp_create_nonce( 'printful_settings' ), 'disabled' => true ) );

		echo '</from>';

		Printful_Admin::load_template( 'footer' );
	}

	/**
	 * Display the ajax content for carrier settings
	 */
	public static function render_carriers_ajax() {

		$carrier_settings = self::instance()->setup_carrier_fields();
		Printful_Admin::load_template( 'setting-group', $carrier_settings );
		$enable_submit = 'Printful_Settings.enable_submit_btn();';
		Printful_Admin::load_template( 'inline-script', array('script' => $enable_submit) );
		exit;
	}

	/**
	 * @return mixed
	 * @internal param $integration_settings
	 */
	public function setup_integration_fields() {

		$integration_settings = array(
			'title'       => 'Integration settings',
			'description' => '',
			'settings'    => self::$integration_fields,
		);

		foreach ( $integration_settings['settings'] as $key => $setting ) {
			if ( $setting['type'] !== 'title' ) {
				$integration_settings['settings'][ $key ]['value'] = Printful_Integration::instance()->get_option( $key, $setting['default'] );
			}
		}

		return $integration_settings;
	}

	/**
	 * @internal param $carrier_settings
	 */
	public function setup_carrier_fields() {

		$carrier_settings = array(
			'title'       => 'Carriers & Services',
			'description' => "You can specify here which shipping carriers are available for this store.\n
			 Uncheck the ones you want to disable. If you leave “Flat rate” checked it means that any order where flat rate was selected can still ship with any carrier and service.",
			'settings'    => array(),
		);

		if ( ! Printful_Integration::instance()->is_connected() ) {
			$carrier_settings['description'] = 'You need to be connected to Printful API to edit carrier settings!';
			return $carrier_settings;
		}

		$carriers = Printful_Carriers::instance()->carriers;
		if ( empty( $carriers ) ) {
			return false;
		}

		$standard  = array();
		$expedited = array();

		foreach ( $carriers as $carrier ) {

			$item = array(
				'title'   => false,
				'type'    => 'checkbox',
				'label'   => $carrier['title'] . ' <i>' . $carrier['subtitle'] . '</i>',
				'default' => 'yes',
				'value'   => ( $carrier['status'] == 'on' ? 'yes' : 'no' ),
			);

			if ( $carrier['type'] == self::CARRIER_TYPE_STANDARD ) {
				$standard[ $carrier['carrier_id'] ] = $item;
			} else {
				$expedited[ $carrier['carrier_id'] ] = $item;
			}
		}

		$carrier_settings['settings'][ self::CARRIER_TYPE_STANDARD ] = array(
			'title' => 'Standard shipping',
			'type'  => 'checkbox-group',
			'items' => $standard,
		);

		$carrier_settings['settings'][ self::CARRIER_TYPE_EXPEDITED ] = array(
			'title' => 'Expedited shipping',
			'type'  => 'checkbox-group',
			'items' => $expedited,
		);

		return $carrier_settings;
	}

	/**
	 * Prepare carrier data for posting to Printful API
	 * @return array
	 */
	public function prepare_carriers() {

		$carriers  = Printful_Carriers::instance()->carriers;

		if ( empty( $carriers ) ) {
			return false;
		}

		$standard  = ( ! empty( $_POST[ self::CARRIER_TYPE_STANDARD ] ) ? $_POST[ self::CARRIER_TYPE_STANDARD ] : array() );
		$expedited = ( ! empty( $_POST[ self::CARRIER_TYPE_EXPEDITED ] ) ? $_POST[ self::CARRIER_TYPE_EXPEDITED ] : array() );

		$saved_carriers = array_merge( $standard, $expedited );

		if ( empty( $saved_carriers ) ) {
			return false;
		}

		$request_body   = array();

		foreach ( $carriers as $carrier ) {
			$status = 'off';
			if ( in_array( $carrier['carrier_id'], $saved_carriers ) ) {
				$status = 'on';
			}
			$request_body[] = array(
				'carrier_id' => $carrier['carrier_id'],
				'status'     => $status,
			);
		}

		return $request_body;
	}

	/**
	 * Ajax endpoint for saving the settings
	 */
	public static function save_printful_settings() {

		if ( ! empty( $_POST ) ) {

			check_admin_referer( 'printful_settings' );

			//save carriers first, so API key change does not affect this
			if ( Printful_Integration::instance()->is_connected(true) ) {

				//save remote carrier settings
				$request_body = Printful_Admin_Settings::instance()->prepare_carriers();
				$result = Printful_Carriers::instance()->post_carriers( $request_body );

				if ( ! $result ) {
					die( 'Error: failed to save carriers' );
				}
			}

			$options = array();

			//build save options list
			foreach ( self::$integration_fields as $key => $field ) {

				if ( $field['type'] == 'checkbox' ) {
					if ( isset( $_POST[ $key ] ) ) {
						$options[ $key ] = 'yes';
					} else {
						$options[ $key ] = 'no';
					}
				} else {
					if ( isset( $_POST[ $key ] ) ) {
						$options[ $key ] = $_POST[ $key ];
					}
				}
			}

			//save integration settings
			Printful_Integration::instance()->update_settings( $options );

			die('OK');
		}
	}
}