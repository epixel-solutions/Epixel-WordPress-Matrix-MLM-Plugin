<?php
/**
 * ----------------
 * Metabox Settings
 * ----------------
 * Metabox Setup - See https://metabox.io/docs/ for documentation
 */

if(!defined('ABSPATH')){
    exit;
}

if(!class_exists('SettingsMB')){

    class SettingsMB {

        // Initialize function(s)
        public function __construct()
        {
            $metaboxes = array(
                'mb_courses',
                'mb_products',
                'mb_capture_page',
                'mb_webinar',
                'mb_page_template_options_1',
                'mb_page_template_options_2',
                'mb_page_template_options_3',
                'mb_emails',
                'mb_sms',
                'mb_course_categories'
            );
            if($metaboxes) {
                foreach ($metaboxes as $key => $mb) {
                    add_filter('rwmb_meta_boxes', array($this, $mb));
                }
            }
            
            add_filter('mb_settings_pages', array($this, 'mb_settings_pages'));
        }

        // MB - Courses(LearnDash LMS)
        public function mb_courses($meta_boxes)
        {
            $prefix = '';
            $meta_boxes[] = array(
                'id'         => 'course_custom_fields',
                'title'      => 'Course Custom Fields',
                'post_types' => array( 'sfwd-courses' ),
                'context'    => 'normal',
                'priority'   => 'high',
                'autosave'   => false,
                'fields' => array(
                    array(
                        'id'   => $prefix . 'short_description',
                        'type' => 'textarea',
                        'name' => 'Short Description',
                    ),
                    array(
                        'id'   => $prefix . 'subtitle',
                        'type' => 'text',
                        'name' => 'Subtitle', 'fxprotools',
                    ),
                ),
            );
            return $meta_boxes;
        }

        // MB - Products(Woocommerce)
        public function mb_products($meta_boxes)
        {
            $prefix = '';
            $meta_boxes[] = array(
                'id'         => 'product_custom_fields',
                'title'      => 'Product Custom Fields',
                'post_types' => array( 'product' ),
                'context'    => 'normal',
                'priority'   => 'high',
                'autosave'   => false,
                'fields' => array(
                    array(
                        'id'          => $prefix . 'subtitle',
                        'type'        => 'text',
                        'placeholder' => 'Short Description',
                        'name'        => 'Short Description',
                    ),
                    array(
                        'id'          => $prefix . 'personal_volume',
                        'type'        => 'number',
                        'placeholder' => 'Personal Volume',
                        'name'        => 'Personal Volume',
                    ),
                ),
            );
            return $meta_boxes;
        }

        // MB - Capture Page
        public function mb_capture_page($meta_boxes)
        {
            $prefix = '';
            $meta_boxes[] = array(
                'id'         => 'capture_page_fields',
                'title'      => 'Capture Page Fields',
                'post_types' => array( 'fx_funnel' ),
                'context'    => 'advanced',
                'priority'   => 'high',
                'autosave'   => false,
                'fields' => array(
                    array(
                        'id'   => $prefix . 'capture_page_title',
                        'type' => 'text',
                        'name' => 'Capture Page Title',
                        'size' => 80,
                    ),
                    array(
                        'id'   => $prefix . 'capture_sub_title',
                        'type' => 'text',
                        'name' => 'Capture Sub Title',
                        'size' => 80,
                    ),
                    array(
                        'id'   => $prefix . 'capture_page_url',
                        'type' => 'text',
                        'name' => 'Capture Page URL',
                        'size' => 80,
                    ),
                    array(
                        'id'   => $prefix . 'capture_page_thumbnail',
                        'type' => 'image_advanced',
                        'name' => 'Cature Page Thumbnail',
                        'force_delete' => false,
                        'max_file_uploads' => '1',
                    ),
                ),
            );
            $meta_boxes[] = array(
                'id'         => 'landing_page_fields',
                'title'      => 'Landing Page Fields',
                'post_types' => array( 'fx_funnel' ),
                'context'    => 'advanced',
                'priority'   => 'high',
                'autosave'   => false,
                'fields' => array(
                    array(
                        'id'   => $prefix . 'landing_page_title',
                        'type' => 'text',
                        'name' => 'Capture Page Title',
                        'size' => 80,
                    ),
                    array(
                        'id'   => $prefix . 'landing_sub_title',
                        'type' => 'text',
                        'name' => 'Capture Sub Title',
                        'size' => 80,
                    ),
                    array(
                        'id'   => $prefix . 'landing_page_url',
                        'type' => 'text',
                        'name' => 'Landing Page URL',
                        'size' => 80,
                    ),
                    array(
                        'id'   => $prefix . 'landing_page_thumbnail',
                        'type' => 'image_advanced',
                        'name' => 'Landing Page Thumbnail',
                        'force_delete' => false,
                        'max_file_uploads' => '1',
                    ),
                ),
            );
            return $meta_boxes;
        }

        // MB - Webinar
        public function mb_webinar($meta_boxes)
        {
            $prefix = '';
            $meta_boxes[] = array(
                'id'         => 'webinar_custom_fields',
                'title'      => 'Webinar Custom Fields',
                'post_types' => array( 'fx_webinar' ),
                'context'    => 'advanced',
                'priority'   => 'high',
                'autosave'   => false,
                'fields' => array(
                    array(
                        'id'   => $prefix . 'webinar_topic',
                        'type' => 'wysiwyg',
                        'name' => 'Topic',
                    ),
                    array(
                        'id'   => $prefix . 'webinar_start_date',
                        'type' => 'date',
                        'name' => 'Start Date',
                    ),
                    array(
                        'id'   => $prefix . 'webinar_start_time',
                        'type' => 'time',
                        'name' => 'Start Time',
                    ),
                    array(
                        'id'   => $prefix . 'webinar_meeting_link',
                        'type' => 'text',
                        'name' => 'Meeting Link',
                    ),
                ),
            );
            return $meta_boxes;
        }

        // MB - Page Template Options #1 with 2 video url
        public function mb_page_template_options_1($meta_boxes)
        {
            $prefix = 'pto1_';
            $meta_boxes[] = array(
                'id'         => 'page_template_options_1',
                'title'      => 'Page Template Options',
                'post_types' => array( 'post', 'page', 'sfwd-courses', 'sfwd-lessons' ),
                'context'    => 'advanced',
                'priority'   => 'high',
                'autosave'   => false,
                'include' => array(
                	'relation'	=> 'OR',
                	'ID'		=> '',
                	'parent'	=> '',
                	'slug'		=> array( 'dashboard','access-products', 'referral-program','compensation-plan' ),
                ),
                'tabs'      => array(
                    // $prefix . 'page'    => __( 'Page', 'rwmb' ),
                    $prefix . 'attributes'   => __( 'Attributes', 'rwmb' ),
                    $prefix . 'video'   => __( 'Video', 'rwmb' ),
                    $prefix . 'menu'    => __( 'Menu', 'rwmb' ),
                ),
                'tab_style'		=> 'left',
                'tab_wrapper'	=> true,
                'fields' 	=> array(
                    array(
                        'name'      => 'Parent',
                        'id'        => $prefix . 'parent_id',
                        'type'      => 'select',
                        'desc'      => '',
                        'options'   => array(
                            'default'       => 'None',
                        ),
                        'tab'           => $prefix . 'attributes',
                    ),
                    array(
                        'name'      => 'Page Template',
                        'id'        => $prefix . 'page_template',
                        'type'      => 'select',
                        'desc'      => '',
                        'options'   => array(
                            'default'       => 'Default',
                        ),
                        'tab'           => $prefix . 'attributes',
                    ),
                    array(
                        'name' 		=> 'Display Main Header Menu',
                        'id' 		=> $prefix . 'display_main_header_menu',
                        'type' 		=> 'select',
                        'desc'		=> 'Choose to show or hide the header',
                        'placeholder'	=> 'Default',
                        'options'	=> array(
                            'yes'		=> 'Yes',
                            'no'		=> 'No',
                        ),
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Main Header Menu',
                        'id' 			=> $prefix . 'main_header_menu',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 		=> 'Display Secondary Header Menu',
                        'id' 		=> $prefix . 'display_header_menu',
                        'type' 		=> 'select',
                        'desc'		=> 'Choose to show or hide the header',
                        'placeholder'	=> 'Default',
                        'options'	=> array(
                            'yes'		=> 'Yes',
                            'no'		=> 'No',
                        ),
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Secondary Header Menu',
                        'id' 			=> $prefix . 'secondary_header_menu',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Display Footer Menu',
                        'id' 			=> $prefix . 'display_footer_menu',
                        'type' 			=> 'select',
                        'desc'			=> 'Choose to show or hide the header',
                        'placeholder'	=> 'Default',
                        'options'		=> array(
                            'yes'		=> 'Yes',
                            'no'		=> 'No',
                        ),
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Footer Menu ( Far Left )',
                        'id' 			=> $prefix . 'footer_menu_fl',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Footer Menu ( Middle )',
                        'id' 			=> $prefix . 'footer_menu_mid',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Footer Menu ( Far Right )',
                        'id' 			=> $prefix . 'footer_menu_fr',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    // array(
                    //     'name'          => 'Video URL',
                    //     'id'            => $prefix . 'video_url',
                    //     'type'          => 'text',
                    //     'placeholder'   => '',
                    //     'tab'           => $prefix . 'video',
                    // ),
                    array(
                        'name'          => 'Video URL <sup style="color:#0073AA;">customer</sup>',
                        'id'            => $prefix . 'video_url_customer',
                        'type'          => 'text',
                        'placeholder'   => '',
                        'tab'           => $prefix . 'video',
                        'before'        => '<div class="dash-alert dash-alert--warning" role="alert"><strong>This option only works</strong> if this "page template" has video embed hardcoded into page or if the video embed <br> shortcode is present in the content of this page</div>'
                    ),
                    array(
                        'name'          => 'Video URL <sup style="color:#0073AA;">distributor</sup>',
                        'id'            => $prefix . 'video_url_distributor',
                        'type'          => 'text',
                        'placeholder'   => '',
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Autostart Video',
                        'id' 			=> $prefix . 'video_autostart',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Disable Video Controls',
                        'id' 			=> $prefix . 'video_disable_controls',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Disable Related',
                        'id' 			=> $prefix . 'video_disable_related',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Hide Info',
                        'id' 			=> $prefix . 'video_hide_info',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    // array(
                    //     'name' 			=> 'Disable Sharing',
                    //     'id' 			=> $prefix . 'video_disable_sharing',
                    //     'type' 			=> 'checkbox_list',
                    //     'placeholder'	=> '',
                    //     'options'		=> array(
                    //         'yes'		=> '',
                    //     ),
                    //     'tab'           => $prefix . 'video',
                    // ),
                    // array(
                    //     'name' 			=> 'Hide Branding',
                    //     'id' 			=> $prefix . 'video_hide_branding',
                    //     'type' 			=> 'checkbox_list',
                    //     'placeholder'	=> '',
                    //     'options'		=> array(
                    //         'yes'		=> '',
                    //     ),
                    //     'tab'           => $prefix . 'video',
                    // ),
                    array(
                        'name' 			=> 'Scrolling Video',
                        'id' 			=> $prefix . 'video_scrolling',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Floating Video',
                        'id' 			=> $prefix . 'video_floating',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                ),
            );
            return $meta_boxes;
        }

        // MB - Page Template Options #2 with 1 video url
        public function mb_page_template_options_2($meta_boxes)
        {
            $prefix = 'pto2_';
            $meta_boxes[] = array(
                'id'         => 'page_template_options_2',
                'title'      => 'Page Template Options',
                'post_types' => array( 'post', 'page', 'sfwd-courses', 'sfwd-lessons' ),
                'context'    => 'advanced',
                'priority'   => 'high',
                'autosave'   => false,
                'include' => array(
                	'relation'	=> 'OR',
                	'ID'		=> '',
                	'parent'	=> '',
                	'slug'		=> array( 'f2', 'lp1', 'lp2', 'lp3' ),
                ),
                'tabs'      => array(
                    // $prefix . 'page'    => __( 'Page', 'rwmb' ),
                    $prefix . 'video'   => __( 'Video', 'rwmb' ),
                    $prefix . 'menu'    => __( 'Menu', 'rwmb' ),
                ),
                'tab_style'		=> 'left',
                'tab_wrapper'	=> true,
                'fields' 	=> array(
                    array(
                        'name' 		=> 'Display Main Header Menu',
                        'id' 		=> $prefix . 'display_main_header_menu',
                        'type' 		=> 'select',
                        'desc'		=> 'Choose to show or hide the header',
                        'placeholder'	=> 'Default',
                        'options'	=> array(
                            'yes'		=> 'Yes',
                            'no'		=> 'No',
                        ),
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Main Header Menu',
                        'id' 			=> $prefix . 'main_header_menu',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 		=> 'Display Secondary Header Menu',
                        'id' 		=> $prefix . 'display_header_menu',
                        'type' 		=> 'select',
                        'desc'		=> 'Choose to show or hide the header',
                        'placeholder'	=> 'Default',
                        'options'	=> array(
                            'yes'		=> 'Yes',
                            'no'		=> 'No',
                        ),
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Secondary Header Menu',
                        'id' 			=> $prefix . 'secondary_header_menu',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Display Footer Menu',
                        'id' 			=> $prefix . 'display_footer_menu',
                        'type' 			=> 'select',
                        'desc'			=> 'Choose to show or hide the header',
                        'placeholder'	=> 'Default',
                        'options'		=> array(
                            'yes'		=> 'Yes',
                            'no'		=> 'No',
                        ),
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Footer Menu ( Far Left )',
                        'id' 			=> $prefix . 'footer_menu_fl',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Footer Menu ( Middle )',
                        'id' 			=> $prefix . 'footer_menu_mid',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Footer Menu ( Far Right )',
                        'id' 			=> $prefix . 'footer_menu_fr',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name'          => 'Video URL',
                        'id'            => $prefix . 'video_url',
                        'type'          => 'text',
                        'placeholder'   => '',
                        'tab'           => $prefix . 'video',
                        'before'        => '<div class="dash-alert dash-alert--warning" role="alert"><strong>This option only works</strong> if this "page template" has video embed hardcoded into page or if the video embed <br> shortcode is present in the content of this page</div>'
                    ),
                    array(
                        'name' 			=> 'Autostart Video',
                        'id' 			=> $prefix . 'video_autostart',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Disable Video Controls',
                        'id' 			=> $prefix . 'video_disable_controls',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Disable Related',
                        'id' 			=> $prefix . 'video_disable_related',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Hide Info',
                        'id' 			=> $prefix . 'video_hide_info',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    // array(
                    //     'name' 			=> 'Disable Sharing',
                    //     'id' 			=> $prefix . 'video_disable_sharing',
                    //     'type' 			=> 'checkbox_list',
                    //     'placeholder'	=> '',
                    //     'options'		=> array(
                    //         'yes'		=> '',
                    //     ),
                    //     'tab'           => $prefix . 'video',
                    // ),
                    // array(
                    //     'name' 			=> 'Hide Branding',
                    //     'id' 			=> $prefix . 'video_hide_branding',
                    //     'type' 			=> 'checkbox_list',
                    //     'placeholder'	=> '',
                    //     'options'		=> array(
                    //         'yes'		=> '',
                    //     ),
                    //     'tab'           => $prefix . 'video',
                    // ),
                    array(
                        'name' 			=> 'Scrolling Video',
                        'id' 			=> $prefix . 'video_scrolling',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Floating Video',
                        'id' 			=> $prefix . 'video_floating',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                ),
            );
            return $meta_boxes;
        }

        // MB - Page Template Options #3 without video tab
        public function mb_page_template_options_3($meta_boxes)
        {
            $prefix = 'pto3_';
            $meta_boxes[] = array(
                'id'         => 'page_template_options_3',
                'title'      => 'Page Template Options',
                'post_types' => array( 'post', 'page', 'sfwd-courses', 'sfwd-lessons' ),
                'context'    => 'advanced',
                'priority'   => 'high',
                'autosave'   => false,
                'exclude' => array(
                	'relation'	=> 'OR',
                	'ID'		=> '',
                	'parent'	=> '',
                	'slug'		=> array( 'dashboard','access-products', 'referral-program', 'f2', 'lp1', 'lp2', 'lp3', 'compensation-plan' ),
                ),
                'tabs'      => array(
                    // $prefix . 'page'    => __( 'Page', 'rwmb' ),
                    $prefix . 'video'   => __( 'Video', 'rwmb' ),
                    $prefix . 'menu'    => __( 'Menu', 'rwmb' ),
                ),
                'tab_style'		=> 'left',
                'tab_wrapper'	=> true,
                'fields' 	=> array(
                    array(
                        'name' 		=> 'Display Main Header Menu',
                        'id' 		=> $prefix . 'display_main_header_menu',
                        'type' 		=> 'select',
                        'desc'		=> 'Choose to show or hide the header',
                        'placeholder'	=> 'Default',
                        'options'	=> array(
                            'yes'		=> 'Yes',
                            'no'		=> 'No',
                        ),
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Main Header Menu',
                        'id' 			=> $prefix . 'main_header_menu',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 		=> 'Display Secondary Header Menu',
                        'id' 		=> $prefix . 'display_header_menu',
                        'type' 		=> 'select',
                        'desc'		=> 'Choose to show or hide the header',
                        'placeholder'	=> 'Default',
                        'options'	=> array(
                            'yes'		=> 'Yes',
                            'no'		=> 'No',
                        ),
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Secondary Header Menu',
                        'id' 			=> $prefix . 'secondary_header_menu',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Display Footer Menu',
                        'id' 			=> $prefix . 'display_footer_menu',
                        'type' 			=> 'select',
                        'desc'			=> 'Choose to show or hide the header',
                        'placeholder'	=> 'Default',
                        'options'		=> array(
                            'yes'		=> 'Yes',
                            'no'		=> 'No',
                        ),
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Footer Menu ( Far Left )',
                        'id' 			=> $prefix . 'footer_menu_fl',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Footer Menu ( Middle )',
                        'id' 			=> $prefix . 'footer_menu_mid',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name' 			=> 'Footer Menu ( Far Right )',
                        'id' 			=> $prefix . 'footer_menu_fr',
                        'type' 			=> 'taxonomy_advanced',
                        'taxonomy'		=> array('nav_menu'),
                        'field_type'	=> 'select',
                        'placeholder'	=> 'Default',
                        'tab'           => $prefix . 'menu',
                    ),
                    array(
                        'name'          => 'Video URL',
                        'id'            => $prefix . 'video_url',
                        'type'          => 'text',
                        'placeholder'   => '',
                        'tab'           => $prefix . 'video',
                        'before'        => '<div class="dash-alert dash-alert--warning" role="alert"><strong>This option only works</strong> if this "page template" has video embed hardcoded into page or if the video embed <br> shortcode is present in the content of this page</div>'
                    ),
                    array(
                        'name' 			=> 'Autostart Video',
                        'id' 			=> $prefix . 'video_autostart',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Disable Video Controls',
                        'id' 			=> $prefix . 'video_disable_controls',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Disable Related',
                        'id' 			=> $prefix . 'video_disable_related',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Hide Info',
                        'id' 			=> $prefix . 'video_hide_info',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    // array(
                    //     'name' 			=> 'Disable Sharing',
                    //     'id' 			=> $prefix . 'video_disable_sharing',
                    //     'type' 			=> 'checkbox_list',
                    //     'placeholder'	=> '',
                    //     'options'		=> array(
                    //         'yes'		=> '',
                    //     ),
                    //     'tab'           => $prefix . 'video',
                    // ),
                    // array(
                    //     'name' 			=> 'Hide Branding',
                    //     'id' 			=> $prefix . 'video_hide_branding',
                    //     'type' 			=> 'checkbox_list',
                    //     'placeholder'	=> '',
                    //     'options'		=> array(
                    //         'yes'		=> '',
                    //     ),
                    //     'tab'           => $prefix . 'video',
                    // ),
                    array(
                        'name' 			=> 'Scrolling Video',
                        'id' 			=> $prefix . 'video_scrolling',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                    array(
                        'name' 			=> 'Floating Video',
                        'id' 			=> $prefix . 'video_floating',
                        'type' 			=> 'checkbox_list',
                        'placeholder'	=> '',
                        'options'		=> array(
                            'yes'		=> '',
                        ),
                        'tab'           => $prefix . 'video',
                    ),
                ),
            );
            return $meta_boxes;
        }

        // MB - Email
        public function mb_emails($meta_boxes)
        {
            $prefix = '';
            $meta_boxes[] = array(
                'id'			=> 'email_custom_fields',
                'title' 		=> 'Email Details',
                'post_types'	=> array('fx_email'),
                'context'		=> 'advanced',
                'priority'		=> 'high',
                'autosave'		=> false,
                'fields'		=> array(
                    array(
                        'id'	=> $prefix . 'email_recipient_type',
                        'type'	=> 'select',
                        'name'	=> 'Recipient Type',
                        'options' => array(
                            'all'		=> 'All Users',
                            'group'		=> 'Group',
                            'product'	=> 'Product',
                            'individual'=> 'Individual'
                        )
                    ),
                    array(
                        'id'	=> $prefix . 'recipient_group',
                        'type'	=> 'select',
                        'name'	=> 'Group Type',
                        'options' => array(
                            'customer'		=> 'Customers',
                            'distributor'	=> 'Distributors',
                            'both'			=> 'Both'
                        ),
                        'visible' => array($prefix . 'email_recipient_type', 'group')
                    ),
                    array(
                        'id'	=> $prefix . 'recipient_product',
                        'type'	=> 'post',
                        'name'	=> 'Product',
                        'post_type' => 'product',
                        'field_type' => 'select_advanced',
                        'visible' => array($prefix . 'email_recipient_type', 'product')
                    ),
                    array(
                        'id'	=> $prefix . 'recipient_individual_type',
                        'type'	=> 'select',
                        'name'	=> 'Individual Type',
                        'options' => array(
                            'email'	=> 'Specified Email',
                            'user'	=> 'User'
                        ),
                        'visible' => array($prefix . 'email_recipient_type', 'individual')
                    ),
                    array(
                        'id'	=> $prefix . 'recipient_individual_name',
                        'type'	=> 'text',
                        'name'	=> 'Individual Name',
                        'visible' => array($prefix . 'recipient_individual_type', 'email')
                    ),
                    array(
                        'id'	=> $prefix . 'recipient_individual_email',
                        'type'	=> 'text',
                        'name'	=> 'Individual Email',
                        'visible' => array($prefix . 'recipient_individual_type', 'email')
                    ),
                    array(
                        'id'	=> $prefix . 'recipient_individual_user',
                        'type'	=> 'user',
                        'name'	=> 'Individual User',
                        'field_type' => 'select_advanced',
                        'visible' => array($prefix . 'recipient_individual_type', 'user')
                    ),
                    array(
                        'id'	=> $prefix . 'email_content',
                        'type'	=> 'wysiwyg',
                        'name'	=> 'Email Content'
                    )
                )
            );
            
            $meta_boxes[] = array(
                'id'			=> 'email_settings_fields',
                'title' 		=> 'Email Settings',
                'settings_pages'	=> array('email-settings'),
                'context'		=> 'advanced',
                'priority'		=> 'high',
                'autosave'		=> false,
                'fields'		=> array(
                    array(
                        'id'	=> $prefix . 'email_from_name',
                        'type'	=> 'text',
                        'name'	=> 'From Name'
                    ),
                    array(
                        'id'	=> $prefix . 'email_from_address',
                        'type'	=> 'text',
                        'name'	=> 'From Email'
                    ),
                ),
                'validation' => array(
                    'rules' => array(
                        $prefix.'email_from_name' => array(
                            'required' => true
                        ),
                        $prefix.'email_from_address' => array(
                            'required' => true,
                            'email' => true
                        ),
                    )
                )
            );

            return $meta_boxes;
        }

        // MB - SMS
        public function mb_sms($meta_boxes)
        {
            $prefix = '';
            $meta_boxes[] = array(
                'id'			=> 'sms_custom_fields',
                'title' 		=> 'SMS Details',
                'post_types'	=> array('fx_sms'),
                'context'		=> 'advanced',
                'priority'		=> 'high',
                'autosave'		=> false,
                'fields'		=> array(
                    array(
                        'id'	=> $prefix . 'sms_recipient_type',
                        'type'	=> 'select',
                        'name'	=> 'Recipient Type',
                        'options' => array(
                            'all'		=> 'All Users',
                            'group'		=> 'Group',
                            'product'	=> 'Product',
                            'individual'=> 'Individual'
                        )
                    ),
                    array(
                        'id'	=> $prefix . 'recipient_group',
                        'type'	=> 'select',
                        'name'	=> 'Group Type',
                        'options' => array(
                            'customer'		=> 'Customers',
                            'distributor'	=> 'Distributors',
                            'both'			=> 'Both'
                        ),
                        'visible' => array($prefix . 'sms_recipient_type', 'group')
                    ),
                    array(
                        'id'	=> $prefix . 'recipient_product',
                        'type'	=> 'post',
                        'name'	=> 'Product',
                        'post_type' => 'product',
                        'field_type' => 'select_advanced',
                        'visible' => array($prefix . 'sms_recipient_type', 'product')
                    ),
                    array(
                        'id'	=> $prefix . 'recipient_individual_type',
                        'type'	=> 'select',
                        'name'	=> 'Individual Type',
                        'options' => array(
                            'sms'	=> 'Specified Number',
                            'user'	=> 'User'
                        ),
                        'visible' => array($prefix . 'sms_recipient_type', 'individual')
                    ),
                    array(
                        'id'	=> $prefix . 'recipient_individual_sms',
                        'type'	=> 'text',
                        'name'	=> 'Individual Number',
                        'visible' => array($prefix . 'recipient_individual_type', 'sms')
                    ),
                    array(
                        'id'	=> $prefix . 'recipient_individual_user',
                        'type'	=> 'user',
                        'name'	=> 'Individual User',
                        'field_type' => 'select_advanced',
                        'visible' => array($prefix . 'recipient_individual_type', 'user')
                    ),
                    array(
                        'id'	=> $prefix . 'sms_content',
                        'type'	=> 'textarea',
                        'name'	=> 'SMS Content'
                    )
                )
            );
            
            $meta_boxes[] = array(
                'id'			=> 'email_settings_fields',
                'title' 		=> 'Email Settings',
                'settings_pages'	=> array('email-settings'),
                'context'		=> 'advanced',
                'priority'		=> 'high',
                'autosave'		=> false,
                'fields'		=> array(
                    array(
                        'id'	=> $prefix . 'email_from_name',
                        'type'	=> 'text',
                        'name'	=> 'From Name'
                    ),
                    array(
                        'id'	=> $prefix . 'email_from_address',
                        'type'	=> 'text',
                        'name'	=> 'From Email'
                    ),
                ),
                'validation' => array(
                    'rules' => array(
                        $prefix.'email_from_name' => array(
                            'required' => true
                        ),
                        $prefix.'email_from_address' => array(
                            'required' => true,
                            'email' => true
                        ),
                    )
                )
            );

            return $meta_boxes;
        }
        
        // MB - Course categories
        public function mb_course_categories($meta_boxes)
        {
            $prefix = '';
            $meta_boxes[] = array(
                'id'         => 'course_category_fields',
                'title'      => '',
                'taxonomies' => array( 'ld_course_category' ),
                'context'    => 'normal',
                'priority'   => 'high',
                'autosave'   => false,
                'fields' => array(
                    array(
                        'name'      => 'Status',
                        'id'        => $prefix . 'category_status',
                        'type'      => 'select',
                        //'placeholder'   => 'Default',
                        'options'   => array(
                            'published'        => 'published',
                            'draft'       => 'draft',
                        ),
                    ),
                ),
            );
            return $meta_boxes;
        }

        public function mb_settings_pages($settings_pages)
        {
            $settings_pages[] = array(
                'id'          => 'email-settings',
                'option_name' => 'email_settings',
                'menu_title'  => 'Email Settings',
            );
            return $settings_pages;
        }
    }

}

return new SettingsMB();
