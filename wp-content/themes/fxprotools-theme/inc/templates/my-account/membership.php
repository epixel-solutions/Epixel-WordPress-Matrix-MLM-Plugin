<div class="user-cancellation">
	<div class="progress">
	  <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width: 80%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">STEP 3 of 3</div>
	</div>
	<h2 class="text-center;">WAIT! Final Step BEFORE Your Account Is Deleted!</h2>
	<p>This is a special one time offer! You may not see this offer available again if you close this page.</p>
	<p>You don't currently qualify for any downgraded plan option other then "Paused". </p>
	<div class="row">
		<div class="col-md-6">
			<h3>Pause Account - $9.99 Month</h3>
		</div>
		<div class="col-md-6">
			<a href="#" class="btn btn-danger btn-block btn-lg btn-pause" data-subscription-id="<?php echo isset( $_GET['subs_id'] ) ? $_GET['subs_id'] : 0;?>">Pause My Account - $9.99 / Month</a>
		</div>
	</div>
	<p>(If you pause, your pages will not display live, you won't be able to use the ClickFunnels App... but we'll keep your subdomain reserved and all your pages and funnels waiting so you can resume your account anytime.)</p>
	<div class="row">
		<div class="col-md-6">
			<h3>Or...Finalize Account Cancellation:</h3>
		</div>
		<div class="col-md-6">
			<button type="button" data-toggle="modal" data-target="#cancellation-modal" class="btn btn-danger btn-block btn-lg">Finalize Cancellation</button>
		</div>
	</div>
	<p><strong>IMPORTANT:</strong> If you cancel your account, please note that your username (USER_NAME) will be made available for someone else; any progress and access to pages you've created will be disabled; optins and leads will not be collected; and videos will not display if you added your own.</p>
</div>

<p class="text-bold hide-on-cancel">Memberships Section</p>
<?php 
	//update subscription data based on user id param
	$subscriptions = wcs_get_users_subscriptions( get_query_var('acc_id') );
?>

<div class="woocommerce_account_subscriptions hide-on-cancel">

	<?php if ( WC_Subscriptions::is_woocommerce_pre( '2.6' ) ) : ?>
	<h2><?php esc_html_e( 'My Subscriptions', 'woocommerce-subscriptions' ); ?></h2>
	<?php endif; ?>

	<?php if ( ! empty( $subscriptions ) ) : ?>
	<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

	<thead>
		<tr>
			<th class="subscription-id order-number"><span class="nobr"><?php esc_html_e( 'Subscription', 'woocommerce-subscriptions' ); ?></span></th>
			<th class="subscription-status order-status"><span class="nobr"><?php esc_html_e( 'Status', 'woocommerce-subscriptions' ); ?></span></th>
			<th class="subscription-next-payment order-date"><span class="nobr"><?php echo esc_html_x( 'Next Payment', 'table heading', 'woocommerce-subscriptions' ); ?></span></th>
			<th class="subscription-total order-total"><span class="nobr"><?php echo esc_html_x( 'Total', 'table heading', 'woocommerce-subscriptions' ); ?></span></th>
			<th class="subscription-actions order-actions">&nbsp;</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ( $subscriptions as $subscription_id => $subscription ) : ?>
		<tr class="order">
			<td class="subscription-id order-number" data-title="<?php esc_attr_e( 'ID', 'woocommerce-subscriptions' ); ?>">
				<?php echo esc_html( sprintf( _x( '#%s', 'hash before order number', 'woocommerce-subscriptions' ), $subscription->get_order_number() ) ); ?>
				<?php do_action( 'woocommerce_my_subscriptions_after_subscription_id', $subscription ); ?>
			</td>
			<td class="subscription-status order-status" data-title="<?php esc_attr_e( 'Status', 'woocommerce-subscriptions' ); ?>">
				<?php echo esc_attr( wcs_get_subscription_status_name( $subscription->get_status() ) ); ?>
			</td>
			<td class="subscription-next-payment order-date" data-title="<?php echo esc_attr_x( 'Next Payment', 'table heading', 'woocommerce-subscriptions' ); ?>">
				<?php echo esc_attr( $subscription->get_date_to_display( 'next_payment' ) ); ?>
				<?php if ( ! $subscription->is_manual() && $subscription->has_status( 'active' ) && $subscription->get_time( 'next_payment' ) > 0 ) : ?>
					<?php
					// translators: placeholder is the display name of a payment gateway a subscription was paid by
					$payment_method_to_display = sprintf( __( 'Via %s', 'woocommerce-subscriptions' ), $subscription->get_payment_method_to_display() );
					$payment_method_to_display = apply_filters( 'woocommerce_my_subscriptions_payment_method', $payment_method_to_display, $subscription );
					?>
				<br/><small><?php echo esc_attr( $payment_method_to_display ); ?></small>
				<?php endif; ?>
			</td>
			<td class="subscription-total order-total" data-title="<?php echo esc_attr_x( 'Total', 'Used in data attribute. Escaped', 'woocommerce-subscriptions' ); ?>">
				<?php echo wp_kses_post( $subscription->get_formatted_order_total() ); ?>
			</td>
			<td class="subscription-actions order-actions">
				<a href="<?php echo get_the_permalink() . '?id=' . get_query_var('acc_id') . '&subs_id=' . $subscription->get_order_number() ?>" class="button view"><?php echo esc_html_x( 'View', 'view a subscription', 'woocommerce-subscriptions' ); ?></a>
				<?php do_action( 'woocommerce_my_subscriptions_actions', $subscription ); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>

	</table>
	<?php else : ?>

		<p class="no_subscriptions">
			<?php
			// translators: placeholders are opening and closing link tags to take to the shop page
			printf( esc_html__( 'You have no active subscriptions. Find your first subscription in the %sstore%s.', 'woocommerce-subscriptions' ), '<a href="' . esc_url( apply_filters( 'woocommerce_subscriptions_message_store_url', get_permalink( wc_get_page_id( 'shop' ) ) ) ) . '">', '</a>' );
			?>
		</p>

	<?php endif; ?>

</div>

<?php if(isset($_GET['subs_id'])){ ?>
<div class="membership-view-subs hide-on-cancel">
	<?php get_template_part('woocommerce/myaccount/view-subscription'); ?>
	<div class="cancel-step-1">
		<h3 class="m-b-md">Cancel Membership</h3>
		<div class="progress">
		  <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width: 33%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">STEP 1 of 3</div>
		</div>
		<p><a href="<?php echo get_option('home'); ?>/cancel-step-1?id=<?php echo get_query_var('acc_id') ?>&subs_id=<?php echo $_GET['subs_id'] ?>&order_type=membership" class="btn btn-danger btn-lg">Start Cancellation Process</a></p>
		<p><strong>IMPORTANT:</strong> If you cancel your account, please note that your subdomain (mastermindmedia) will be made available for someone else; any funnels and pages you've created will be disabled; optins and leads will not be collected; and videos will not play.</p>
	</div>
	<a href="#" id="back-to-memberships" class="btn btn-default">Back to Memberships</a>
</div>
<?php } ?>