<?php
/**
 * ----------------
 * Subscription Related Functions
 * ----------------
 * Hooks and Filters
 */

function fx_customer_subscription_products()
{
	return array( 2699, 47, 2927, 2928, 2930, 2931 );
}

function fx_distributor_subscription_products()
{
	return array( 48, 2921, 2920 );
}

function is_user_fx_customer($user_id = '', $status = 'active')
{
	$subscription_products = fx_customer_subscription_products();
	foreach($subscription_products as $s){
		if( wcs_user_has_subscription( $user_id , $s, $status) ){
			return true;
		}
	}
	return false;
}


function is_user_fx_distributor($user_id  = '', $status = 'active')
{
	$subscription_products = fx_distributor_subscription_products();
	foreach($subscription_products as $s){
		if( wcs_user_has_subscription( $user_id , $s, $status) ){
			return true;
		}
	}
	return false;
}

function user_has_autotrader()
{
	return wcs_user_has_subscription( '', 49, 'active');
}


function user_has_coaching()
{
	return wcs_user_has_subscription( '', 50, 'active');
}

function get_user_subscription_details()
{
	$subscriptions = wcs_get_users_subscriptions();
	$subscription_details = array();

	foreach($subscriptions as $s){
		$items = $s->get_items();

	    foreach($items as $key => $item){
	    	$subscription = wcs_get_subscription( $s->get_id() );
	    	$subscription_type = wc_get_order_item_meta($key, 'subscription-type', true);
	    	$product = wc_get_product( $item->get_product_id() );
	    	$package_type = in_array( $product->get_id(), fx_customer_subscription_products() ) ? 'Professional' : 'Subscriber';
	    	$package_type = in_array( $product->get_id(), fx_distributor_subscription_products() ) ? 'Business' : $package_type;
	    	$subscription_details[] = array( 	
	    		'id' => $s->get_id(), 
	    		'subscription' => $subscription,
	    		'product_id' => $product->get_id(),
	    		'order_item_id' => $item->get_id(),
	    		'package_type' => $package_type,
				'type' => $subscription_type, 
				'start_date' => $subscription->get_date('date_created'), 
				'end_date' =>  $subscription->has_status( wcs_get_subscription_ended_statuses() ) ? $subscription->get_date( 'end' ) : 0,
				'trial_expiry_date'  => $subscription->get_date( 'trial_end' ),
				'monthly_fee' => WC_Subscriptions_Product::get_price( $product ),
				'next_payment_date' => $subscription->get_date('next_payment'),
				'status' => $subscription->get_status()
	    	);
	    }
	}

	return $subscription_details;
}

function get_user_main_subscription(){
	$subscription_details = get_user_subscription_details();
	
	if( sizeof($subscription_details) > 0) {
		$subscription = $subscription_details[0];
		foreach( $subscription_details as $detail){
		    if( strtolower( $detail['package_type'] ) == 'business' ){
		        $subscription = $detail;
		        break;
		    }
		}
		return $subscription;
	}
	return false;
}

function get_renewal_order_checkout_link( $subscription ){
	$renewal = $subscription->get_last_order( 'renewal' );
	return $renewal ? $renewal->get_checkout_payment_url() : false;
}

function get_switch_subscription_url( $product_id ){
	$url = get_permalink($product_id);
	$subscription = get_user_main_subscription();

	//if product isn't a variable product
	if( wp_get_post_parent_id($product_id) ){
 		$url = get_permalink( $product_id ) . '&switch-subscription=' . $subscription['id'] . '&_wcsnonce=' . wp_create_nonce( 'wcs_switch_request' ) . '&item=' . $subscription['order_item_id'];
	} 

	return $url;
}

function get_recent_subscriptions ($limit = 15)
{
	$subscriptions = get_posts( array(
        'post_type' => 'shop_subscription',
        'post_status' => array( 'wc-processing', 'wc-completed', 'wc-expired', 'wc-on-hold' ),
        'numberposts' => $limit,
        'posts_per_page' => $limit
	) );
	$subscription_list = array();
	foreach($subscriptions as $s){
		$subscription_list[] = wc_get_order( $s->ID );
	}
	return $subscription_list;
}


add_action( 'user_subscription_paused', 'process_user_subscription', 10, 1);
function process_user_subscription( $subscription_id ){
	$subscription = wcs_get_subscription( $subscription_id );
	$subscription->set_status( 'on-hold' );
	$subscription->save();
}


add_action( 'template_redirect', 'paused_account_enforce_access' );
function paused_account_enforce_access()
{
	if ( is_user_logged_in() ) {
		global $post;
	    if( !isset( $post ) ) return;
	    $slug = $post->post_name;

		$allowed_pages = ThemeSettings::USER_ALLOWED_PAGES;
		
		if (in_array($slug, $allowed_pages)) {
			// Bail.
			return;
		}
	}
	
	if ( is_user_logged_in() && ( !is_user_fx_distributor() && !is_user_fx_customer() ) && !is_page( 'no-access' ) && !current_user_can( 'administrator' ) && has_imported_user_update_password() ) {
		global $post;
	    if( !isset( $post ) ) return;
	    $slug = $post->post_name;

		$allowed_pages = ThemeSettings::GUEST_ALLOWED_PAGES;

		if( !is_product() && !is_cart() && !is_checkout() && !is_shop() && !is_404() && !is_front_page() ) {
	       if( !in_array($slug, $allowed_pages) ){
	            wp_redirect( site_url('renewal') );
	            exit;
	        }
	    }
	}
}


//Custom my account redirects
add_action( 'init', 'view_subscription_redirect', 10 );
function view_subscription_redirect() {

    $url = $_SERVER['REQUEST_URI'] ;

    if( substr($url, 0, 30) == '/my-account/view-subscription/' ){
    	wp_redirect(  rtrim( home_url() . '/my-account/?subs_id=' . substr($url, 30), '/') );
    	exit;
    }

     if( substr($url, 0, 23) == '/my-account/view-order/'){
    	wp_redirect(  rtrim(  home_url() . '/my-account/?order_id=' . substr($url, 23), '/')  );
    	exit;
    }
}