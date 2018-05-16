<?php
/*
Template Name: Add Payment Method
*/
get_header();
/* 
halt

$anet_settings = get_option('woocommerce_authorize_net_cim_credit_card_settings');
$payment_token = $anet_settings['environment'] == 'test' ? get_user_meta(get_current_user_id(),'_wc_authorize_net_cim_credit_card_payment_tokens_test', true) : get_user_meta(get_current_user_id(),'_wc_authorize_net_cim_credit_card_payment_tokens', true);
$profile_id = $anet_settings['environment'] == 'test' ? get_user_meta(get_current_user_id(),'wc_authorize_net_cim_customer_profile_id_test', true) : get_user_meta(get_current_user_id(),'wc_authorize_net_cim_customer_profile_id', true);



$subscription = get_user_main_subscription()['subscription'];
$subscription->set_requires_manual_renewal(false);
$subscription->set_payment_method('authorize_net_cim_credit_card');
$subscription->save();

update_post_meta( $subscription->get_id(), '_wc_authorize_net_cim_credit_card_payment_token', key( $payment_token ) );
update_post_meta( $subscription->get_id(), '_wc_authorize_net_cim_credit_card_customer_id', $profile_id );

*/

?>

<?php if ( $available_gateways = WC()->payment_gateways->get_available_payment_gateways() ) : ?>
	<div class="container">	
		<div class="fx-header-title">
			<h1>Add Payment Method</h1>
		</div>

		<form id="add_payment_method" method="post">
			<div id="payment" class="woocommerce-Payment">
				<ul class="woocommerce-PaymentMethods payment_methods methods">
					<?php
						// Chosen Method.
						if ( count( $available_gateways ) ) {
							current( $available_gateways )->set_current();
						}

						foreach ( $available_gateways as $gateway ) {
							?>
							<li class="woocommerce-PaymentMethod woocommerce-PaymentMethod--<?php echo $gateway->id; ?> payment_method_<?php echo $gateway->id; ?>">
								<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> />
								<label for="payment_method_<?php echo $gateway->id; ?>"><?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?></label>
								<?php
									if ( $gateway->has_fields() || $gateway->get_description() ) {
										echo '<div class="woocommerce-PaymentBox woocommerce-PaymentBox--' . $gateway->id . ' payment_box payment_method_' . $gateway->id . '" style="display: none;">';
										$gateway->payment_fields();
										echo '</div>';
									}
								?>
							</li>
							<?php
						}
					?>
				</ul>

				<div class="form-row">
					<?php wp_nonce_field( 'woocommerce-add-payment-method' ); ?>
					<input type="submit" class="woocommerce-Button woocommerce-Button--alt button alt" id="place_order" value="<?php esc_attr_e( 'Add payment method', 'woocommerce' ); ?>" />
					<input type="hidden" name="woocommerce_add_payment_method" id="woocommerce_add_payment_method" value="1" />
				</div>
			</div>
		</form>
	</div>
<?php else : ?>
	<p class="woocommerce-notice woocommerce-notice--info woocommerce-info"><?php esc_html_e( 'Sorry, it seems that there are no payment methods which support adding a new payment method. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ); ?></p>
<?php endif; ?>

<?php get_footer(); ?>