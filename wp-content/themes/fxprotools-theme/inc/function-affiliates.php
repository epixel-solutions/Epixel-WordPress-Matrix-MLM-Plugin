<?php
/**
 * -----------------------------
 * Affiliate Related Functions
 * -----------------------------
 * 
 */


//Filters affiliate vars
add_action( 'init', 'allow_multiple_affiliate_vars', 10 );
function allow_multiple_affiliate_vars() {

    if( isset( $_GET['aff'] ) || isset( $_GET['s1'] ) ){
        $ref = $_GET['aff'] ? $_GET['aff'] : $_GET['s1']; 
        $url = site_url() . strtok( $_SERVER["REQUEST_URI"], '?') . '/?ref=' . $ref;
        wp_redirect( $url, 301 );
    }
}


add_action( 'init', 'before_set_affiliate_var', 11 );
function before_set_affiliate_var() {
	$ref = isset( $_GET['ref'] ) ? $_GET['ref'] : 'business.admin';

	if( $ref != 'business.admin' && !current_user_can('administrator') ) {
		$user = get_user_by('slug', $ref);
	    if( $user ) {
	    	if( !wcs_user_has_subscription($user->ID, 48, 'active') ){
	    		$ref = 'business.admin';
	    		$url = site_url() . strtok( $_SERVER["REQUEST_URI"], '?') . '/?ref=' . $ref;
		        wp_redirect( $url, 301 );
	    	}
	    }
	}
}
