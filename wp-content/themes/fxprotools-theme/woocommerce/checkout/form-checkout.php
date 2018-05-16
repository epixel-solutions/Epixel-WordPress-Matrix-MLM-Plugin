<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
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

get_header();

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

?>


<form name="checkout" method="post" class="checkout checkout_layout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

	<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

	<div class="checkout-sidebar">
		<div class="checkout-sidebar-item">
			<img src="<?php bloginfo('template_directory');?>/assets/img/checkout/sidebar-banner.png" class="img-responsive">
		</div>
		<div class="checkout-sidebar-item">
			<h3>What Our Members Are Saying...</h3>
		</div>
		<div class="checkout-sidebar-item">
			<img src="https://forcefactor.me/assets/images/testimonial/checkout.jpg">
		</div>
		<div class="checkout-sidebar-item">
			<h3>HERE'S WHAT YOU ARE GETTING...</h3>
			<ul class="check-lit">
				<li>Access to our private online membership site.</li>
				<li>Eye-opening video success training that provides simple, step by step rules for getting your online business up and running.</li>
				<li>Access to your own personal team of coaches who are ready to work one-on-one with you to help you achieve success.</li>
				<li>Exceptional support. We care about your results, so we have provided live chat and email support so you can get the answers you need when you need them.</li>
				<li>And more than a dozen other high-value online business resources that will provide the motivation, the tools and the know-how you need to get to the next level in your business.</li>
			</ul>
		</div>
		<div class="checkout-sidebar-item">
			<div class="checkout-icon-box checkout-icon-box-1">
				<h4>100% MONEY BACK GUARANTEE</h4>
				<p>If you decide that it's not for you, just let us know and you'll be issued a full and prompt refund, no questions asked.</p>
			</div>
		</div>
		<div class="checkout-sidebar-item">
			<div class="checkout-icon-box checkout-icon-box-2">
				<h4>YOUR INFORMATION IS SAFE</h4>
				<p>We will not sell, rent, or share your contact information for any reason.</p>
			</div>
		</div>
		<div class="checkout-sidebar-item">
			<div class="checkout-icon-box checkout-icon-box-3">
				<h4>YOUR INFORMATION IS SAFE</h4>
				<p>All information is encrypted and transmitted without risk using a SSL protocol.</p>
			</div>
		</div>
	</div>

	<div id="checkout-panel-3" class="panel panel-default panel-gray">
		<div class="panel-body">
			<h5>Order Summary <span>Price:</span></h5>
			<?php if(isset($_COOKIE['affwp_ref'])): ?>
				<?php $user = new WP_User( affwp_get_affiliate_user_id($_COOKIE['affwp_ref'])); ?>
				<table class="affiliate-referrer">
					<tbody>
						<tr class="cart_item">
							<td class="product-name">
								<strong>Your Referring Sponsor :</strong>
							</td>
							<td class="product-total">
								<strong><?php echo $user->display_name;?></strong> (<?php echo $user->user_login;?> )
							</td>
						</tr>
					</tbody>
				</table>
			<?php endif ;?>
			<div class="term-wrap">
				<div class="no-pad-left">
					<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox" name="terms" id="terms">
					<span class="term-wrap-text">
						I agree with the
						<a href="#" onclick="window.open('#', 'newwindow', 'width=500, height=700'); return false">Purchase Agreement</a>,
						<a href="#" onclick="window.open('#', 'newwindow', 'width=500, height=700'); return false">Refund Policy</a>,
						<a href="#" onclick="window.open('#', 'newwindow', 'width=500, height=700'); return false">Terms of Service</a>,
						and <a href="#" onclick="window.open('#', 'newwindow', 'width=500, height=700'); return false">Privacy Policy</a>.
					</span>
				</div>
			</div>
			<div class="col-md-12 submit-btn-wrap">
				<div class="text-center">
					<button type="submit" id="submit-btn" name="woocommerce_checkout_place_order" id="submitorder" class="btn btn-danger btn-lg m-b-md btn-lg-w-text">Finish My Order <span class="fa fa-angle-right"></span> </button>
				</div>
			</div>
	 	</div>
	 </div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<div class="modal fade trial checkout-popup" id="checkout-popup-1" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <button type="button" id="hide-checkout-popup" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
      <div class="modal-body">
      	<h2 class="text-center">WAIT!!</h2>
      	<h3 class="text-center">DON'T GO EMPTY HANDED...</h3>
        <p class="intro-note label-red text-center">GET STARTED TODAY FOR $1.00</p>
        <p class="text-center"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/dollar.jpg"></p>
        <p>....Because you took time to visit our website we are presenting you with this valuable, <strong>ONE TIME ONLY, $1 TRIAL OFFER...</strong></p>
        <p><strong>No Tricks or Gimmicks</strong>....click the red "GET STARTED FOR $1.00 TODAY" button below right now... <span class="label-red">you'll receive this product for 30 days! Only $1.00!</span></p>
      	<p>Remember... we show you exactly - step-by- step - how to start earning money in your spare time from home... while doing fun, simple work right from your computer, tablet or smart-phone.</p>
      	<p>So go ahead and <a href="#">take advantage of this no-risk</a>, $1 TRIAL OFFER right now because you will never see it again once you leave this page.</p>
      	<p><strong>LAST CHANCE!</strong> Just click the "<strong>GET STARTED FOR $1.00 TODAY</strong>" button below... so can learn how to start earning a full time income from home.</p>
      	<div class="text-center">
      		<a href="#" class="btn btn-success btn-lg m-b-md btn-lg-w-text btn-trial " data-dismiss="modal">
				Get Your Access For $1.00 Trial Now!
				<span>Sign-up takes less than 60 seconds. Pick a plan to get started!</span>
			</a>
      	</div>
      	<p>P.S. Immediately after clicking the red button above, you will get <a href="#">instant access</a> to the Training Website.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade trial checkout-popup" id="checkout-popup-2" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <button type="button" id="hide-checkout-popup" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
      <div class="modal-body">
      	<h2 class="text-center">WAIT!!</h2>
      	<h3 class="text-center">DON'T GO EMPTY HANDED...</h3>
        <p class="intro-note label-red text-center">GET STARTED TODAY FOR $1.00</p>
        <p class="text-center"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/dollar.jpg"></p>
        <p>....Because you took time to visit our website we are presenting you with this valuable, <strong>ONE TIME ONLY, $1 TRIAL OFFER...</strong></p>
        <p><strong>No Tricks or Gimmicks</strong>....click the red "GET STARTED FOR $1.00 TODAY" button below right now... <span class="label-red">you'll receive this product for 30 days! Only $1.00!</span></p>
      	<p>Remember... we show you exactly - step-by- step - how to start earning money in your spare time from home... while doing fun, simple work right from your computer, tablet or smart-phone.</p>
      	<p>So go ahead and <a href="#">take advantage of this no-risk</a>, $1 TRIAL OFFER right now because you will never see it again once you leave this page.</p>
      	<p><strong>LAST CHANCE!</strong> Just click the "<strong>GET STARTED FOR $1.00 TODAY</strong>" button below... so can learn how to start earning a full time income from home.</p>
      	<div class="text-center">
      		<a href="#" class="btn btn-success btn-lg m-b-md btn-lg-w-text btn-trial"  data-dismiss="modal">
				Get Your Access For $1.00 Trial Now!
				<span>Sign-up takes less than 60 seconds. Pick a plan to get started!</span>
			</a>
      	</div>
      	<p>P.S. Immediately after clicking the red button above, you will get <a href="#">instant access</a> to the Training Website.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade default checkout-popup" id="checkout-popup-3" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <button type="button" id="hide-checkout-popup" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
      <div class="modal-body">
      	<h2 class="text-center">WAIT!!</h2>
      	<h3 class="text-center">DON'T GO EMPTY HANDED...</h3>
        <p class="intro-note label-red text-center">GET STARTED TODAY FOR $1.00</p>
        <p class="text-center"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/dollar.jpg"></p>
        <p>....Because you took time to visit our website we are presenting you with this valuable, <strong>ONE TIME ONLY, $1 TRIAL OFFER...</strong></p>
        <p><strong>No Tricks or Gimmicks</strong>....click the red "GET STARTED FOR $1.00 TODAY" button below right now... <span class="label-red">you'll receive this product for 30 days! Only $1.00!</span></p>
      	<p>Remember... we show you exactly - step-by- step - how to start earning money in your spare time from home... while doing fun, simple work right from your computer, tablet or smart-phone.</p>
      	<p>So go ahead and <a href="#">take advantage of this no-risk</a>, $1 TRIAL OFFER right now because you will never see it again once you leave this page.</p>
      	<p><strong>LAST CHANCE!</strong> Just click the "<strong>GET STARTED FOR $1.00 TODAY</strong>" button below... so can learn how to start earning a full time income from home.</p>
      	<div class="text-center">
      		<a href="#" class="btn btn-success btn-lg m-b-md btn-lg-w-text btn-trial "  data-dismiss="modal">
				Get Your Access For $1.00 Trial Now!
				<span>Sign-up takes less than 60 seconds. Pick a plan to get started!</span>
			</a>
      	</div>
      	<p>P.S. Immediately after clicking the red button above, you will get <a href="#">instant access</a> to the Training Website.</p>
      </div>
    </div>
  </div>
</div>

<?php
	$orders = array();
	$order_statuses = array('wc-on-hold', 'wc-processing', 'wc-completed', 'wc-pending', 'wc-cancelled', 'wc-refunded', 'wc-failed');
    $sub=get_posts( array(
            'post_type' => 'shop_order',
            'post_status' => $order_statuses,
            'numberposts' => 15
    ) );

    $args = array(
		'exclude'      => array(get_current_user_id()),
		'number'       => '100',
		'role__not_in' => array('administrator')
	 );

	$all_users = get_users( $args );
	$user_count = count($all_users);

	foreach($all_users as $user){
		$customer_id = $user->ID;
		$name       = get_the_author_meta('first_name', $customer_id) . " " . get_the_author_meta('last_name', $customer_id);
    	$state      = get_the_author_meta('billing_state', $customer_id);
    	$prod_random = array('IBO Kit','Signals','Business + IBO Kit','Professional');
    	shuffle($prod_random);
    	$real_order = "";

    	$cust_orders = get_customer_orders($customer_id);
    	if( sizeof($cust_orders) > 0){
    		$order_items = wc_get_order( $cust_orders[0]->ID );
			$items = $order_items->get_items();
			foreach($items as $item){
				if($item->get_product_id() != 49 || $item->get_product_id() != 50){
					$real_order = $item->get_name();
				}
			}

	    	$activity   = "Recently ordered " . isset($real_order) ? $real_order : $prod_random[0];
	    	$time       = random_checkout_time_elapsed();
	    	$user_login = get_the_author_meta('user_login', $customer_id);
	    	$st_random  = array('Alabama','California','Colorado','Illinois','Florida','Delaware','New York','Indiana','Kansas','Massachussets','Nevada','New Mexico','Oklahoma','Texas','Utah','Virginia','Washington');
			shuffle($st_random);
			$get_state  = $st_random[0];

	    	if($state == ""){
	    		$state = $get_state;
	    	}

	    	if(!get_the_author_meta('first_name', $customer_id)){
	    		if (strpos($user_login, '@') !== false) {
				    $name = strstr($user_login, '@', true);
				}else{
					$name = $user_login;
				}
	    	}

	    	$orders[] = array(
	    		'image'    => "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode( $state ) . "&zoom=13&size=120x120&maptype=roadmap&key=AIzaSyAMRPELYMjUR8a0q0UArdw8oLRYrjuLA6o",
	    		'name'     => $name . ', ' . $state,
	    		'activity' => $activity,
	    		'time'     => $time
	    	);
    	}
		
	}

	if($user_count <= 30){
		$fake_users = array(
			array(
				"name"     => 'Brian B.',
				"location" => 'New Jersey'
			),
			array(
				"name"     => 'Donald DeRenzo',
				"location" => 'New Mexico'
			),
			array(
				"name"     => 'Jesse Smithers',
				"location" => 'Nevada'
			),
			array(
				"name"     => 'Sheryl Williams',
				"location" => 'West Virginia'
			),
			array(
				"name"     => 'Alicia McCollum',
				"location" => 'New Hampshire'
			),
			array(
				"name"     => 'Kiara Collins',
				"location" => 'New Jersey'
			),
			array(
				"name"     => 'Elizabeth Keen',
				"location" => 'South Dakota'
			),
			array(
				"name"     => 'Jason Moore',
				"location" => 'Georgia'
			),
			array(
				"name"     => 'Lucy Mayfield',
				"location" => 'Maine'
			),
			array(
				"name"     => 'Spencer Ryan',
				"location" => 'Delaware'
			),
			array(
				"name"     => 'Rob Harper',
				"location" => 'New York'
			),
			array(
				"name"     => 'Ryan Mendez',
				"location" => 'Florida'
			),
			array(
				"name"     => 'Gregory McGinnis',
				"location" => 'Alabama'
			),
			array(
				"name"     => 'Miley Parker',
				"location" => 'California'
			),
		);

		foreach($fake_users as $user){
			$prod_random = array('IBO Kit','Signals','Business + IBO Kit','Professional');
	    	shuffle($prod_random);
	    	$activity   = "Recently ordered " . $prod_random[0];
	    	$time       = random_checkout_time_elapsed();
			$orders[] = array(
	    		'image'    => "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode( $user['location'] ) . "&zoom=13&size=120x120&maptype=roadmap&key=AIzaSyAMRPELYMjUR8a0q0UArdw8oLRYrjuLA6o",
	    		'name'     => $user['name'] . ', ' . $user['location'],
	    		'activity' => $activity,
	    		'time'     => $time
	    	);
		}
	}
	?>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			var notifications = <?php echo json_encode($orders) ?>;
			var customer_size = notifications.length;
			var counter = 1;

			jQuery('[data-toggle="popover"]').popover();

			setInterval(function(){
				if(counter > customer_size){
					counter = 1;
				}
				var customer_index = counter - 1;
				new Noty({
					type: 'alert',
					layout: 'bottomLeft',
				    text: '<div class="customer-notif"><img src="'+ notifications[customer_index].image +'"><div class="customer-notif-main"><div class="customer-name">'+ notifications[customer_index].name +'</div><div class="customer-activity">'+ notifications[customer_index].activity +'</div><div class="customer-time">'+ notifications[customer_index].time +'</div></div></div>',
				    theme: 'relax',
				    progressBar: false,
				    timeout: 7000,
				    visibilityControl: false,
				    killer: true
				}).show();
				counter++;
			},7000);
		});
	</script>


<?php
$popup_type = '';

foreach( WC()->cart->get_cart() as $cart_item ){
	$popup_type = 'default';
	if( isset( $cart_item ['variation']['attribute_subscription-type'] ) ){
		if($cart_item['variation']['attribute_subscription-type'] == 'normal'){
			$popup_type = 'normal';
			$trial_product_link = get_permalink($cart_item['product_id']) . '?attribute_subscription-type=trial';
		}
		elseif($cart_item['variation']['attribute_subscription-type'] == 'trial'){
			$popup_type = 'trial';
		}
		else{
			$popup_type = 'default';
		}
	}

}

if ( isset($popup_type) ):
?>
<script type="text/javascript">
	jQuery(document).on('mouseleave', customer_exit_intent);

	function customer_exit_intent(e){
		if( !$.cookie('checkout_popup_cookie') &&  e.clientY < 60  ){
			<?php if($popup_type == 'normal'): ?>
				jQuery('.checkout-popup.<?php echo $popup_type;?>').find('.btn-trial').attr('href', '<?php echo $trial_product_link;?>');
			<?php endif; ?>
			jQuery('.checkout-popup.<?php echo $popup_type;?>').modal('show');
		}
	}

	jQuery('.close').click(function(){
		jQuery('.checkout-popup.<?php echo $popup_type;?>').modal('hide');
		$.cookie('checkout_popup_cookie', 'active', { expires: 12 });
	});
</script>
<?php endif; ?>
