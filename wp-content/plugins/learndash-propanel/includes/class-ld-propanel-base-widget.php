<?php
/**
 * LearnDash ProPanel Widget Base
 *
 * @package LearnDash_ProPanel
 * @since 2.0
 */

if ( !class_exists( 'LearnDash_ProPanel_Widget' ) ) {
	class LearnDash_ProPanel_Widget {

		/**
		 * @var LearnDash_ProPanel_Overview The reference to *Singleton* instance of this class
		 */
		private static $instance;

		/**
		 * Returns the *Singleton* instance of this class.
		 *
		 * @return LearnDash_ProPanel_Widget The *Singleton* instance.
		 */
		public static function get_instance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}

			return static::$instance;
		}

		/**
		 * LearnDash_ProPanel_Overview constructor.
		 */
		public function __construct() {
			add_action( 'wp_dashboard_setup', array( $this, 'register_widget' ) );
			add_action( 'wp_ajax_learndash_propanel_template', array( $this, 'load_template' ) );
		}

		/**
		 * Register Widget
		 */
		function register_widget() {
			// Only show the ProPanel widgets for admin and group leaders
			if ( ( current_user_can( 'administrator' ) ) || ( current_user_can( 'group_leader' ) ) ) {
				wp_add_dashboard_widget( 'learndash-propanel-' . $this->name, $this->label, array( $this, 'initial_template' ) );
			}
		}

		/**
		 * Initial Template
		 */
		function initial_template() {}

		/**
		 * Load Template(s)
		 */
		function load_template() {
			check_ajax_referer( 'ld-propanel', 'nonce' );

			if ( isset( $_GET['template'] ) && ! empty( $_GET['template'] ) ) {
				$output = apply_filters( 'learndash_propanel_template_ajax', '', $_GET['template'] );
				wp_send_json_success( array( 'output' => $output ) );
			}
			die();
		}
	}
}