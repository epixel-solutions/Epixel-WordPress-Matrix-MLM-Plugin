<?php
/**
 * Plugin Name: Meta Box Tooltip
 * Plugin URI: http://premium.deluxeblogtips.com/meta-box-tooltip
 * Description: Add tooltip for meta fields
 * Version: 1.0.1
 * Author: Rilwis
 * Author URI: http://www.deluxeblogtips.com
 * License: GPL2+
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'MB_Tooltip' ) ) {
	class MB_Tooltip {
		/**
		 * Add hooks to meta box
		 */
		public function __construct() {
			add_action( 'rwmb_enqueue_scripts', array( $this, 'enqueue' ) );
			add_filter( 'rwmb_begin_html', array( $this, 'output' ), 10, 2 );
		}

		/**
		 * Add scripts and style of tooltip into meta box plugin
		 */
		public function enqueue() {
			list( , $url ) = RWMB_Loader::get_path( dirname( __FILE__ ) );
			wp_enqueue_style( 'mb-tooltip', $url . 'css/tooltip.css', '', '1.0.0' );
			wp_enqueue_script( 'mb-tooltip-bootstrap', $url . 'js/bootstrap.min.js', array( 'jquery' ), '1.0.0', true );
			wp_enqueue_script( 'mb-tooltip', $url . 'js/tooltip.js', array( 'mb-tooltip-bootstrap' ), '1.0.0', true );
		}

		/**
		 * Add tooltip to field label
		 *
		 * @param string $html Output HTML
		 * @param array $field Field information
		 *
		 * @return string
		 */
		public function output( $html, $field ) {
			if ( empty( $field['tooltip'] ) ) {
				return $html;
			}

			$tooltip = $field['tooltip'];

			// Add tooltip to field label, in one of following formats
			// 1) 'tooltip' => 'Tooltip Content'
			// 2) 'tooltip' => array( 'icon' => 'info', 'content' => 'Tooltip Content', 'position' => 'top' )
			// 3) 'tooltip' => array( 'icon' => 'http://url-to-icon-image.png', 'content' => 'Tooltip Content', 'position' => 'top' )
			//
			// In 1st format, icon will be 'info' by default
			// In 2nd format, icon can be 'info' (default), 'help'
			// In 3rd format, icon can be URL to custom icon image
			//
			// 'position' is optional. Value can be 'top' (default), 'bottom', 'left', 'right'

			$icon     = 'info';
			$position = 'top';
			$content  = $tooltip;
			if ( is_array( $tooltip ) ) {
				$icon    = $tooltip['icon'];
				$content = $tooltip['content'];
				if ( isset( $tooltip['position'] ) ) {
					$position = $tooltip['position'];
				}
			}
			// If icon is an URL to custom image
			if ( filter_var( $icon, FILTER_VALIDATE_URL ) ) {
				$icon_html = '<img src="' . esc_url( $icon ) . '">';
			} else {
				$icons     = array(
					'info' => 'dashicons dashicons-info',
					'help' => 'dashicons dashicons-editor-help',
				);
				$class     = isset( $icons[ $icon ] ) ? $icons[ $icon ] : "dashicons $icon";
				$icon_html = '<span class="' . esc_attr( $class ) . '"></span>';
			}
			$tooltip_html = sprintf(
				'<a href="#" class="mb-tooltip" data-toggle="tooltip" data-placement="%s" title="%s">%s</a>',
				esc_attr( $position ),
				esc_attr( $content ),
				$icon_html
			);

			$html = str_replace( '</label>', $tooltip_html . '</label>', $html );

			return $html;
		}
	}

	if ( is_admin() ) {
		new MB_Tooltip;
	}
}
