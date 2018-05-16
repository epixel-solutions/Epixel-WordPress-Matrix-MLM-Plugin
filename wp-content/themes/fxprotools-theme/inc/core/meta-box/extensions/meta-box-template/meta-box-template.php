<?php
/*
Plugin Name: Meta Box Template
Plugin URI: http://metabox.io/plugins/meta-box-template/
Description: Configure meta boxes easily via a template
Version: 0.2
Author: Rilwis
Author URI: http://www.deluxeblogtips.com
License: GPL2+
*/

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( is_admin() )
{
	define( 'MB_TEMPLATE_DIR', plugin_dir_path( __FILE__ ) );
	define( 'MB_TEMPLATE_URL', plugin_dir_url( __FILE__ ) );

	require MB_TEMPLATE_DIR . 'inc/class-mb-template-settings.php';
	new MB_Template_Settings;

	require MB_TEMPLATE_DIR . 'inc/class-mb-template-register.php';
	new MB_Template_Register;
}
