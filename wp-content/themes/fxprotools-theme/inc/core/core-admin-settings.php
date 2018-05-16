<?php
/**
 * --------------
 * Admin Settings
 * --------------
 * Admin related settings
 */

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('AdminSettings')){

	class AdminSettings {

		public function __construct()
		{
			add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
			add_action('login_enqueue_scripts',  array($this, 'enqueue_admin_assets'));
			add_action('admin_menu',  array($this, 'remove_admin_menus'), 99);
			add_action('admin_init', array($this, 'remove_dashboard_meta'));
			add_action('after_setup_theme', array($this, 'remove_admin_bar_non_admin'));
			add_action('widgets_init', array($this, 'register_widget_options'));
			add_filter('login_headerurl', array($this, 'login_logo_link'));
			add_theme_support('menus');
			add_theme_support('post-thumbnails');
		}

		// Admin assets
		public function enqueue_admin_assets()
		{
			global $theme_version;
			// wp_enqueue_style('admin-style', get_stylesheet_directory_uri().'/assets/css/admin.css', $theme_version);
		}

		// Change link of logo in login(Default is wordpress link)
		public function login_logo_link()
		{
			return get_bloginfo('url');
		}

		// Remove Admin Menus
		public function remove_admin_menus()
		{
			//remove_menu_page('index.php');                  // Dashboard
			remove_menu_page('jetpack');                    // Jetpack
			remove_menu_page('edit.php');                   // Posts
			remove_menu_page('upload.php');                 // Media
			// remove_menu_page('edit.php?post_type=page');    // Pages
			remove_menu_page('edit-comments.php');          // Comments
			// remove_menu_page('themes.php');                 // Appearance
			// remove_menu_page('plugins.php');                // Plugins
			// remove_menu_page('users.php');                  // Users
			//remove_menu_page('tools.php');                  // Tools
			// remove_menu_page('options-general.php');        // Settings
			remove_menu_page('edit.php?post_type=meta-box');    // Metabox IO
		}

		// Remove Dashboard Widgets
		public function remove_dashboard_meta()
		{
			if (!current_user_can( 'manage_options')){
				remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
				remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
				remove_meta_box('dashboard_primary', 'dashboard', 'normal');
				remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
				remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
				remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
				remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
				remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
				remove_meta_box('dashboard_activity', 'dashboard', 'normal');
			}
		}

		// Remove admin bar for Non-admin
		public function remove_admin_bar_non_admin()
		{
		     if ( !current_user_can('manage_options') && !is_admin()) {
		         show_admin_bar(false);
		     }
		}

		// Register Widget Options
		public function register_widget_options()
		{
		    register_sidebar( array(
		        'name'          => 'Footer Menu 1',
		        'id'            => 'footer_menu_1',
		        'before_widget' => '',
		        'after_widget'  => '',
		        'before_title'  => '<h2 class="widget-title">',
		        'after_title'   => '</h2>',
		    ));

		    register_sidebar( array(
		        'name'          => 'Footer Menu 2',
		        'id'            => 'footer_menu_2',
		        'before_widget' => '',
		        'after_widget'  => '',
		        'before_title'  => '<h2 class="widget-title">',
		        'after_title'   => '</h2>',
		    ));

		    register_sidebar( array(
		        'name'          => 'Footer Menu 3',
		        'id'            => 'footer_menu_3',
		        'before_widget' => '',
		        'after_widget'  => '',
		        'before_title'  => '<h2 class="widget-title">',
		        'after_title'   => '</h2>',
		    ));
		}

		// Custom page - Authorize.net CIM and Subscriptions Manager
		// public function page_anet()
		// {
		// 	$page_settings = add_menu_page(
		// 		'ANET - CISM',
		// 		'ANET - CISM',
		// 		'manage_options',
		// 		'anet-management',
		// 		'page_content',
		// 		'dashicons-exerpt-view',
		// 		9
		// 	);
		// 	add_action('load-' . $page_settings, 'page_assets');

		// 	function page_assets(){
		// 		// CSS
		// 		wp_enqueue_style('css-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), '', 'all');
		// 		wp_enqueue_style('css-datatable', 'https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.css', array(), '', 'all');
		// 		wp_enqueue_style('css-custom', get_stylesheet_directory_uri() . '/assets/css/admin-anet.css', array(), '', 'all');
		// 		// JS
		// 		wp_enqueue_script('js-jquery', 'https://code.jquery.com/jquery-2.2.4.min.js', FALSE, '', TRUE);
		// 		wp_enqueue_script('js-chosen', 'https://cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js', FALSE, '', TRUE);
		// 		wp_enqueue_script('js-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', FALSE, '', TRUE);
		// 		wp_enqueue_script('js-datatable', 'https://cdn.datatables.net/v/bs/dt-1.10.13/datatables.min.js', FALSE, '', TRUE);
		// 		wp_enqueue_script('js-admin', get_stylesheet_directory_uri() . '/assets/js/admin-anet.js', FALSE, '', TRUE);

		// 		// Declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		// 		wp_localize_script('js-admin', 'wpAjax', array(
		// 			'ajaxUrl'   => admin_url('admin-ajax.php'),
		// 			'ajaxNonce' => wp_create_nonce('wp_nonce')
		// 		));

		// 	}

		// 	function page_content(){
		// 		get_template_part('inc/templates/template-admin-anet');
		// 	}
		// }

	}

}

return new AdminSettings();