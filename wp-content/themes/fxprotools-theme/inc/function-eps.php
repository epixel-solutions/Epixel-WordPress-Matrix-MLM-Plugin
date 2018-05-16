<?php
/*
eps_commerce_purchase_complete
eps_commerce_joining_package_purchase_complete
eps_commerce_distributor_kit_purchase_complete
eps_affiliates_become_distributor_from_customer
eps_affiliates_place_user_in_holding_tank
eps_affiliates_unilevel_place_user_in_holding_tank
eps_affiliates_place_customer_under_sponsor
eps_affiliates_place_user_in_holding_tank
 */

function eps_subscription_order_completed( $subscription ) { 
    $items = $subscription->get_items();

	foreach($items as $key => $item){
		$pv = rwmb_meta('personal_volume', '',  $item->get_product_id());
	}

	$last_order = $subscription->get_last_order('all');

	if( wcs_order_contains_renewal($last_order) ){

		$free_renewal = get_post_meta( $last_order->get_id(), '_free_renewal', true );

		$args = array(	
			'uid' => $subscription->get_customer_id(),
			'order_id' => $last_order->get_id(),
			'amount_paid' => $free_renewal ? 0 : $last_order->get_total(),
			'afl_point' => $free_renewal ? 0 : $pv
		);

		error_log('eps_commerce_purchase_complete:' . print_r($args, true));
	    $result = apply_filters('eps_commerce_purchase_complete', $args);
	    error_log('Invoked : eps_commerce_purchase_complete via renewal ' . print_r($result, true) );
	}

	else {
		$parent_order = new WC_Order($subscription->get_parent_id());

	    foreach($items as $key => $item){
	    	if( isset( $item['variation_id'] ) && in_array( $item['variation_id'], array(2920, 2930, 2927) ) ) {
	    		$args = array(	
					'uid' => $subscription->get_customer_id(),
					'order_id' => $parent_order->get_id(),
					'amount_paid' => $parent_order->get_total(),
					'afl_point' => $pv
				);

			    $result = apply_filters('eps_commerce_purchase_complete', $args);
			    error_log('Invoked : eps_commerce_purchase_complete via regular checkout' . print_r($result, true) );

			    if( $item['variation_id'] == 2920){
			    	$args = array(	
						'uid' => $subscription->get_customer_id(),
						'order_id' => $parent_order->get_id()
					);
				    $result = apply_filters('eps_commerce_distributor_kit_purchase_complete', $args);
				   	error_log('Invoked : eps_commerce_distributor_kit_purchase_complete ' . print_r($result, true) );
			    }
	    	}
	    	else{
	    		 error_log('Invoke Fail : eps_commerce_purchase_complete via regular checkout, INVALID VARIATION' );
	    	}
	    }
	}

   
}; 
         
add_action( 'woocommerce_subscription_payment_complete', 'eps_subscription_order_completed', 10, 1 ); 


function eps_distributor_kit_purchased( $subscription ) { 

    $items = $subscription->get_items();

	foreach($items as $key => $item){
		if( $item['product_id'] == 2871 || $item['product_id'] == 48){
			$renewal = $subscription->get_last_order('all', array('renewal'));

		    $args = array(	
				'uid' => $subscription->get_customer_id(),
				'order_id' => $renewal->get_id()
			);
		    $result = apply_filters('eps_commerce_distributor_kit_purchase_complete', $args);
		   	error_log('Invoked : eps_commerce_distributor_kit_purchase_complete ' . print_r($result, true) );

		} else {
			error_log('Invoke Fail : eps_commerce_distributor_kit_purchase_complete, NO IBO KIT');
		}
	}

	
   
}; 
         
add_action( 'woocommerce_subscription_renewal_payment_complete', 'eps_distributor_kit_purchased', 10, 1 ); 



function eps_switched_from_customer_distributor() { 
	if( is_user_logged_in() && is_user_fx_distributor() ){
		do_action('eps_affiliates_become_distributor_from_customer', get_current_user_id() );
		error_log('Invoked : eps_affiliates_become_distributor_from_customer');
	}
	
}; 
         
add_action( 'woocommerce_subscriptions_switch_completed', 'eps_switched_from_customer_distributor', 10, 1 ); 


function eps_affiliate_place_user_after_order( $subscription, $order, $recurring_cart ) { 

	$user_id = $order->get_customer_id();
	//check the user is already placed in any one of the tree,
	//if he placed then we doesnot need to add this user again holding tank.
	$matrix_node = afl_genealogy_node( $user_id );
	$uni_node 	 = afl_genealogy_node( $user_id , 'unilevel');

	if ( empty($uni_node ) && empty($matrix_node) ) {
		$referral = affiliate_wp()->referrals->get_by('reference', $order->get_id() );
		$referrer_id = isset( $referral->affiliate_id ) ? affwp_get_affiliate_user_id( $referral->affiliate_id ) : 2936;

		if(isset($referrer_id)){
			if( is_user_fx_customer( $user_id, 'any' ) ){
				$user = new WP_User( $user_id );
				$user->add_role( 'afl_customer' ); 

				if( $referrer_id == 2936 || is_user_fx_distributor($referrer_id, 'any') ){
					//this add the user to the customer table
					do_action('eps_affiliates_place_customer_under_sponsor', $user_id, $referrer_id);
					error_log('Invoked: eps_affiliates_place_customer_under_sponsor ' . $user_id . ':' . $referrer_id);

					//adds the customer to referrer's holding tank
					do_action('eps_affiliates_unilevel_place_user_in_holding_tank', $user_id, $referrer_id);
					error_log('Invoked: eps_affiliates_unilevel_place_user_in_holding_tank ' . $user_id . ':' . $referrer_id);
				}
				else if( is_user_fx_customer( $referrer_id, 'any' ) ){
					//add to the customer table
					do_action('eps_affiliates_place_customer_under_sponsor', $user_id, $referrer_id);
					error_log('Invoked: eps_affiliates_place_customer_under_sponsor ' . $user_id . ':' . $referrer_id);

					//add directly to the genealogy
					do_action('eps_affiliates_unilevel_place_user_under_sponsor', $user_id, $referrer_id);
					error_log('Invoked: eps_affiliates_place_customer_under_sponsor ' . $user_id . ':' . $referrer_id);
				}
				
			}
			if( is_user_fx_distributor($user_id, 'any') ){
				//add to unilevel holding tank
				do_action('eps_affiliates_place_user_in_holding_tank', $user_id, $referrer_id);
				error_log('Invoked: eps_affiliates_place_user_in_holding_tank ' . $user_id . ':' . $referrer_id);

				//add to matrix holding tank
				do_action('eps_affiliates_unilevel_place_user_in_holding_tank', $user_id, $referrer_id);
				error_log('Invoked: eps_affiliates_unilevel_place_user_in_holding_tank ' . $user_id . ':' . $referrer_id);

				
				$user = new WP_User( $user_id );
				$user->add_role( 'afl_member' );
			}
		}
	}
}; 
         
add_action( 'woocommerce_checkout_subscription_created', 'eps_affiliate_place_user_after_order', 10, 3 ); 



function eps_calculate_faststart_bonus( $subscription ) { 

	$referral = affiliate_wp()->referrals->get_by('reference', $subscription->get_parent_id() );
	$referrer_id = isset( $referral->affiliate_id ) ? affwp_get_affiliate_user_id( $referral->affiliate_id ) : 2936;
	$user_id = $subscription->get_customer_id();
	$has_paid_signup_fee = get_user_meta( $referrer_id , '_has_paid_signup_fee', true ); 
	$has_collected_bonus_from_user =  get_user_meta( $user_id , '_has_paid_signup_fee', true );

	if( is_user_fx_distributor( $referrer_id ) && $has_paid_signup_fee &&  !$has_collected_bonus_from_user ){
		$items = $subscription->get_items();

	    foreach($items as $key => $item){
	    	if( isset( $item['variation_id'] ) && in_array( $item['variation_id'], array(2920, 2930, 2927) ) ) {
	    		add_user_meta( $user_id , '_has_paid_signup_fee', 1); 

	    		do_action('afl_calculate_fast_start_bonus', $user_id, $referrer_id);
				error_log('Invoked: afl_calculate_fast_start_bonus ' . $user_id . ':' . $referrer_id);
				break;
	    	}
	    	else{
	    		error_log('Invoked Fail: afl_calculate_fast_start_bonus : Invalid Variation ID');
	    	}
	    }

	}
	else{
		error_log('Invoked Fail: afl_calculate_fast_start_bonus, not valid distributor or claimed already');
	}
}; 
         
add_action( 'woocommerce_subscription_payment_complete', 'eps_calculate_faststart_bonus', 10, 1 ); 


 
