<?php
/**
 * LearnDash ProPanel Shortcodes
 *
 * @package LearnDash_ProPanel_Activity
 * @since 2.0
 */
if ( !class_exists( 'LearnDash_ProPanel_Shortcodes_Activity' ) ) {
	class LearnDash_ProPanel_Shortcodes_Activity extends LearnDash_ProPanel_Activity {

		protected static $instance;

		/**
		 * Returns the *Singleton* instance of this class.
		 *
		 * @return LearnDash_ProPanel The *Singleton* instance.
		 */
		public static function get_instance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}

			return static::$instance;
		}

		/**
		  * Override class function for 'this'.
		  *
		  * This function handles out Singleton logic in 
		  * @return reference to current instance
		  */
		static function this() {
			return self::$instance;
		}

		/**
		 * LearnDash_ProPanel_Shortcodes constructor.
		 */
		public function __construct() {
			add_shortcode( 'ld_propanel_activity', array( $this, 'do_shortcode' ) );
		}

		function do_shortcode( $atts = array(), $content = '' ) {

			if ( ( learndash_is_group_leader_user( get_current_user_id() ) ) || ( learndash_is_admin_user( get_current_user_id() ) ) ) {
				
				$default_atts = array(
									'html_id'				=>	wp_create_nonce( 'learndash-propanel-shortcode-'. time() ),
									'html_class'			=>	'',
									'per_page' 				=> 	get_option( 'posts_per_page' ),
							        'filter_type' 			=> 	'',		// 	(optional) Should be single 'user' or 'course'
									'filter_id'				=>	'',		// 	(optional) The ID of the type to filter on. Will be a course ID or User ID. 
																		//	The value 'CURRENT_ID' can be used for current User or current Course (if on a course post)
									'export_buttons' 		=> 	1,
									'nav_top'				=>	1,
									'nav_bottom'			=>	1
							    );

				$atts = shortcode_atts( $default_atts, $atts );
				
				if ( ( !empty( $atts['filter_type'] ) ) && ( $atts['filter_type'] != 'user' ) && ( $atts['filter_type'] != 'course' ) ) {
					return $content;
				}

				if ( ( empty( $atts['filter_type'] ) ) && ( !empty( $atts['filter_id'] ) ) ) {
					return $content;
				}
				if ( ( empty( $atts['filter_id'] ) ) && ( !empty( $atts['filter_type'] ) ) ) {
					return $content;
				}
			
				//if ( ( $atts['filter_type'] == 'user' ) && ( $atts['filter_id'] == 'CURRENT_ID' ) ) {
				//	$atts['filter_id'] = get_current_user_id();
				//	if ( empty( $atts['filter_id'] ) ) {
				//		return $content;
				//	}
				//}

				//if ( ( $atts['filter_type'] == 'course' ) && ( $atts['filter_id'] == 'CURRENT_ID' ) ) {
				//	global $post, $learndash_post_types;
				//	if ( !( $post instanceof WP_Post ) || ( !in_array( $post->post_type, $learndash_post_types ) ) ) {
				//		return $content;
				//	}
				//	$course_id = $course_id = learndash_get_course_id( $post->ID );
				//	if ( empty( $course_id ) ) return $content;
				//	$atts['filter_id'] = $course_id;
				//}

				if ( learndash_is_group_leader_user( get_current_user_id() ) ) {
					if ( $atts['filter_type'] == 'user' ) {
						$groups_user_ids = array();
						$group_ids = learndash_get_administrators_group_ids( get_current_user_id() );
						if ( !empty( $group_ids ) ) {
							foreach( $group_ids as $group_id ) {
								$group_user_ids = learndash_get_groups_user_ids( $group_id );

								if ( $group_user_ids && is_array( $group_user_ids ) ) {
									$groups_user_ids = array_merge( $group_user_ids, $groups_user_ids );
								}
							}
						}
					
						if ( ( empty( $groups_user_ids ) ) || (!in_array( $atts['filter_id'], $groups_user_ids ) ) ) {
							return $content;
						}
					
					
					} else if ( $atts['filter_type'] == 'course' ) {
						$group_course_ids = array();
						$group_ids = learndash_get_administrators_group_ids( get_current_user_id() );							
						if ( !empty( $group_ids ) ) {
							$group_course_ids = learndash_get_groups_courses_ids( get_current_user_id(), $group_ids );
						}
						if ( ( empty( $group_course_ids ) ) || (!in_array( $atts['filter_id'], $group_course_ids ) ) ) {
							return $content;
						}
					}
				}

				// At this point we are a go to display something. so we load of the needed JS/CSS
				$ld_propanel = LearnDash_ProPanel::get_instance();
				$ld_propanel->scripts( true );

				$div_atts = array(
					'per_page'			=>	$atts['per_page'],
					'id'				=>	$atts['filter_id'],
					'type'				=>	$atts['filter_type'],
					'template'			=>	'activity_rows',
					'export_buttons'	=>	0, //$atts['export_buttons'],
					'nav_top'			=>	$atts['nav_top'],
					'nav_bottom'		=>	$atts['nav_bottom'],
				);

				$content .= '<div id="learndash-propanel-activity-shortcode-'. esc_html( $atts['html_id'] ) . '" class="learndash-propanel-activity-shortcode learndash-propanel-activity';
				if ( !empty( $atts['html_class'] ) )
					$content .= ' '. esc_html( $atts['html_class'] );
				$content .= '"';

				$content .= ' data-filters="'.  htmlspecialchars( json_encode( $div_atts, JSON_FORCE_OBJECT ) ) .'"';
			
				$content .= '></div>';
			}
			
			return $content;

		}
	}
}
