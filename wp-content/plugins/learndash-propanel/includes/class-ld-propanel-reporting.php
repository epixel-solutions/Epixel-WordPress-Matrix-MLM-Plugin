<?php
/**
 * LearnDash ProPanel Reporting
 *
 * @package LearnDash_ProPanel_Reporting
 * @since 2.0
 */

if ( !class_exists( 'LearnDash_ProPanel_Reporting' ) ) {
	class LearnDash_ProPanel_Reporting extends LearnDash_ProPanel_Widget {

		/**
		 * @var string
		 */
		protected $name;

		/**
		 * @var string
		 */
		protected $label;

		/**
		 * LearnDash_ProPanel_Reporting constructor.
		 */
		public function __construct() {
			$this->name = 'reporting';
			$this->label = esc_html__( 'ProPanel Reporting', 'ld_propanel' );

			parent::__construct();
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ), 1000 );
			add_filter( 'learndash_propanel_template_ajax', array( $this, 'reporting_template' ), 10, 2 );

			add_action( 'wp_ajax_learndash_propanel_user_search', array( $this, 'user_search' ) );
			add_action( 'wp_ajax_learndash_propanel_course_search', array( $this, 'course_search' ) );
			add_action( 'wp_ajax_learndash_propanel_get_filter_result_rows', array( $this, 'build_filter_result_rows' ) );
			add_action( 'wp_ajax_learndash_propanel_email_users', array( $this, 'ajax_email_users' ) );

			//add_action( 'admin_menu', array( $this, 'propanel_reporting_page' ) );
		}

		/**
		 *
		 */
		function scripts() {
			$screen = get_current_screen();

			if ( in_array( $screen->id, array( 'dashboard', 'dashboard_page_propanel-reporting' ) ) ) {
				$menu_user_cap = '';
				
				if ( learndash_is_admin_user() ) 
					$menu_user_cap = LEARNDASH_ADMIN_CAPABILITY_CHECK;
				else if ( learndash_is_group_leader_user() ) 
					$menu_user_cap = LEARNDASH_GROUP_LEADER_CAPABILITY_CHECK;
				
				if ( !empty( $menu_user_cap ) ) {
					// Specific code to deregister the BadgeOS version of select JS libs. This seems to 
					// cause a conflict with the version needed for PP on the Dashboard. 
					wp_deregister_script( 'badgeos-select2' );
					wp_deregister_style( 'badgeos-select2-css' );
				
					wp_enqueue_script( 'ld-propanel-select2' );
					wp_enqueue_script( 'ld-propanel-tablesorter' );
					wp_enqueue_script( 'ld-propanel-tablesorter-pager' );
					wp_enqueue_script( 'ld-propanel-tablesorter-widgets' );
					wp_enqueue_script( 'ld-propanel-tablesorter-widget-output' );
					wp_enqueue_script( 'ld-propanel-chartjs' );

					wp_enqueue_style( 'ld-propanel-select2' );

					wp_localize_script( 'ld-propanel', 'ld_propanel_reporting', array(
						/**
						 * Filter CSV Export File Name
						 */
						'filename' => apply_filters( 'ld_propanel_export_filename', 'learndash-report-' . current_time( 'Y-m-d' ) ) . '.csv',
						'ajax_email_error' => esc_html__( 'ProPanel Email: AJAX submission could not complete, please try again.', 'ld_propanel' ),
					) );
				} else {
					wp_deregister_script( 'ld-propanel-select2' );
					wp_deregister_script( 'ld-propanel-tablesorter' );
					wp_deregister_script( 'ld-propanel-tablesorter-pager' );
					wp_deregister_script( 'ld-propanel-tablesorter-widgets' );
					wp_deregister_script( 'ld-propanel-tablesorter-widget-output' );
					wp_deregister_script( 'ld-propanel-chartjs' );

					wp_deregister_style( 'ld-propanel-select2' );
					
				}
			}
		}

		/**
		 *
		 */
		function reporting_template( $output, $template ) {
			if ( 'reporting' == $template ) {
				ob_start();
				include ld_propanel_get_template( 'ld-propanel-reporting.php' );
				include ld_propanel_get_template( 'ld-propanel-choose-filter.php' );
				$output = ob_get_clean();
			}

			if ( 'course-reporting' == $template ) {
				$output = $this->build_course_table();
			}

			if ( 'user-reporting' == $template ) {
				$output = $this->build_user_table();
			}

			if ( 'download-reporting' == $template ) {
				$output = '';

				$reply_data = array( 'status' => false);
				if ( isset( $_GET['args'] ) )
					$post_data = $_GET['args'];
				else
					$post_data = array();

				if ( ( isset( $post_data['init'] ) ) && ( $post_data['init'] == '1' ) ) { 
					if ( ( isset( $post_data['filters']['type'] ) ) && ( !empty( $post_data['filters']['type'] ) ) 
					  && ( isset( $post_data['filters']['id'] ) ) && ( !empty( $post_data['filters']['id'] ) ) ) {
						if ( $post_data['filters']['type'] == 'user' ) {
							$group_course_ids = array();
							if ( learndash_is_group_leader_user( get_current_user_id() ) ) {
								$group_ids = learndash_get_administrators_group_ids( get_current_user_id() );							
								if ( !empty( $group_ids ) ) {
									$user_group_ids = learndash_get_users_group_ids( intval( $post_data['filters']['id'] ) );
									if ( !empty( $user_group_ids ) ) {
										$group_ids = array_intersect( $group_ids, $user_group_ids );
									} else {
										$group_ids = array();
									}
									
									if ( !empty( $group_ids ) ) {
										$group_course_ids = learndash_get_groups_courses_ids( get_current_user_id(), $group_ids );
									}
								}

								if ( !empty( $group_course_ids ) ) {
									$post_data['filters']['posts_ids'] = $group_course_ids;
									$post_data['filters']['users_ids'] = array( intval( $post_data['filters']['id'] ) );
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
									
									$course_group_ids = learndash_get_course_groups( intval( $post_data['filters']['id'] ) );
									if ( !empty( $course_group_ids ) ) {
										$group_ids = array_intersect( $group_ids, $course_group_ids );
									} else {
										$group_ids = array();
									}
									
									if ( !empty( $group_ids ) ) {
										foreach( $group_ids as $group_id ) {
											$group_user_ids = learndash_get_groups_user_ids( $group_id );

											if ( $group_user_ids && is_array( $group_user_ids ) ) {
												$groups_user_ids = array_merge( $group_user_ids, $groups_user_ids );
											}
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
				}
				
				if ( class_exists( 'Learndash_Admin_Settings_Data_Reports' ) ) {
					$ld_admin_settings_data_reports = new Learndash_Admin_Settings_Data_Reports;
					$reply_data['data'] = $ld_admin_settings_data_reports->do_data_reports( $post_data, $reply_data );
					
					if ( $post_data['filters']['type'] == 'user' ) {
						$reply_data['data']['filters']['posts_ids'] = array();
					} else if ( $post_data['filters']['type'] == 'course' ) {
						$reply_data['data']['filters']['users_ids'] = array();
					}				
					//$reply_data['data']['filters'] = array();
					//error_log('reply_data<pre>'. print_r($reply_data, true) .'</pre>');
					
					$output = $reply_data;
				}
			}

			return $output;
		}


		/**
		 *
		 */
		function user_search() {
			$users = array();

			$user_query_args = array(
				'search' => sprintf( '*%s*', $_GET['search'] ),
				'search_columns' => array( 'display_name' ),
				'orderby' => 'display_name',
				'order'	=> 'ASC',
				'number' => 10,
				'offset' => ( intval( $_GET['page'] ) - 1 ) * 10,
				'paged' => intval( $_GET['page'] ),
			);

			if ( learndash_is_admin_user( get_current_user_id() ) ) {
				$exclude_admin_users = apply_filters( 'ld_propanel_exclude_admin_users', true );
				if ( $exclude_admin_users == true ) {
					if ( !isset( $user_query_args['role__not_in'] ) ) $user_query_args['role__not_in'] = array();
					$user_query_args['role__not_in'][] = 'administrator';
				}

			} else if ( learndash_is_group_leader_user( get_current_user_id() ) ) {

				$group_ids = learndash_get_administrators_group_ids( get_current_user_id() );
				$groups_user_ids = array();

				foreach( $group_ids as $group_id ) {
					$group_user_ids = learndash_get_groups_user_ids( $group_id );

					if ( $group_user_ids && is_array( $group_user_ids ) ) {
						$groups_user_ids = array_merge( $group_user_ids, $groups_user_ids );
					}
				}
				
				if ( !empty( $groups_user_ids ) ) {
					$user_query_args['include'] = $groups_user_ids;			
				} else {
					$user_query_args = array();
				}
			}

			if ( !empty( $user_query_args ) ) {
				$user_query_args = apply_filters( 'ld_propanel_reporting_user_search_args', $user_query_args );
				if ( !empty( $user_query_args ) ) {
					$user_query = new WP_User_Query( $user_query_args );
					
					if ( ! empty( $user_query->results ) ) {
						foreach ( $user_query->get_results() as $user ) {
							$users[] = array(
								'id' => $user->ID,
								'text' => $user->display_name,
							);
						}
					}
				}
			}

			/**
			 * Filter users returned in search
			 */
			$users = apply_filters( 'ld_propanel_reporting_user_search_results', $users, $user_query_args );

			wp_send_json_success( array( 'total' => $user_query->get_total(), 'items' => $users ) );
			die();
		}


		/**
		 *
		 */
		function course_search() {

			$courses_data = array(
				'total'	=>	0,
				'items'	=>	array()
			);

			$args = array(
				'post_type' => 'sfwd-courses',
				'orderby' => 'post_title',
				'order' => 'ASC',
				's' => $_GET['search'],
				'posts_per_page' => 10,
				'offset' => ( intval( $_GET['page'] ) - 1 ) * 10,
				'paged' => intval( $_GET['page'] ),
			);

			if ( learndash_is_admin_user( get_current_user_id() ) ) {

			} else if ( learndash_is_group_leader_user( get_current_user_id() ) ) {
				$group_ids = learndash_get_administrators_group_ids( get_current_user_id() );
				if ( !empty( $group_ids ) ) {
					$course_ids = learndash_get_groups_courses_ids( get_current_user_id(), $group_ids );
					if ( !empty( $course_ids ) ) {
						$args['post__in'] = $course_ids;
					} else {
						$args = array();
					}
				} else {
					$args = array();
				}
			} else {
				$args = array();
			}
			
			if ( !empty( $args ) ) {
				$course_query = new WP_Query( $args );
				if ( $course_query->have_posts() ) {
					$courses_data['total'] = intval( $course_query->found_posts );
					
					foreach ( $course_query->posts as $course ) {
						$courses_data['items'][] = array(
							'id' => $course->ID,
							'text' => $course->post_title,
						);
					}
				}
			}

			/**
			 * Filter courses returned in search
			 */
			wp_send_json_success( apply_filters( 'ld_propanel_course_search', apply_filters( 'ld_propanel_course_search', $courses_data ) ) );
			die();
		}

		/**
		 *
		 */
		function full_reporting_page_output() {
			ob_start();
			$container_type = 'full';
			include ld_propanel_get_template( 'ld-propanel-full-reporting.php' );
			echo ob_get_clean();
		}

		/**
		 * @return string
		 */
		function build_course_table() {
			if ( isset( $_GET['filters']['type'] ) && 'course' == ( $_GET['filters']['type'] ) ) {
				ob_start();
				$container_type = $_GET['container_type'];
				include ld_propanel_get_template( 'ld-propanel-course-reporting.php' );
				return ob_get_clean();
			}
		}

		/**
		 * @return string
		 */
		function build_user_table() {
			if ( isset( $_GET['filters']['type'] ) && 'user' == ( $_GET['filters']['type'] ) ) {
				ob_start();
				$container_type = $_GET['container_type'];
				include ld_propanel_get_template( 'ld-propanel-user-reporting.php' );
				return ob_get_clean();
			}
		}

		/**
		 *
		 */
		function build_filter_result_rows() {
			check_ajax_referer( 'ld-propanel', 'nonce' );
			$current_filter_id = intval( $_GET['filters']['id'] );

			if (!isset($_GET['ld_pp_page'])) $_GET['ld_pp_page'] = 0;
			$_GET['ld_pp_page'] = intval( $_GET['ld_pp_page'] ) + 1;
			
			if ( 'course' == $_GET['filters']['type'] ) {
				$response = $this->build_course_users_rows_response( $current_filter_id );
			}

			if ( 'user' == $_GET['filters']['type'] ) {
				$response = $this->build_user_courses_rows_response( $current_filter_id );
			}

			wp_send_json( $response );
			die();
		}

		/**
		 * @param $course_id
		 *
		 * @return array
		 */
		function build_course_users_rows_response( $course_id ) {
			$course_status = $_GET['filters']['courseStatus'];
			$container_type = $_GET['container_type'];

			// Set the initial response. In case all following queries fail. 
			$response = array(
				'total_rows' => 0, 
				'rows_html' => array(),
				'user_ids' => array(),
			);

			$activity_query_args = array(
				'per_page' => isset( $_GET['ld_pp_size'] ) ? intval( $_GET['ld_pp_size'] ) : 10,
				'offset' => isset( $_GET['ld_pp_size'], $_GET['ld_pp_page'] ) ? intval( $_GET['ld_pp_size'] ) * intval( $_GET['ld_pp_page'] ) : 0,
				'paged' => ( intval( $_GET['ld_pp_page'] ) ) ? intval( $_GET['ld_pp_page'] ) : 1,
				//'user_ids' => $user_ids->results,
			);

			$activity_query_args['activity_status'] = array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' );
			if ( 'not-started' == $course_status ) {
				$activity_query_args['activity_status'] = 'NOT_STARTED';
			}

			if ( 'in-progress' == $course_status ) {
				$activity_query_args['activity_status'] = 'IN_PROGRESS';
			}

			if ( 'completed' == $course_status ) {
				$activity_query_args['activity_status'] = 'COMPLETED';
			}
			
			/**
			 * Build Search Args, column indexes are different on full vs widget
			 */
			if ( ( isset( $_GET['ld_pp_search'] ) ) && ( !empty( $_GET['ld_pp_search'] ) ) ) {
				if ( 'full' == $container_type && true == $_GET['ld_pp_search'][5] ) {
					$activity_query_args['s'] = isset( $_GET['ld_pp_search'][5] ) ? sprintf( '%%%s%%', esc_html( $_GET['ld_pp_search'][5] ) ) : null;
				}

				if ( 'widget' == $container_type && true == $_GET['ld_pp_search'][3] ) {
					$activity_query_args['s'] = isset( $_GET['ld_pp_search'][3] ) ? sprintf( '%%%s%%', esc_html( $_GET['ld_pp_search'][3] ) ) : null;
				}
			}

			/**
			 * Build Sort Args, column indexes are different on full vs widget
			 */
			if ( isset( $_GET['ld_pp_sort'][1] ) || isset( $_GET['ld_pp_sort'][2] ) ) {
				$activity_query_args['orderby_order'] = 'users.display_name DESC';

				if ( 'widget' == $container_type && true == $_GET['ld_pp_sort'][1] ) {
					$activity_query_args['orderby_order'] = 'users.display_name ASC';
				}

				if ( 'full' == $container_type && true == $_GET['ld_pp_sort'][2] ) {
					$activity_query_args['orderby_order'] = 'users.display_name ASC';
				}
			}

		
			/**
			 * Setup the response
			 */

			if ( 'widget' == $container_type ) {
				$response['headers'] = array( 
					'checkbox'	=>	__( 'Checkbox', 'ld_propanel' ), 
					'user'		=>	__( 'User', 'ld_propanel' ), 
					'progress'	=>	__( 'Progress', 'ld_propanel' ) );
			}

			if ( 'full' == $container_type ) {
				$response['headers'] = array( 
					'checkbox'		=>	__( 'Checkbox', 'ld_propanel' ), 
					'user_id'		=>	__( 'User ID', 'ld_propanel' ), 
					'user'			=>	__( 'User', 'ld_propanel' ), 
					'progress'		=>	__( 'Progress', 'ld_propanel' ), 
					'last_update'	=>	__( 'Last Update', 'ld_propanel' ) );
			}
		
		
			/**
			 * Collect all user ID's regardless of pagination to pass back to javascript
			 */
			if ( learndash_is_group_leader_user() ) {
				
				$groups_user_ids = array();

				$group_ids = learndash_get_administrators_group_ids( get_current_user_id() );
				if ( !empty( $group_ids ) ) {
					if ( !empty( $_GET['filters']['id'] ) ) {
						$course_group_ids = learndash_get_course_groups( intval( $_GET['filters']['id'] ) );
						if ( !empty( $course_group_ids ) ) {
							$group_ids = array_intersect( $group_ids, $course_group_ids );
						} else {
							$group_ids = array();
						}
					}

					if ( !empty( $group_ids ) ) {

						foreach( $group_ids as $group_id ) {
							$group_user_ids = learndash_get_groups_user_ids( $group_id );

							if ( !empty( $group_user_ids ) ) {
								$groups_user_ids = array_merge( $groups_user_ids, $group_user_ids );
							}
						}
					}
				}
				
				if ( !empty( $groups_user_ids ) ) {
					$activity_query_args['user_ids'] = $groups_user_ids;
				} else {
					// If we don't have any groups users then clear the activity query args array which will force an abort. 
					$activity_query_args = array();
				}
				
				
			} else if ( learndash_is_admin_user() ) {
				$exclude_admin_users = apply_filters( 'ld_propanel_exclude_admin_users', true );
				$user_ids = learndash_get_users_for_course( $course_id, array(), $exclude_admin_users );
				
				if ( !empty( $user_ids->results ) ) {
					$activity_query_args['user_ids'] = $user_ids->results;
				} else {
					// If we don't have any user_ids results then clear the activity query args array which will force an abort. 
					$activity_query_args = array();
				}
			}

			if ( !empty( $activity_query_args ) ) {
				/**
				 * Get the goodies
				 */
				$activities = learndash_report_course_users_progress( $course_id, array(), $activity_query_args );
				
				if ( ( isset( $activities['results'] ) ) && ( !empty( $activities['results'] ) ) ) {

					if ( ( isset( $activities['pager'] ) ) && ( !empty( $activities['pager'] ) ) ) {
						$response['total_rows'] = $activities['pager']['total_items'];
					}

					foreach ( $activities['results'] as $activity ) {
						$response['user_ids'][$activity->user_id] = $activity->user_id;

						$row = array();
						foreach ( $response['headers'] as $header => $label ) {
							ob_start();
							include ld_propanel_get_template( 'ld-propanel-course-user-row.php' );
							$row[ $label ] = ob_get_clean();
						}

						$response['rows'][] = $row;
					}
				}
			}
		
			if ( !empty( $response['headers'] ) ) 
				$response['headers'] = array_values( $response['headers'] );

			if ( !empty( $response['user_ids'] ) ) 
				$response['user_ids'] = array_values( $response['user_ids'] );
			
			// Just in case the pager returns empties
			if ( ( empty( $response['total_rows'] ) ) && ( count( $response['rows'] ) ) )
				$response['total_rows'] = count( $response['rows'] );
					
			return $response;
		}
		
		
		/**
		 * @param $user_id
		 *
		 * @return array
		 */
		function build_user_courses_rows_response( $user_id ) {
			$container_type = $_GET['container_type'];

			/**
			 * Build Course Query args
			 * Search args column indexes are different on full vs widget
			 */

			$course_query_args = array();

			/**
			 * Build Activity Query Args
			 */
			$activity_query_args = array();

			if ( 'full' == $container_type ) {
				$activity_query_args['s'] = ( isset( $_GET['ld_pp_search'][4] ) && ( !empty($_GET['ld_pp_search'][4] ) ) ) ? sprintf( '%%%s%%', esc_html( $_GET['ld_pp_search'][4] ) ) : null;
			}

			if ( 'widget' == $container_type ) {
				$activity_query_args['s'] = isset( $_GET['ld_pp_search'][2] ) && ( !empty( $_GET['ld_pp_search'][2] ))? sprintf( '%%%s%%', esc_html( $_GET['ld_pp_search'][2] ) ) : null;
			}

			if ( ( isset( $activity_query_args['s'] ) ) && ( !empty( $activity_query_args['s'] ) ) ) {
				$activity_query_args['s_context'] = 'post_title';
			}

			if ( isset( $_GET['ld_pp_size'] ) ) { 
				$activity_query_args['per_page'] = intval( $_GET['ld_pp_size'] );
			} else {
				$per_page_array = ld_propanel_get_pager_values();
				if ( ( !empty( $per_page_array ) ) && ( is_array( $per_page_array ) ) ) {
					$per_page_array = array_values( $per_page_array );
					$activity_query_args['per_page'] = $per_page_array[0];
				} else {
					$activity_query_args['nopaging'] = true;
				}
			}
			
			if ( isset( $_GET['ld_pp_page'] ) ) { 
				$activity_query_args['paged'] = intval( $_GET['ld_pp_page'] );
			} else {
				$activity_query_args['paged'] = 1;
			}

			$activity_query_args['activity_status'] = array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' );

			if ( ! empty( $_GET['filters']['courseStatus'] ) ) {
				$course_status = $_GET['filters']['courseStatus'];

				if ( 'all' == $course_status ) {
					$activity_query_args['activity_status'] = array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' );
				} else if ( 'not-started' == $course_status ) {
					$activity_query_args['activity_status'] = 'NOT_STARTED';
				} else if ( 'in-progress' == $course_status ) {
					$activity_query_args['activity_status'] = 'IN_PROGRESS';
				} else if ( 'completed' == $course_status ) {
					$activity_query_args['activity_status'] = 'COMPLETED';
				}
			}

			if ( learndash_is_group_leader_user( get_current_user_id() ) ) {
				
				$group_course_ids = array();
				$group_ids = learndash_get_administrators_group_ids( get_current_user_id() );
				if ( !empty( $group_ids ) ) {
					if ( !empty( $_GET['filters']['id'] ) ) {
						$user_group_ids = learndash_get_users_group_ids( intval( $_GET['filters']['id'] ) );
						if ( !empty( $user_group_ids ) ) {
							$group_ids = array_intersect( $group_ids, $user_group_ids );
						} else {
							$group_ids = array();
						}
					} 

					if (!empty( $group_ids ) ) {
						$group_course_ids = learndash_get_groups_courses_ids( get_current_user_id(), $group_ids );
					}
				}

				if ( !empty( $group_course_ids ) ) {
					$activity_query_args['post_ids'] = $group_course_ids;
				} else {
					// If here the group leader doesn't have any groups, courses or user_ids
					$activity_query_args = array();
				}
				
			} else if ( learndash_is_admin_user( get_current_user_id() ) ) {
			}

			/**
			 * Get the goodies
			 */

			$activities = learndash_report_user_courses_progress( $user_id, $course_query_args, $activity_query_args );
		
			/**
			 * Build the response
			 */
			$response = array(
				'total_rows' => 0,
				'rows' => array()
			);

			if ( 'widget' == $container_type ) {
				$response['headers'] = array( 
					'course'	=>	sprintf( _x( '%s', 'Course', 'ld_propanel' ), LearnDash_Custom_Label::get_label( 'courses' ) ),
					'progress'	=>	__( 'Progress', 'ld_propanel' )
				);
			}

			if ( 'full' == $container_type ) {
				$response['headers'] = array( 
					'course_id'		=>	sprintf( _x( '%s ID', 'Course ID', 'ld_propanel' ), LearnDash_Custom_Label::get_label( 'course' ) ),
					'course'		=>	sprintf( _x( '%s', 'Course', 'ld_propanel' ), LearnDash_Custom_Label::get_label( 'course' ) ), 
					'progress'		=>	__( 'Progress', 'ld_propanel' ), 
					'last_update'	=>	__( 'Last Update', 'ld_propanel' ) 
				);
			}

			if (( isset( $activities['results'] ) ) && ( !empty( $activities['results'] ) )) {
			
				if (( isset( $activities['pager'] ) ) && ( !empty( $activities['pager'] ) )) {
					$response['total_rows'] = $activities['pager']['total_items'];
				}
			
				foreach ( $activities['results'] as $activity ) {
					$row = array();

					foreach ( $response['headers'] as $header => $label ) {
						ob_start();
						include ld_propanel_get_template( 'ld-propanel-user-course-rows.php' );
						$row[ $label ] = ob_get_clean();
					}

					$response['rows'][] = $row;
				}
			}
			
			if ( !empty( $response['headers'] ) ) 
				$response['headers'] = array_values( $response['headers'] );
			
			// Just in case the pager returns empties
			if ( ( empty( $response['total_rows'] ) ) && ( count( $response['rows'] ) ) )
				$response['total_rows'] = count( $response['rows'] );
			
			return $response;
		}

		function get_course_users_column_width( $container_type = 'widget', $column ) {
			$full_widths = array(
				'checkbox' => '5%',
				'user_id' => '5%',
				'user' => '50%',
				'progress' => '30%',
				'last_update' => '10%',
			);

			$widget_widths = array(
				'checkbox' => '10%',
				'user' => '40%',
				'progress' => '50%',
			);

			if ( 'full' == $container_type ) {
				return $full_widths[ $column ];
			}

			if ( 'widget' == $container_type ) {
				return $widget_widths[ $column ];
			}
		}

		function get_user_courses_column_width( $container_type = 'widget', $column ) {
			$full_widths = array(
				'course_id' => '5%',
				'course' => '50%',
				'progress' => '35%',
				'last_update' => '10%',
			);

			$widget_widths = array(
				'course' => '40%',
				'progress' => '60%',
			);

			if ( 'full' == $container_type ) {
				return $full_widths[ $column ];
			}

			if ( 'widget' == $container_type ) {
				return $widget_widths[ $column ];
			}
		}

		/**
		 * @param array $user_ids
		 * @param $subject
		 * @param $message
		 *
		 * @return bool
		 */
		function email_users( $user_ids = array(), $subject, $message ) {
			$email_addresses = array();

			foreach ( $user_ids as $user_id ) {
				$user = get_user_by( 'id', $user_id );

				if ( $user ) {
					$email_addresses[] = sanitize_email( $user->user_email );
				}
			}

			if ( $email_addresses ) {
				return wp_mail( $email_addresses, $subject, $message );
			}

			return false;
		}

		/**
		 *
		 */
		function ajax_email_users() {
			check_ajax_referer( 'ld-propanel', 'nonce' );

			$user_ids = isset( $_POST['user_ids'] ) ? $_POST['user_ids'] : null;
			$filter = isset( $_POST['filter'] ) ? $_POST['filter'] : null;
			$subject = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
			$message = isset( $_POST['message'] ) ? esc_textarea( $_POST['message'] ) : '';

			$user_ids = array_map( 'intval', explode( ',', $user_ids ) );

			if ( ! empty( $user_ids ) ) {
				$result = $this->email_users( $user_ids, $subject, $message, $filter );

				if ( $result ) {
					wp_send_json_success();
				} else {
					wp_send_json_error( array( 'message' => esc_html__( 'We could not send the email successfully. Please try again or check with your hosting provider.', 'ld_propanel' ) ) );
				}
			} else {
				wp_send_json_error( array( 'message' => esc_html__( 'We do not have any email addresses to send your message to.', 'ld_propanel' ) ) );
			}

			die();
		}

	}
}
