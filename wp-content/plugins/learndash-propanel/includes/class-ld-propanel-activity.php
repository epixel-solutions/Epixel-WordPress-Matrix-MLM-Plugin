<?php
/**
 * LearnDash ProPanel Activity
 *
 * @package LearnDash_ProPanel_Activity
 * @since 2.0
 */
if ( !class_exists( 'LearnDash_ProPanel_Activity' ) ) {
	class LearnDash_ProPanel_Activity extends LearnDash_ProPanel_Widget {

		/**
		 * @var string
		 */
		protected $name;
	
		/**
		 * @var string
		 */
		protected $label;

		/**
		 * LearnDash_ProPanel_Activity constructor.
		 */
		public function __construct() {
			$this->name = 'activity';
			$this->label = esc_html__( 'ProPanel Activity', 'ld_propanel' );

			parent::__construct();
			add_filter( 'learndash_propanel_template_ajax', array( $this, 'activity_template' ), 10, 2 );
			add_filter( 'learndash_propanel_template_ajax', array( $this, 'activity_template_rows' ), 10, 2 );

			add_filter( 'learndash_data_reports_headers', array( $this, 'learndash_data_reports_headers' ), 10, 2 );
		}

		/**
		 * Initial Activity Template
		 *
		 * @param $output
		 * @param $template
		 *
		 * @return string
		 */
		public function activity_template( $output, $template ) {
			if ( 'activity' == $template ) {

				ob_start();
				include ld_propanel_get_template( 'ld-propanel-choose-filter.php' );
				$output = ob_get_clean();
				
			} else if (( 'activity-courses' == $template ) || ( 'activity-quizzes' == $template )) {

				// To handle the Activy Courses and Quizzes report output we hook into the LearnDash core reporting function. 
				// It does all the heave processing for us. 
				$reply_data = array( 'status' => false);
				if ( isset( $_GET['args'] ) )
					$post_data = $_GET['args'];
				else
					$post_data = array();
				

				// For now we remove search. Not supports
				if ( ( isset( $post_data['filters'] ) ) && ( !empty( $post_data['filters'] ) ) ) {
					foreach( $post_data['filters'] as $filter_key => $filter_val ) {
						if ( ( $filter_key == 'courseStatus') || ( $filter_key == 'type') || ( $filter_key == 'id') ) {
							if ( empty( $filter_val ) ) {
								unset( $post_data['filters'][$filter_key] );
							}
						} else {
							unset( $post_data['filters'][$filter_key] );
						}
					}
				}
				
				if ( ( isset( $post_data['filters'] ) ) && ( empty( $post_data['filters'] ) ) ) {
					unset( $post_data['filters'] );
				}

				if ( ( isset( $post_data['filters']['type'] ) ) && ( !empty( $post_data['filters']['type'] ) ) 
				  && ( isset( $post_data['filters']['id'] ) ) && ( !empty( $post_data['filters']['id'] ) ) ) {
					if ( $post_data['filters']['type'] == 'user' ) {
						if ( learndash_is_group_leader_user( get_current_user_id() ) ) {
							$group_course_ids = array();
							$group_ids = learndash_get_administrators_group_ids( get_current_user_id() );							
							if ( !empty( $group_ids ) ) {
								$group_course_ids = learndash_get_groups_courses_ids( get_current_user_id(), $group_ids );
							}
							if ( !empty( $group_course_ids ) ) {
								$post_data['filters']['posts_ids'] = $group_course_ids;
							} else {
								// If here the group leader doesn't have any groups, courses or user_ids
								return $output;
							}
							
						} else if ( learndash_is_admin_user( get_current_user_id() ) ) {
							$post_data['filters']['users_ids'] = array( intval( $post_data['filters']['id'] ) );
							$post_data['filters']['posts_ids'] = array();
							unset( $post_data['filters']['id'] );
						}
						
					} else if ( $post_data['filters']['type'] == 'course' ) {
						$post_data['filters']['posts_ids'] = array( intval( $post_data['filters']['id'] ) );
						
						if ( learndash_is_group_leader_user( get_current_user_id() ) ) {
							$group_ids = learndash_get_administrators_group_ids( get_current_user_id() );
							$groups_user_ids = array();
							
							if ( !empty( $group_ids ) ) {
								foreach( $group_ids as $group_id ) {
									$group_user_ids = learndash_get_groups_user_ids( $group_id );

									if ( $group_user_ids && is_array( $group_user_ids ) ) {
										$groups_user_ids = array_merge( $group_user_ids, $groups_user_ids );
									}
								}
							}
							if ( !empty( $groups_user_ids ) ) {
								$post_data['filters']['users_ids'] = $groups_user_ids;			
							} else {
								// If here the group leader doesn't have any groups, courses or user_ids
								return $output;
							}
							
						} else if ( learndash_is_admin_user( get_current_user_id() ) ) {
						
							$course_user_query = learndash_get_users_for_course( intval( $post_data['filters']['id'] ) );
							if ( $course_user_query instanceof WP_User_Query ) {	
								$post_data['filters']['users_ids'] = $course_user_query->get_results();
							}
						}
					}
				}
				
				//$post_data['filters']['activity_status'] = array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' );
				if ( ( isset( $post_data['filters']['courseStatus'] ) ) && ( !empty( $post_data['filters']['courseStatus'] ) ) ) {
					$courseStatus = esc_attr( $post_data['filters']['courseStatus'] ); 
					unset( $post_data['filters']['courseStatus'] );
					
					if ( $courseStatus == 'all' )
						$post_data['filters']['activity_status'] = array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' );
					else if ( $courseStatus == 'not-started' )
						$post_data['filters']['activity_status'] = array( 'NOT_STARTED' );
					if ( $courseStatus == 'in-progress' )
						$post_data['filters']['activity_status'] = array( 'IN_PROGRESS' );
					if ( $courseStatus == 'completed' )
						$post_data['filters']['activity_status'] = array( 'COMPLETED' );
				}
				
				
				if ( class_exists( 'Learndash_Admin_Settings_Data_Reports' ) ) {
					$ld_admin_settings_data_reports = new Learndash_Admin_Settings_Data_Reports;
					$reply_data['data'] = $ld_admin_settings_data_reports->do_data_reports( $post_data, $reply_data );
					$output = $reply_data;
				}
			}

			return $output;
		}
		
		
		/**
		 * Override the LearnDash core reporting column headers. 
		 *
		 * @param $data_headers array of headers. See notes below for exact structure
		 * @param $data_slug stirng for the type of report 'user-courses' or 'user-quizzes'
		 *
		 * @return $data_headers array 
		 *
		 * The follow is an example of the data structure used for the headers. Note this is NOT
		 * a simple key/value array. 
		 * $data_headers['user_id']  = 	array( 
		 *										'label'		=>	'user_id',
		 *										'default'	=>	'',
		 *										'display'	=>	array( $this, 'report_header_user_id' )
		 *									);
		 *
		 * 'label' This is used in place of the array item key for the column header value.
		 * 'default' This is the default value of the field
		 * 'display' This should be a callback function to handle the value determination
		*/
		function learndash_data_reports_headers( $data_headers, $data_slug ) {
			
			if ( $data_slug == 'user-courses' ) {
				if ( !isset( $data_headers['course_started_on'] ) ) {
					$data_headers['course_started_on'] = array(
						'label'		=>	'course_started_on',
						'default'	=>	'',
						'display'	=>	array( $this, 'learndash_courses_report_display_column' )
					);
				}

				/*
				if ( !isset( $data_headers['course_updated_on'] ) ) {
					$data_headers['course_updated_on'] = array(
						'label'		=>	'course_updated_on',
						'default'	=>	'',
						'display'	=>	array( $this, 'learndash_courses_report_display_column' )
					);
				}
				*/
				
				if ( !isset( $data_headers['course_total_time_on'] ) ) {
					$data_headers['course_total_time_on'] = array(
						'label'		=>	__( 'course_total_time_on', 'ld_propanel' ),
						'default'	=>	'',
						'display'	=>	array( $this, 'learndash_courses_report_display_column' )
					);
				}

				if ( !isset( $data_headers['course_last_step_id'] ) ) {
					$data_headers['course_last_step_id'] = array(
						'label'		=>	__( 'course_last_step_id', 'ld_propanel' ),
						'default'	=>	'',
						'display'	=>	array( $this, 'learndash_courses_report_display_column' )
					);
				}

				if ( !isset( $data_headers['course_last_step_type'] ) ) {
					$data_headers['course_last_step_type'] = array(
						'label'		=>	__( 'course_last_step_type', 'ld_propanel' ),
						'default'	=>	'',
						'display'	=>	array( $this, 'learndash_courses_report_display_column' )
					);
				}

				if ( !isset( $data_headers['course_last_step_title'] ) ) {
					$data_headers['course_last_step_title'] = array(
						'label'		=>	__( 'course_last_step_title', 'ld_propanel' ),
						'default'	=>	'',
						'display'	=>	array( $this, 'learndash_courses_report_display_column' )
					);
				}

				if ( !isset( $data_headers['last_login_date'] ) ) {
					$data_headers['last_login_date'] = array(
						'label'		=>	__( 'last_login_date', 'ld_propanel' ),
						'default'	=>	'',
						'display'	=>	array( $this, 'learndash_courses_report_display_column' )
					);
				}
			} else if ( $data_slug == 'user-quizzes' ) {

			}

			return $data_headers;
		}
		
		function learndash_courses_report_display_column( $header_output = '', $header_key, $activity, $report_user ) {
			include ld_propanel_get_template( 'ld-propanel-reporting-columns.php' );
			return $header_output;
		}
		
		
		/**
		 * Build Activity Rows
		 *
		 * @param $output
		 * @param $template
		 *
		 * @return string
		 */
		public function activity_template_rows( $output, $template ) {
			if (( 'activity_rows' == $template ) || ( 'activity' == $template )) {
				$output = '';

				if ( ! isset( $_GET['filters']['type'] ) || ! isset( $_GET['filters']['id'] ) ) {
					return $output;
				}

				if ( ( !isset( $_GET['args'] ) ) || ( empty( $_GET['args'] ) ) ) $_GET['args'] = array();
				if ( !isset( $_GET['args']['per_page'] ) ) {
					// If we don't get the per_page argument we get the first item from out function. 
					$per_page_array = ld_propanel_get_pager_values();
					if ( ( !empty( $per_page_array ) ) && ( is_array( $per_page_array ) ) ) {
						$per_page_array = array_values( $per_page_array );
						$_GET['args']['per_page'] = $per_page_array[0];
					}
				}

				if (( !isset( $_GET['filters']['export_buttons'] ) ) || (( $_GET['filters']['export_buttons'] != true) && ( $_GET['filters']['export_buttons'] != false ) ) )
					$_GET['filters']['export_buttons'] = true;

				if (( !isset( $_GET['filters']['nav_top'] ) ) || (( $_GET['filters']['nav_top'] != true) && ( $_GET['filters']['nav_top'] != false ) ) )
					$_GET['filters']['nav_top'] = true;

				if (( !isset( $_GET['filters']['nav_bottom'] ) ) || (( $_GET['filters']['nav_bottom'] != true) && ( $_GET['filters']['nav_bottom'] != false ) ) )
					$_GET['filters']['nav_bottom'] = true;

				/**
				 * Build $activity_query_args from info passed as AJAX
				 */
				$activity_query_args = array(
					'per_page' 			=> 	abs( intval( $_GET['args']['per_page'] ) ),
					'activity_status' 	=> 	array( 'IN_PROGRESS', 'COMPLETED' ), // We are only showing completed items for now
					'activity_types'	=>	array('course', 'quiz', 'lesson', 'topic'),	
					'post_types'		=>	array('sfwd-courses', 'sfwd-quiz', 'sfwd-lessons', 'sfwd-topic'),
					'orderby_order'		=>	'ld_user_activity.activity_updated DESC',
					'export_buttons'	=>	$_GET['filters']['export_buttons'],
					'nav_top'			=>	$_GET['filters']['nav_top'],
					'nav_bottom'		=>	$_GET['filters']['nav_bottom'],
				);

				$current_filter = esc_html( $_GET['filters']['type'] );
				$current_filter_id = intval( $_GET['filters']['id'] );
				$course_status = $_GET['filters']['courseStatus'];

				if ( 'course' == $current_filter ) {
					//$activity_query_args['post_ids'] = array( $current_filter_id );
					// If we are filtering by a course ID, then we want to include all steps within that Course for the Activity. 
					$activity_query_args['post_ids'] = learndash_get_course_steps( $current_filter_id, array( 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz' ) );
					$activity_query_args['post_ids'][] = $current_filter_id;
				}

				if ( 'user' == $current_filter ) {
					$activity_query_args['user_ids'] = array( $current_filter_id );
				}

				if ( ( !isset( $activity_query_args['user_ids'] ) ) || ( empty( $activity_query_args['user_ids'] ) ) )
					$activity_query_args['user_ids'] = learndash_get_report_user_ids();

				if ( ! empty( $_GET['filters']['search'] ) ) {
					$activity_query_args['s'] = sprintf( '%%%s%%', esc_html( $_GET['filters']['search'] ) );
					$activity_query_args['s_context'] = 'display_name';
				}

				$paged = 1;

				if ( isset( $_GET['args']['paged'] ) && ! empty( $_GET['args']['paged'] ) ) {
					$activity_query_args['paged'] = abs( intval( $_GET['args']['paged'] ) );
					$paged = intval( $_GET['args']['paged'] );
				}
			
				$activity_query_args['date_format'] = 'Y-m-d H:i:s';
			
				$activity_query_args = apply_filters( 'ld_propanel_activity_widget_query_args', $activity_query_args, $template );
				if ( !empty( $activity_query_args ) ) {
					$activities = learndash_reports_get_activity( $activity_query_args );
					ob_start();
					if ( empty( $activities['results'] ) ) {
						include ld_propanel_get_template( 'ld-propanel-no-results.php' );
					} else {
						if ( $activity_query_args['export_buttons'] == true )
							include ld_propanel_get_template( 'ld-propanel-activity-report-header.php' );
					
						if ( $activity_query_args['nav_top'] == true )
							include ld_propanel_get_template( 'ld-propanel-activity-pagination.php' );

						$activity_row_date_time_format = apply_filters('ld_propanel_activity_row_date_time_format', get_option('date_format') .' '. get_option('time_format'));

						foreach ( $activities['results'] as $activity ) {
							$activity->activity_started_formatted = get_date_from_gmt( date( 'Y-m-d H:i:s', $activity->activity_started ), 'Y-m-d H:i:s' ); 
							$activity->activity_started_formatted = date_i18n( $activity_row_date_time_format, strtotime( $activity->activity_started_formatted ), false);

							$activity->activity_completed_formatted = get_date_from_gmt( date( 'Y-m-d H:i:s', $activity->activity_completed ), 'Y-m-d H:i:s' ); 
							$activity->activity_completed_formatted = date_i18n( $activity_row_date_time_format, strtotime( $activity->activity_completed_formatted ), false);

							$activity->activity_updated_formatted = get_date_from_gmt( date( 'Y-m-d H:i:s', $activity->activity_updated ), 'Y-m-d H:i:s' ); 
							$activity->activity_updated_formatted = date_i18n( $activity_row_date_time_format, strtotime( $activity->activity_updated_formatted ), false);
							
							include ld_propanel_get_template( 'ld-propanel-activity-rows.php' );
						}

						if ( $activity_query_args['nav_bottom'] == true )
							include ld_propanel_get_template( 'ld-propanel-activity-pagination.php' );
					}

					$output = ob_get_clean();
				}
			}

			return $output;
		}

		/**
		 * @param $activity
		 *
		 * @return mixed
		 */
		public static function get_activity_steps_completed( $activity ) {
			if ( ( !empty( $activity ) ) && ( property_exists( $activity, 'activity_meta' ) ) && ( isset( $activity->activity_meta['steps_completed'] ) ) ) {
				return intval($activity->activity_meta['steps_completed']);
			}
		}

		/**
		 * @param $activity
		 *
		 * @return mixed
		 */
		public static function get_activity_steps_total( $activity ) {
			if ( ( !empty( $activity ) ) && ( property_exists( $activity, 'activity_meta' ) ) && ( isset( $activity->activity_meta['steps_total'] ) ) ) {
				return intval($activity->activity_meta['steps_total']);
			}
		}

		/**
		 * @param $activity
		 *
		 * @return array|null|WP_Post
		 */
		function get_activity_course( $activity ) {
			$course_id = learndash_get_course_id( $activity->post_id );
			$course = get_post( $course_id );

			if ( $course ) {
				return $course;
			}
		}

		/**
		 * @param $activity
		 *
		 * @return bool
		 */
		function quiz_activity_is_pending( $activity ) {
			if ( ( !empty( $activity ) ) && ( property_exists( $activity, 'activity_meta' ) ) ) {

				if ( ( isset( $activity->activity_meta['has_graded'] ) ) 
				  && ( true === $activity->activity_meta['has_graded'] ) 
				  && ( true === LD_QuizPro::quiz_attempt_has_ungraded_question( $activity->activity_meta ) ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * @param $activity
		 *
		 * @return bool
		 */
		function quiz_activity_is_passing( $activity ) {
			if ( ( !empty( $activity ) ) && ( property_exists( $activity, 'activity_meta' ) ) ) {

				if ( isset( $activity->activity_meta['pass'] ) ) {
					return (bool) $activity->activity_meta['pass'];
				}
			}

			return false;
		}

		/**
		 * @param $activity
		 *
		 * @return mixed
		 */
		function quiz_activity_score( $activity ) {
			if ( ( !empty( $activity ) ) && ( property_exists( $activity, 'activity_meta' ) ) ) {
				if ( isset( $activity->activity_meta['score'] ) ) {
					return $activity->activity_meta['score'];
				}
			}
		}

		/**
		 * @param $activity
		 *
		 * @return mixed
		 */
		function quiz_activity_total_points( $activity ) {
			if ( ( !empty( $activity ) ) && ( property_exists( $activity, 'activity_meta' ) ) ) {
				if ( isset( $activity->activity_meta['total_points'] ) ) {
					return intval($activity->activity_meta['total_points']);
				}
			}
		}

		/**
		 * @param $activity
		 *
		 * @return mixed
		 */
		function quiz_activity_awarded_points( $activity ) {
			if ( ( !empty( $activity ) ) && ( property_exists( $activity, 'activity_meta' ) ) ) {
				if ( isset( $activity->activity_meta['points'] ) ) {
					return intval($activity->activity_meta['points']);
				}
			}
		}

		/**
		 * @param $activity
		 *
		 * @return int
		 */
		function quiz_activity_points_percentage( $activity ) {
			$awarded_points = intval( $this->quiz_activity_awarded_points( $activity ) );
			$total_points = intval( $this->quiz_activity_total_points( $activity ) );
			if ( ( !empty( $awarded_points ) ) && ( !empty( $total_points ) ) ) {
				return round( 100 * ( intval( $awarded_points ) / intval( $total_points ) ) );
			}
		}




		/**
		 * @param $activity
		 *
		 * @return mixed
		 */
		function quiz_activity_total_score( $activity ) {
			if ( ( !empty( $activity ) ) && ( property_exists( $activity, 'activity_meta' ) ) ) {
				if ( isset( $activity->activity_meta['count'] ) ) {
					return intval($activity->activity_meta['count']);
				}
			}
		}

		/**
		 * @param $activity
		 *
		 * @return mixed
		 */
		function quiz_activity_awarded_score( $activity ) {
			if ( ( !empty( $activity ) ) && ( property_exists( $activity, 'activity_meta' ) ) ) {
				if ( isset( $activity->activity_meta['score'] ) ) {
					return intval($activity->activity_meta['score']);
				}
			}
		}

		/**
		 * @param $activity
		 *
		 * @return int
		 */
		function quiz_activity_score_percentage( $activity ) {
			$awarded_score = intval( $this->quiz_activity_awarded_score( $activity ) );
			$total_score = intval( $this->quiz_activity_total_score( $activity ) );
			if ( ( !empty( $awarded_score ) ) && ( !empty( $total_score ) ) ) {
				return round( 100 * ( intval( $awarded_score ) / intval( $total_score ) ) );
			}
		}


		function get_quiz_scoring( $activity ) {
			return null;
		}

	}
}
//$learndash_propanel_activity = new LearnDash_ProPanel_Activity();