<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 
?>
<?php if ($gateway->id == 'authorize_net_cim_credit_card'): ?>
<li class="wc_payment_method payment_method_<?php echo $gateway->id; ?>">
	<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
	
	
 	<div class="panel panel-default">
		<div class="panel-heading">STEP 3: PAYMENT INFORMATION</div>
		<div class="panel-body">
			<div class="panel-note">
				<p>This website utilizes some of the most advanced techniques to protect your information including technical, administrative and even physical safeguards againts unauthorized access, misuse and improper disclosure.</p>
			</div>
	 		<div class="form-group row ">
			    <div class="col-xs-12">
				    <div class="row">
				    	<div class="col-md-12 payment-form">
				    		<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
				     			<?php $gateway->payment_fields(); ?>
				     		<?php endif; ?>
				    	</div>
					</div>
				</div>
	 		</div>
		 </div>
	</div>
</li>
<?php else: ?>
<li class="wc_payment_method payment_method_<?php echo $gateway->id; ?>">
	<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo $gateway->id; ?>">
		<?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo $gateway->id; ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
<?php endif; ?>