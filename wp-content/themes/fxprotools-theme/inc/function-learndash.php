<?php
/**
 * -----------------------
 * Fxprotools - Custom functions for learndash lms
 * -----------------------
 * custom functions for queries
 */

add_action('wp_ajax_nopriv_lms_lesson_complete', 'lms_lesson_complete');
add_action('wp_ajax_lms_lesson_complete', 'lms_lesson_complete');

function lms_lesson_complete()
{
	$user_id = get_current_user_id();
	$lesson_id = isset( $_POST['lesson_id'] ) ? $_POST['lesson_id'] : 0;

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
		echo learndash_is_lesson_complete( $user_id , $lesson_id );
	}
	wp_die();
}

function get_courses_by_product_id($product_id)
{
	$courses_ids = get_post_meta($product_id , '_related_course');
	$courses     = array();
	if($courses_ids){
		foreach($courses_ids as $id){
			$courses[] = get_post($id[0]);
		}
	}
	return $courses;
}

function get_courses_by_category_id($category_id)
{
	$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'menu_order',
			'order'			   => 'ASC',
			'post_status'      => 'publish',
			'post_type'		   => 'sfwd-courses',
			'tax_query' => array(
			array(
				'taxonomy'    	 => 'ld_course_category',
				'field'  		 => 'term_id',
				'terms'			 => $category_id,
			),
		),
	);
	$courses = get_posts($args);
	return !$courses ? false : $courses;
}

function get_course_metadata($course_id)
{
	return get_post_meta( $course_id, '_sfwd-courses', true );
}

function get_course_price_by_id($course_id)
{
	$course_data = get_course_metadata($course_id);
	$price = $course_data['sfwd-courses_course_price'];
	return is_numeric($price) ? $price : 0;
}

function get_lessons_by_course_id($course_id)
{
	$orderby = learndash_get_setting( $course_id, 'course_lesson_orderby' );
	$order   = learndash_get_setting( $course_id, 'course_lesson_order' );
	$args = array(
			'posts_per_page'   => -1,
			'orderby'          => $orderby,
			'order'			   => $order,
			'post_status'      => 'publish',
			'post_type'		   => 'sfwd-lessons',
			'meta_query' => array(
			array(
				'key'     => 'course_id',
				'value'   => $course_id,
				'compare' => '=',
			),
		),
	);
	$lessons = get_posts($args);
	return !$lessons ? false : $lessons;
}

function get_user_progress()
{
	if(!is_user_logged_in()) return false;
	$current_user    = wp_get_current_user();
	$user_id         = $current_user->ID;
	$course_progress = get_user_meta( $user_id, '_sfwd-course_progress', true );
	return !$course_progress ? false : $course_progress;
}

function get_course_lesson_progress($course_id, $lesson_id)
{
	if(!$course_id || !$lesson_id) return false;
	$course_progress = get_user_progress();
	return $course_progress[$course_id]['lessons'][$lesson_id];
}

function get_lesson_parent_course($lesson_id)
{
	$course_id = get_post_meta($lesson_id , 'course_id',true);
	$course = get_post($course_id);
	return !$course ? false : $course;
}

function get_course_category_children($course_cat_id)
{
	$children_ids = get_term_children($course_cat_id , 'ld_course_category');

	if( !empty($children_ids) ){
		$child_categories = get_terms( array(
			'taxonomy'   => 'ld_course_category',
			'include'    => $children_ids,
			'hide_empty' => false,
		) );
		return !$child_categories ? false: $child_categories;
	} else{
		return false;
	}
}


function is_lesson_progression_enabled($course_id)
{
	$meta = get_post_meta( $course_id, '_sfwd-courses' );
	return empty( $meta[0]['sfwd-courses_course_disable_lesson_progression'] );
}

function forced_lesson_time()
{
	$timeval = learndash_forced_lesson_time();

	if ( ! empty( $timeval ) ) {
		$time_sections = explode( ' ', $timeval );
		$h = $m = $s = 0;

		foreach ( $time_sections as $k => $v ) {
			$value = trim( $v );

			if ( strpos( $value, 'h' ) ) {
				$h = intVal( $value );
			} else if ( strpos( $value, 'm' ) ) {
				$m = intVal( $value );
			} else if ( strpos( $value, 's' ) ) {
				$s = intVal( $value );
			}
		}

		$time = $h * 60 * 60 + $m * 60 + $s;

		if ( $time == 0 ) {
			$time = (int)$timeval;
		}
	}

	if ( !empty( $time ) ) {
		$button_disabled = " disabled='disabled' ";
		echo '<script>
				var learndash_forced_lesson_time = ' . $time . ' ;
				var learndash_timer_var = setInterval(function(){learndash_timer()},1000);
			</script>
			<style>
				input#learndash_mark_complete_button[disabled] {     color: #333;    background: #ccc;    border-color: #ccc;}
			</style>';
		return $button_disabled;
	}
}

add_filter("learndash_course_completion_url", function($link, $course_id) {
	$course_meta = get_post_meta( $course_id, '_sfwd-courses', true );
	$link = ( !isset( $course_meta['sfwd-courses_course_completed_redirect_enabled'] ) ) ? $link : ( empty($course_meta['sfwd-courses_course_completed_redirect_url']) ? $link : $course_meta['sfwd-courses_course_completed_redirect_url'] ) ;
    return $link;
}, 5, 2);