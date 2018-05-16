<?php
/**
 * -----------------------------
 * Custom Post/Taxonomy Settings
 * -----------------------------
 * Custom Post/Taxonomy related Settings
 */

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('CptSettings')){

	class CptSettings {
		
		// Initialize function(s)
		public function __construct()
		{
			$cpts = array('init_cpt_webinar', 'init_cpt_funnels', 'init_cpt_emails', 'init_cpt_sms');
			if($cpts) {
				foreach ($cpts as $key => $cpt) {
					add_filter('init', array($this, $cpt));
				}
			}
			add_action('after_switch_theme', array($this, 'theme_flush_rewrite'));
		}

		// Custom Post - Webinar
		public function init_cpt_webinar()
		{
			register_post_type('fx_webinar',
				array(
					'capability_type'     => 'page',
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'has_archive'         => true,
					'exclude_from_search' => true,
					'show_in_menu'        => true,
					'show_in_nav_menus'   => true,
					'show_in_admin_bar'   => true,
					'can_export'          => true,
					'menu_position'       => 5,
					'menu_icon'           => 'dashicons-desktop',
					'rewrite'             => array('slug' => 'webinar'),
					'supports'            => array('title', 'thumbnail','page-attributes'),
					'taxonomies'          => array('category'),
					'labels' => array(
						'name'                  => 'Webinars',
						'singular_name'         => 'Webinar',
						'menu_name'             => 'Webinars',
						'name_admin_bar'        => 'Webinar',
						'archives'              => 'Webinar Archives',
						'attributes'            => 'Webinar Attributes',
						'parent_item_colon'     => 'Parent Webinar:',
						'all_items'             => 'All Webinars',
						'add_new_item'          => 'Add New Webinar',
						'add_new'               => 'Add New',
						'new_item'              => 'New Webinar',
						'edit_item'             => 'Edit Webinar',
						'update_item'           => 'Update Webinar',
						'view_item'             => 'View Webinar',
						'view_items'            => 'View Webinars',
						'search_items'          => 'Search Webinar',
						'not_found'             => 'Not found',
						'not_found_in_trash'    => 'Not found in Trash',
						'featured_image'        => 'Featured Image',
						'set_featured_image'    => 'Set featured image',
						'remove_featured_image' => 'Remove featured image',
						'use_featured_image'    => 'Use as featured image',
						'insert_into_item'      => 'Insert into item',
						'uploaded_to_this_item' => 'Uploaded to this Webinar',
						'items_list'            => 'Webinars list',
						'items_list_navigation' => 'Webinars list navigation',
						'filter_items_list'     => 'Filter Webinars list',
					)
				)
			);
		}

		// Custom Post - Funnels
		public function init_cpt_funnels()
		{
			register_post_type('fx_funnel',
				array(
					'capability_type'     => 'page',
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'has_archive'         => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'show_in_menu'        => true,
					'show_in_nav_menus'   => true,
					'show_in_admin_bar'   => true,
					'can_export'          => true,
					'menu_position'       => 6,
					'menu_icon'           => 'dashicons-desktop',
					'supports'            => array('title', 'thumbnail','page-attributes'),
					'taxonomies'          => array('category'),
					'labels' => array(
						'name'                  => 'Funnels',
						'singular_name'         => 'Funnel',
						'menu_name'             => 'Funnels',
						'name_admin_bar'        => 'Funnel',
						'archives'              => 'Funnel Archives',
						'attributes'            => 'Funnel Attributes',
						'parent_item_colon'     => 'Parent Funnel:',
						'all_items'             => 'All Funnels',
						'add_new_item'          => 'Add New Funnel',
						'add_new'               => 'Add New',
						'new_item'              => 'New Funnel',
						'edit_item'             => 'Edit Funnel',
						'update_item'           => 'Update Funnel',
						'view_item'             => 'View Funnel',
						'view_items'            => 'View Funnels',
						'search_items'          => 'Search Funnel',
						'not_found'             => 'Not found',
						'not_found_in_trash'    => 'Not found in Trash',
						'featured_image'        => 'Featured Image',
						'set_featured_image'    => 'Set featured image',
						'remove_featured_image' => 'Remove featured image',
						'use_featured_image'    => 'Use as featured image',
						'insert_into_item'      => 'Insert into item',
						'uploaded_to_this_item' => 'Uploaded to this Funnel',
						'items_list'            => 'Funnels list',
						'items_list_navigation' => 'Funnels list navigation',
						'filter_items_list'     => 'Filter Funnels list',
					)
				)
			);
		}

		// Custom Post - Email
		public function init_cpt_emails()
		{
			register_post_type('fx_email',
				array(
					'capability_type'     => 'page',
					'hierarchical'        => false,
					'public'              => false,
					'show_ui'             => true,
					'has_archive'         => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'show_in_menu'        => true,
					'show_in_nav_menus'   => false,
					'show_in_admin_bar'   => true,
					'can_export'          => true,
					'menu_position'       => 7,
					'menu_icon'           => 'dashicons-email',
					'rewrite'             => array('slug' => 'emails'),
					'supports'            => array('title'),
					'labels' => array(
						'name'                  => 'Emails',
						'singular_name'         => 'Email',
						'menu_name'             => 'Emails',
						'name_admin_bar'        => 'Email',
						'archives'              => 'Email Archives',
						'attributes'            => 'Email Attributes',
						'parent_item_colon'     => 'Parent Email:',
						'all_items'             => 'All Emails',
						'add_new_item'          => 'Send New Email',
						'add_new'               => 'Send Email',
						'new_item'              => 'New Email',
						'edit_item'             => 'Edit Email',
						'update_item'           => 'Update Email',
						'view_item'             => 'View Email',
						'view_items'            => 'View Emails',
						'search_items'          => 'Search Email',
						'not_found'             => 'Not found',
						'not_found_in_trash'    => 'Not found in Trash',
						'featured_image'        => 'Featured Image',
						'set_featured_image'    => 'Set featured image',
						'remove_featured_image' => 'Remove featured image',
						'use_featured_image'    => 'Use as featured image',
						'insert_into_item'      => 'Insert into item',
						'uploaded_to_this_item' => 'Uploaded to this Email',
						'items_list'            => 'Emails list',
						'items_list_navigation' => 'Emails list navigation',
						'filter_items_list'     => 'Filter Emails list',
					)
				)
			);
		}

		// Custom Post - Email
		public function init_cpt_sms()
		{
			register_post_type('fx_sms',
				array(
					'capability_type'     => 'page',
					'hierarchical'        => false,
					'public'              => false,
					'show_ui'             => true,
					'has_archive'         => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'show_in_menu'        => true,
					'show_in_nav_menus'   => false,
					'show_in_admin_bar'   => true,
					'can_export'          => true,
					'menu_position'       => 8,
					'menu_icon'           => 'dashicons-email',
					'rewrite'             => array('slug' => 'sms'),
					'supports'            => array(''),
					'labels' => array(
						'name'                  => 'SMSs',
						'singular_name'         => 'SMS',
						'menu_name'             => 'SMSs',
						'name_admin_bar'        => 'SMS',
						'archives'              => 'SMS Archives',
						'attributes'            => 'SMS Attributes',
						'parent_item_colon'     => 'Parent SMS:',
						'all_items'             => 'All SMSs',
						'add_new_item'          => 'Send New SMS',
						'add_new'               => 'Send SMS',
						'new_item'              => 'New SMS',
						'edit_item'             => 'Edit SMS',
						'update_item'           => 'Update SMS',
						'view_item'             => 'View SMS',
						'view_items'            => 'View SMSs',
						'search_items'          => 'Search SMS',
						'not_found'             => 'Not found',
						'not_found_in_trash'    => 'Not found in Trash',
						'featured_image'        => 'Featured Image',
						'set_featured_image'    => 'Set featured image',
						'remove_featured_image' => 'Remove featured image',
						'use_featured_image'    => 'Use as featured image',
						'insert_into_item'      => 'Insert into item',
						'uploaded_to_this_item' => 'Uploaded to this SMS',
						'items_list'            => 'SMSs list',
						'items_list_navigation' => 'SMSs list navigation',
						'filter_items_list'     => 'Filter SMSs list',
					)
				)
			);
		}
		
		// Fix Permalink - Makes permalink work
		public function theme_flush_rewrite()
		{
			flush_rewrite_rules();
		}

	}

}

return new CptSettings();
