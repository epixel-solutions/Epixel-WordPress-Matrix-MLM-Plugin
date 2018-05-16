<?php
/**
 * LearnDash ProPanel Activity
 *
 * @package LearnDash_ProPanel_Progress_Chart
 * @since 2.0
 */

if ( !class_exists( 'LearnDash_ProPanel_Progress_Chart' ) ) {
	class LearnDash_ProPanel_Progress_Chart extends LearnDash_ProPanel_Widget {

		/**
		 * @var string
		 */
		protected $name;
	
		/**
		 * @var string
		 */
		protected $label;

		/**
		 * LearnDash_ProPanel_Progress_Chart constructor.
		 */
		public function __construct() {
			$this->name = 'progress-chart';
			$this->label = esc_html__( 'ProPanel Progress Chart', 'ld_propanel' );
		
			parent::__construct();
			add_filter( 'learndash_propanel_template_ajax', array( $this, 'progress_chart_template' ), 10, 2 );
			add_action( 'wp_ajax_learndash_propanel_get_progress_charts_data', array( $this, 'get_progress_course_data_for_chart' ), 10, 2 );
		}

		public function progress_chart_template( $output, $template ) {
			if ( 'progress-chart' == $template ) {
				ob_start();
				include ld_propanel_get_template( 'ld-propanel-choose-filter.php' );
				$output = ob_get_clean();
			}

			if ( 'progress-chart-data' == $template ) {
				ob_start();
				include ld_propanel_get_template( 'ld-propanel-progress-chart.php' );
				$output = ob_get_clean();
			}

			return $output;
		}

		public function get_progress_course_data_for_chart() {
			check_ajax_referer( 'ld-propanel', 'nonce' );

			if ( 'course' == $_GET['filters']['type'] ) {
				$course_id = intval( $_GET['filters']['id'] );

				$activity_query_args = array(
				);

				if ( ( isset( $_GET['filters']['search'] ) ) && ( !empty( $_GET['filters']['search'] ) ) && ( ($_GET['filters']['search'] != '%%' ) ) ) {
					$activity_query_args['s'] = sprintf( '%%%s%%', esc_html( $_GET['filters']['search'] ) );
					$activity_query_args['s_context'] = array( 'display_name' );
				}
				
				$response = $this->get_status_breakdown( $course_id, $activity_query_args );
				wp_send_json_success( $response );
			}

			die();
		}

		function get_status_breakdown( $course_id, $activity_query_args ) {
			/*
			$percentage_breakdowns = array(
				20 => 0,
				40 => 0,
				60 => 0,
				80 => 0,
				100 => 0
			);
			*/
			
			$chart_info['all_progress'] = array();
			$chart_info['all_progress']['query'] = array(
				'not_started' => array(
					'label'					=>	__( 'Not Started', 'ld_propanel' ),
					'backgroundColor'		=>	"#2D97C5",
					'hoverBackgroundColor'	=>	"#2D97C5",
					'data'					=>	0
				),
				'in_progress' => array(
					'label'					=>	__( 'In Progress', 'ld_propanel' ),
					'backgroundColor'		=>	"#5BAED2",
					'hoverBackgroundColor'	=>	"#5BAED2",
					'data'					=>	0
				),
				'completed' => array(
					'label'					=>	__( 'Completed', 'ld_propanel' ),
					'backgroundColor'		=>	"#8AC5DF",
					'hoverBackgroundColor'	=>	"#8AC5DF",
					'data'					=>	0
				)
			);
			
			$chart_info['all_progress']['options'] = array(
				'tooltips' => array(
					'backgroundColor'	=>	"#3B3E44",
					'titleMarginBottom'	=>	15,
					'titleFontSize'		=>	18,
					'cornerRadius'		=> 	4,
					'bodyFontSize'		=> 	14,
					'xPadding'			=> 	10,
					'yPadding'			=> 	15,
					'bodySpacing'		=> 	10,
					'fontFamily'		=> 	"'Open Sans',sans-serif"
				),
				'legend'	=>	array(
					'display'	=>	true,
					'labels'	=>	array(
						'boxWidth'		=>	14,
						'fontFamily'	=>	"'Open Sans',sans-serif"
					)
				)
			);
			
			
			$chart_info['all_percentages'] = array();
			
			$chart_info['all_percentages']['query'] = array(
				'20' => array(
					'label'					=>	__( '< 20%', 'ld_propanel' ),
					'backgroundColor'		=>	"#2D97C5",
					'hoverBackgroundColor'	=>	"#2D97C5",
					'data'					=>	0
				),
				'40' => array(
					'label'					=>	__( '< 40%', 'ld_propanel' ),
					'backgroundColor'		=>	"#5BAED2",
					'hoverBackgroundColor'	=>	"#5BAED2",
					'data'					=>	0
				),
				'60' => array(
					'label'					=>	__( '< 60%', 'ld_propanel' ),
					'backgroundColor'		=>	"#8AC5DF",
					'hoverBackgroundColor'	=>	"#8AC5DF",
					'data'					=>	0
				),
				'80' => array(
					'label'					=>	__( '< 80%', 'ld_propanel' ),
					'backgroundColor'		=>	"#B9DCEB",
					'hoverBackgroundColor'	=>	"#B9DCEB",
					'data'					=>	0
				),
				'100' => array(
					'label'					=>	__( '< 100%', 'ld_propanel' ),
					'backgroundColor'		=>	"#E7F3F8",
					'hoverBackgroundColor'	=>	"#E7F3F8",
					'data'					=>	0
				)
			);
			
			$chart_info['all_percentages']['options'] = array(
				'tooltips' => array(
					'backgroundColor'	=>	"#3B3E44",
					'titleMarginBottom'	=>	15,
					'titleFontSize'		=>	18,
					'cornerRadius'		=> 	4,
					'bodyFontSize'		=> 	14,
					'xPadding'			=> 	10,
					'yPadding'			=> 	15,
					'bodySpacing'		=> 	10,
					'fontFamily'		=> 	"'Open Sans',sans-serif"
				),
				'legend'	=>	array(
					'display'	=>	true,
					'labels'	=>	array(
						'boxWidth'		=>	14,
						'fontFamily'	=>	"'Open Sans',sans-serif"
					)
				)
			);
			
			// Let the outside world change elements as needed BEFORE we run the queries. 
			$chart_info = apply_filters( 'ld_propanel_chart_info_query', $chart_info );
			
			
			if ( learndash_is_group_leader_user( get_current_user_id() ) ) {

				$group_ids = learndash_get_administrators_group_ids( get_current_user_id() );
				$groups_user_ids = array();

				foreach( $group_ids as $group_id ) {
					$group_user_ids = learndash_get_groups_user_ids( $group_id );

					if ( $group_user_ids && is_array( $group_user_ids ) ) {
						$groups_user_ids = array_merge( $group_user_ids, $groups_user_ids );
					}
				}
			
				if ( !empty( $groups_user_ids ) ) {
					$activity_query_args['user_ids'] = $groups_user_ids;			
				} else {
					$activity_query_args  = array();
				}
			} else {
				$activity_query_args['user_ids'] = array();
			}

			// We store the various query results for post processing logic like building the in_motion data 
			// sets based on the 'in_progress' query results.
			$activity_query_results = array();
			
			if ( !empty( $activity_query_args ) ) {
				
				// Build the 'all_progress' chart data from queries
				if (!empty( $chart_info['all_progress']['query'] ) ) {
					foreach( $chart_info['all_progress']['query'] as $chart_key => $chart_data ) {
						switch( $chart_key ) {
							case 'not_started':
								$activity_query_args['activity_status'] = 'NOT_STARTED';
								break;
								
							case 'in_progress':
								$activity_query_args['activity_status'] = 'IN_PROGRESS';
								break;
								
							case 'completed':
								$activity_query_args['activity_status'] = 'COMPLETED';
								break;
							
							default:
								$activity_query_args['activity_status'] = '';	
						}
						
						if ( !empty( $activity_query_args['activity_status'] ) ) {
							$activity_query_results[$chart_key] = learndash_report_course_users_progress( $course_id, array(), $activity_query_args );
							
							if ( isset( $activity_query_results[$chart_key]['pager']['total_items'] ) ) {
								$chart_info['all_progress']['query'][$chart_key]['data'] = intval( $activity_query_results[$chart_key]['pager']['total_items'] );
							}
						}
					}
				}
								
				// Now build the 'in_motion' chart data from the in_progress data results
				if ( ( isset( $activity_query_results['in_progress'] ) ) && ( !empty( $activity_query_results['in_progress'] ) ) ) {
					foreach ( $activity_query_results['in_progress']['results'] as $in_progress_user ) {
						$steps_total = LearnDash_ProPanel_Activity::get_activity_steps_total( $in_progress_user );
						$steps_completed = LearnDash_ProPanel_Activity::get_activity_steps_completed( $in_progress_user );
						
						if ( ( 0 != intval( $steps_total ) ) || ( 0 != $steps_completed ) ) {
						  	$this_percentage = 100 * ( intval( $steps_completed ) / intval( $steps_total ) );
						}

						foreach ( $chart_info['all_percentages']['query'] as $percentage_breakdown => $percentage_count ) {
							if ( intval($this_percentage) < intval( $percentage_breakdown ) ) {
								$chart_info['all_percentages']['query'][ $percentage_breakdown ]['data'] += 1;
								continue 2;
							}
						}
					}
				}
				
				$chart_info = apply_filters( 'ld_propanel_chart_info_results', $chart_info );

				if ( !empty( $chart_info['all_progress'] ) ) {
					
					// First we want to remove any empty items
					foreach( $chart_info['all_progress']['query'] as $key => $data ) {
						if ( empty( $data['data'] ) ) {
							unset( $chart_info['all_progress']['query'][$key] );
						}
					}
					
					$chart_info['all_progress']['data'] = array();
					$chart_info['all_progress']['data']['datasets'] = array();

					// Now we need to reorganize the array into what Chart.js needs. 
					if ( !empty(  $chart_info['all_progress']['query'] ) ) {

						$chart_info['all_progress']['data']['labels'] = wp_list_pluck( $chart_info['all_progress']['query'], 'label' );
						if ( ( !empty( $chart_info['all_progress']['data']['labels'] ) ) && ( is_array( $chart_info['all_progress']['data']['labels'] ) ) ) {
							$chart_info['all_progress']['data']['labels'] = array_values( $chart_info['all_progress']['data']['labels'] );
						}
					
						$chart_data = array();
						$chart_data['data'] = wp_list_pluck( $chart_info['all_progress']['query'], 'data' );
						if ( ( !empty( $chart_data['data'] ) ) && ( is_array( $chart_data['data'] ) ) ) {
							$chart_data['data'] = array_values( $chart_data['data'] );
						}

						$chart_data['backgroundColor'] = wp_list_pluck( $chart_info['all_progress']['query'], 'backgroundColor' );
						if ( ( !empty( $chart_data['backgroundColor'] ) ) && ( is_array( $chart_data['backgroundColor'] ) ) ) {
							$chart_data['backgroundColor'] = array_values( $chart_data['backgroundColor'] );
						}

						$chart_data['hoverBackgroundColor'] = wp_list_pluck( $chart_info['all_progress']['query'], 'hoverBackgroundColor' );
						if ( ( !empty( $chart_data['hoverBackgroundColor'] ) ) && ( is_array( $chart_data['hoverBackgroundColor'] ) ) ) {
							$chart_data['hoverBackgroundColor'] = array_values( $chart_data['hoverBackgroundColor'] );
						}

						if ( !empty( $chart_data ) )
							$chart_info['all_progress']['data']['datasets'][] = $chart_data;
					}
					
					unset( $chart_info['all_progress']['query'] );
				}
			}

			if ( !empty( $chart_info['all_percentages'] ) ) {

				// First we want to remove any empty items
				foreach( $chart_info['all_percentages']['query'] as $key => $data ) {
					if ( empty( $data['data'] ) ) {
						unset( $chart_info['all_percentages']['query'][$key] );
					}
				}
				
				$chart_info['all_percentages']['data'] = array();
				$chart_info['all_percentages']['data']['datasets'] = array();

				// Now we need to reorganize the array into what Chart.js needs. 
				if ( !empty(  $chart_info['all_percentages']['query'] ) ) {
			
					$chart_data = array();

					$chart_info['all_percentages']['data']['labels'] = wp_list_pluck( $chart_info['all_percentages']['query'], 'label' );
					if ( ( !empty( $chart_info['all_percentages']['data']['labels'] ) ) && ( is_array( $chart_info['all_percentages']['data']['labels'] ) ) ) {
						$chart_info['all_percentages']['data']['labels'] = array_values( $chart_info['all_percentages']['data']['labels'] );
					}
				
					$chart_data['data'] = wp_list_pluck( $chart_info['all_percentages']['query'], 'data' );
					if ( ( !empty( $chart_data['data'] ) ) && ( is_array( $chart_data['data'] ) ) ) {
						$chart_data['data'] = array_values( $chart_data['data'] );
					}

					$chart_data['backgroundColor'] = wp_list_pluck( $chart_info['all_percentages']['query'], 'backgroundColor' );
					if ( ( !empty( $chart_data['backgroundColor'] ) ) && ( is_array( $chart_data['backgroundColor'] ) ) ) {
						$chart_data['backgroundColor'] = array_values( $chart_data['backgroundColor'] );
					}

					$chart_data['hoverBackgroundColor'] = wp_list_pluck( $chart_info['all_percentages']['query'], 'hoverBackgroundColor' );
					if ( ( !empty( $chart_data['hoverBackgroundColor'] ) ) && ( is_array( $chart_data['hoverBackgroundColor'] ) ) ) {
						$chart_data['hoverBackgroundColor'] = array_values( $chart_data['hoverBackgroundColor'] );
					}
				
					if ( !empty( $chart_data ) )
						$chart_info['all_percentages']['data']['datasets'][] = $chart_data;
				}

				unset( $chart_info['all_percentages']['query'] );
			}
			
			return $chart_info;
		}
	}
}
