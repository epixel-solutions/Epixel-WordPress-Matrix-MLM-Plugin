<?php

function eps_page_endpoint_title( $title ) {
	global $wp_query;
	$query_obj  = new Eps_Query;
	if ( ! is_null( $wp_query ) && ! eps_is_admin() && is_main_query() && in_the_loop() && is_page() && $query_obj->is_eps_endpoint_url() ) {
		$endpoint = $query_obj->afl_get_current_endpoint();

		if ( $endpoint_title = $query_obj->afl_get_endpoint_title( $endpoint ) ) {
			$title = $endpoint_title;
		}

		remove_filter( 'the_title', 'eps_page_endpoint_title' );
	}
	return $title;
}

// add_filter( 'the_title', 'eps_page_endpoint_title' );