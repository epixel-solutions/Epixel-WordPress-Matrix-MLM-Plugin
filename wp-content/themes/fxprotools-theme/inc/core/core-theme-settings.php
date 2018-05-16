<?php
/**
 * --------------
 * Theme Settings
 * --------------
 * Theme related settings
 */

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('ThemeSettings')){

	class ThemeSettings {

		const GUEST_ALLOWED_PAGES =  array( 'funnels', 'referred-members', 'wallet', 'login', 'forgot-password', 'verify-email', 'f1', 'f2', 'f3', 'f4', 'lp1', 'lp2', 'lp3', 'lp4', 'signals', 'autologin', 'log-out-notice','no-access', 'renewal', 'register' );
		const USER_ALLOWED_PAGES = array ( 'my-account', 'inbox', 'compose', 'read', 'sent', 'trash' );

		public function __construct()
		{
			add_action('wp_enqueue_scripts', array($this, 'enqueue_theme_assets'));
			add_action('after_setup_theme', array($this, 'theme_settings'));
		}

		public function enqueue_theme_assets()
		{
			global $theme_version, $post;
			// Disable loading of jquery on wordpress core
			if(!is_admin()){
				wp_deregister_script('jquery');
				wp_deregister_script('wp-embed');
			}
			// Styles - Core
			wp_enqueue_style('style-bootstrap', get_template_directory_uri().'/vendors/bootstrap-3.3.7/css/bootstrap.min.css', $theme_version);
			wp_enqueue_style('style-fontawesome', get_template_directory_uri().'/vendors/font-awesome-4.7.0/css/font-awesome.min.css', $theme_version);
			wp_enqueue_style('style-boostrap-datepicker', get_template_directory_uri().'/vendors/boostrap-datepicker-1.7.1/css/bootstrap-datepicker.min.css', $theme_version);
			wp_enqueue_style('style-noty', get_template_directory_uri().'/vendors/noty-3.1.1/css/noty.css', $theme_version);
			wp_enqueue_style('style-select2', get_template_directory_uri().'/vendors/select2-4.0.4/css/select2.min.css', $theme_version);
			// Styles - Custom
			wp_enqueue_style('theme-style', get_template_directory_uri().'/assets/css/theme/theme.css', $theme_version);

			// Scripts - Core
			wp_enqueue_script('jquery', get_stylesheet_directory_uri().'/vendors/jquery-3.2.1/jquery-3.2.1.min.js', $theme_version);
			wp_enqueue_script('script-bootstrap', get_stylesheet_directory_uri().'/vendors/bootstrap-3.3.7/js/bootstrap.min.js', array(), $theme_version, true);
			wp_enqueue_script('script-bootstrap-datepicker', get_stylesheet_directory_uri().'/vendors/boostrap-datepicker-1.7.1/js/bootstrap-datepicker.min.js', array(), $theme_version, true);
			wp_enqueue_script('script-clipboardjs', get_stylesheet_directory_uri().'/vendors/clipboard-js-1.7.1/js/clipboard.min.js', array(), $theme_version, true);
			wp_enqueue_script('script-noty', get_stylesheet_directory_uri().'/vendors/noty-3.1.1/js/noty.min.js', array(), $theme_version, true);
			wp_enqueue_script('script-jquery-cookie', get_stylesheet_directory_uri().'/vendors/jquery-cookie-1.4.1/jquery.cookie.min.js', array(), $theme_version, true);
			wp_enqueue_script('script-moment', get_stylesheet_directory_uri().'/vendors/moment-2.19.1/moment.min.js', array(), $theme_version, true);
			wp_enqueue_script('embedly', 'https://cdn.embed.ly/jquery.embedly-3.1.1.min.js', array(), $theme_version, true );
			wp_enqueue_script('script-player', get_stylesheet_directory_uri().'/vendors/player-0.0.12/player.min.js', array(), $theme_version, true);
			wp_enqueue_script('script-tinymce', get_stylesheet_directory_uri().'/vendors/tinymce-4.7.1/tinymce.min.js', array(), $theme_version, true);
			wp_enqueue_script('script-select2', get_stylesheet_directory_uri().'/vendors/select2-4.0.4/js/select2.min.js', array(), $theme_version, true);
			
			// Scripts - Custom
			wp_enqueue_script('theme-js', get_bloginfo('template_url').'/assets/js/theme/theme.js', array(), $theme_version, true);
			wp_localize_script('theme-js', 'fx', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'login_url' => site_url( 'login' ),
				'logout_url' => wp_logout_url()
			));

			// Page-specific Assets
			if (!empty($post->post_name)) {
				$post_name = $post->post_name;

				switch ($post_name) {
					case 'password-checkpoint':
						wp_enqueue_script('script-pwstrength', get_stylesheet_directory_uri().'/vendors/jquery.pwstrength.bootstrap-2.1.3/pwstrength-bootstrap.min.js', array(), $theme_version, true);
						break;
				}
			}
		}

		public function theme_settings(){
			$defaults = array(
		        'height'      => 210,
		        'width'       => 665,
		        'flex-height' => true,
		        'flex-width'  => true,
		        'header-text' => array( 'site-title', 'site-description' ),
		    );
		    add_theme_support( 'custom-logo', $defaults );
		}
	}
}

return new ThemeSettings();
