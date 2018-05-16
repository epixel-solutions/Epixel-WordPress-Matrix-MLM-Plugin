<?php
/**
 * ----------------
 * Woocommerce Subscription  Settings
 * ----------------
 * Hooks and Filters
 */

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('WC_Subscriptions_Settings')){

	class WC_Subscriptions_Settings {
		
		public function __construct()
		{

			add_filter( 'woocommerce_settings_tabs_array', array($this, 'add_settings_tab'));
	        add_action( 'woocommerce_settings_tabs_fx_settings_tab', array($this, 'settings_tab') );
	        add_action( 'woocommerce_update_options_fx_settings_tab',  array($this, 'update_settings') );
			add_filter( 'woocommerce_subscriptions_is_duplicate_site', array($this, 'wc_is_duplicate_site'), 10, 1);
			add_filter( 'fx_before_gateway_renewal_order', array($this, 'wc_apply_signup_fee_on_renewal'), 10, 1);
		}

		public function add_settings_tab( $settings_tabs ) {
	        $settings_tabs['fx_settings_tab'] = 'FX Subscription Settings';
	        return $settings_tabs;
	    }

	    public  function settings_tab() {
	        woocommerce_admin_fields( self::get_settings() );
	    }

	    public function update_settings() {
	        woocommerce_update_options( self::get_settings() );
	    }

	    public function get_settings() {
	        $settings = array(
	            'section_title' => array(
	                'name'     => 'FX Subscription Settings',
	                'type'     => 'title',
	                'desc'     => '',
	                'id'       => 'wc_fx_settings_tab_section_title'
	            ),
	            'title' => array(
	                'name' => 'Charge User Signup Fee',
	                'type' => 'select',
	                'options' => array('yes' => 'Yes', 'no' => 'No'),
	                'default' => 'yes',
	                'id'   => 'wc_fx_settings_tab_charge_user_signup_fee'
	            ),
	            'description' => array(
	                'name' => 'When to Charge User',
	                'type' => 'select',
	                'options' => array('start' => 'Start', 'end' => 'Trial End'),
	                'default' => 'end',
	                'id'   => 'wc_fx_settings_tab_when_to_charge_user'
	            ),
	            'section_end' => array(
	                 'type' => 'sectionend',
	                 'id' => 'wc_fx_settings_tab_section_end'
	            )
	        );
	        return apply_filters( 'wc_fx_settings_tab_settings', $settings );
	    }

		
		/**
		 * [If the expired subscription is a trial product]
		 * @param  WC_Subscription
		 * @return bool
		 */
		public static function wc_is_subcription_trial( $subscription )
		{
			if ( $subscription ) {
				$items = $subscription->get_items();
				foreach($items as $key => $item){
					$subscription_type = wc_get_order_item_meta($key, 'subscription-type', true);
			    	return ($subscription_type == 'trial') ? true : false; 
				}
			} else{
				return false;
			}
		}

		/**
		 * [Get Product id used on a subscription]
		 * @param  WC_Subscription
		 * @return integer
		 */
		public static function wc_get_subscription_product_id( $subscription )
		{
			if ( $subscription ) {
				$items = $subscription->get_items();
				foreach($items as $key => $item){
					return $item->get_product_id();
				}
			} else{
				return 0;
			}
		}

		
		public function wc_is_duplicate_site($is_duplicate){
			return false;
		}


		/**
		 * [Apply signup fee on first renweal from trial product]
		 * @param  WC_Subscription
		 * @return WC_Subscription
		 */
		public function wc_apply_signup_fee_on_renewal($renewal_order){
			

			$user_id =  $renewal_order->get_customer_id();
			$referrals = get_user_active_referrals($user_id);
			//$has_paid_signup_fee = get_user_meta( $user_id , '_has_paid_signup_fee', true ); 
			$subscriptions = wcs_get_users_subscriptions( $user_id );

			//if user has 3 active referrals, modify renewal to be free
			
			if( count($referrals) >= 3){

				$eps_passed_criterias = apply_filters( 'check_free_account_criterias', $user_id);
				error_log('Invoked : check_free_account_criterias:' . $eps_passed_criterias );

				if( $eps_passed_criterias ){
					add_post_meta( $renewal_order->get_id(), '_free_renewal', true );
					$renewal_order->remove_order_items();
					$renewal_order->add_order_note('Free Renewal via Referral Program');

					foreach($subscriptions as $s){

						if( $s->has_status('on-hold') ){
							/* $items = $s->get_items();

							foreach($items as $key => $item){
								$subscription_type = wc_get_order_item_meta($key, 'subscription-type', true);

							    if( isset($subscription_type) ) {
									$product = wc_get_product( $item->get_product_id() );

									if( in_array($product->get_id(), array( 48, 2921, 2920 )) ){ //if business product add ibo kit instead
										$ibo_kit =  wc_get_product(2871);
										$renewal_order->add_product($ibo_kit, 1);
										$renewal_order->add_order_note('Add IBO Kit for Distributor Package');

									}
								}
							}
							*/
						}
					}
				}

				
			}

			//add signup fee to renewal
			/* -- signup waived for trial
			if( !$has_paid_signup_fee ){

				foreach($subscriptions as $s){
					
					$items = $s->get_items();
				    foreach($items as $key => $item){

				    	if( isset( $item['variation_id']) && in_array( $item['variation_id'], array(2921, 2931, 2928) ) ) {
							$product = wc_get_product( $item->get_product_id() );
							$args = array(
						        'attribute_subscription-type' => 'normal'
						    );
						    $product_variation = $product->get_matching_variation($args);
							$product = wc_get_product($product_variation);
							$signup_fee = $product->get_sign_up_fee();
							$payment_total = $signup_fee + $renewal_order->get_total();

							$item = new WC_Order_Item_Fee();

							$item->set_props( array(
									'name'      => 'Signup Fee',
									'tax_class' => 0,
									'total'     => $signup_fee,
									'total_tax' => 0,
									'taxes'     => array(
									'total' => 0,
								),
								'order_id'  => $renewal_order->get_id(),
						    ) );

							$item->save();
							$renewal_order->add_item($item);
							$renewal_order->add_order_note('Added Sign Up Fee');
							add_user_meta( $user_id , '_has_paid_signup_fee', 1); 

							//epixel filter invoke
							$referral = affiliate_wp()->referrals->get_by('reference', $s->get_parent_id() );
							$referrer_id = isset( $referral->affiliate_id ) ? affwp_get_affiliate_user_id( $referral->affiliate_id ) : 2936;
							
							$args = array(	
								'uid' => $user_id,
								'associated_uid' => $referrer_id,
								'order_id' => $renewal_order->get_id(),
								'amount_paid' => $signup_fee
							);
							error_log('Invoked : eps_commerce_joining_package_purchase_complete ' . print_r($args, true) );
						    $result = apply_filters('eps_commerce_joining_package_purchase_complete', $args);
						    error_log('Invoked : eps_commerce_joining_package_purchase_complete ' . print_r($result, true) );
						    
							
				    	}
				    }
				}
			}
			--*/

			$renewal_order->calculate_totals();
			return $renewal_order;

		}



	}
}

return new WC_Subscriptions_Settings();