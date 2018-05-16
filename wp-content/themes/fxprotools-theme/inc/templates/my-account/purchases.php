<p class="text-bold">Purchases</p>
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
			<a href="#" class="btn btn-danger btn-block btn-lg btn-pause">Pause My Account - $9.99 / Month</a>
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

<?php
$my_orders_columns = get_order_columns();
$customer_orders = get_purchased_items(get_query_var('acc_id'));
if ( $customer_orders ){ ?>
	<table class="shop_table shop_table_responsive my_account_orders">
		<thead>
			<tr>
				<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
					<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $customer_orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
				?>
				<tr class="order">
					<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
						<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php
								/* translators: 1: formatted order total 2: total order items */
								printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
								?>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
									$actions = array(
										'pay'    => array(
											'url'  => $order->get_checkout_payment_url(),
											'name' => __( 'Pay', 'woocommerce' ),
										),
										'view'   => array(
											'url'  => $order->get_view_order_url(),
											'name' => __( 'View', 'woocommerce' ),
										),
										'cancel' => array(
											'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
											'name' => __( 'Cancel', 'woocommerce' ),
										),
									);

									if ( ! $order->needs_payment() ) {
										unset( $actions['pay'] );
									}

									if ( ! in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
										unset( $actions['cancel'] );
									}

									if ( $actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order ) ) {
										foreach ( $actions as $key => $action ) {
											echo '<a href="' . get_the_permalink() . '?id=' . get_query_var('acc_id') . '&order_id=' . $order->get_order_number() . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
										}
									}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if(isset($_GET['order_id'])){ ?>
	<div class="purchases-view-order">
		<?php get_template_part('woocommerce/myaccount/view-order'); ?>
		<div class="cancel-step-1">
			<h3 class="m-b-md">Cancel Purchase</h3>
			<div class="progress">
			  <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width: 33%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">STEP 1 of 3</div>
			</div>
			<p><a href="<?php echo get_option('home'); ?>/cancel-step-1?id=<?php echo get_query_var('acc_id') ?>&order_id=<?php echo $_GET['order_id'] ?>&order_type=purchase" class="btn btn-danger btn-lg">Start Cancellation Process</a></p>
			<p><strong>IMPORTANT:</strong> If you cancel your account, please note that your subdomain (mastermindmedia) will be made available for someone else; any funnels and pages you've created will be disabled; optins and leads will not be collected; and videos will not play.</p>
		</div>
		<a href="#" id="back-to-purchases" class="btn btn-default">Back to Purchases</a>
	</div>
	<?php } ?>

<?php }else{ ?>
	<p class="hide-on-cancel">No purchases found.</p>
<?php } ?>
<div id="view-purchase-details">
	<div class="purchase-details-info"></div>
	<div class="panel panel-default">
		<div class="clearfix">
			<div class="col-md-12">
				<h3 class="m-b-md">Cancel Purchase</h3>
				<div class="progress">
				  <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 33%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">STEP 1 of 3</div>
				</div>
				<p><a href="<?php echo get_option('home'); ?>/cancel-step-1" class="btn btn-danger btn-lg">Start Cancellation Process</a></p>
				<p><strong>IMPORTANT:</strong> If you cancel your account, please note that your subdomain (mastermindmedia) will be made available for someone else; any funnels and pages you've created will be disabled; optins and leads will not be collected; and videos will not play.</p>
			</div>
		</div>
	</div>
	<a href="#" id="close-purchase-details" class="btn btn-default m-t-md">Back to List of Purchases</a>
</div>