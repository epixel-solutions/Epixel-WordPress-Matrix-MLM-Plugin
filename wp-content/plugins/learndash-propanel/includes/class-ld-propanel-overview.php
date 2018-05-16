<?php
/**
 * LearnDash ProPanel Overview
 *
 * @package LearnDash_ProPanel_Overview
 * @since 2.0
 */

if ( !class_exists( 'LearnDash_ProPanel_Overview' ) ) {
	class LearnDash_ProPanel_Overview extends LearnDash_ProPanel_Widget{

		/**
		 * @var string
		 */
		protected $name;
	
		/**
		 * @var string
		 */
		protected $label;

		/**
		 * LearnDash_ProPanel_Overview constructor.
		 */
		public function __construct() {
			$this->name = 'overview';
			$this->label = esc_html__( 'ProPanel Overview', 'ld_propanel' );

			parent::__construct();
			add_filter( 'learndash_propanel_template_ajax', array( $this, 'overview_template' ), 10, 2 );
		}

		public function overview_template( $output, $template ) {
			if ( 'overview' == $template ) {
				ob_start();
				include ld_propanel_get_template( 'ld-propanel-overview.php' );
				$output = ob_get_clean();
			}

			return $output;
		}
	}
}
