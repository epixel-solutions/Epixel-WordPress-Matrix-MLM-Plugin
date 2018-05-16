<?php
/*
Template Name: Access Products
*/
$subscription_details = get_user_subscription_details();

$subscription = [];
$trial_remaining_days = 0;
if ( ! empty ( $subscription_details ) ) {
	$subscription = $subscription_details[0];
	foreach ( $subscription_details as $detail ) {
		if ( strtolower( $detail['package_type'] ) == 'business' ) {
			$subscription = $detail;
			break;
		}
	}
	if ( isset( $subscription_details['trial_expiry_date'] ) ) {
		$trial_remaining_days = floor( ( strtotime( $subscription_details['trial_expiry_date'] ) - time() ) / ( 60 * 60 * 24 ) );
	}
}

$trial_expiration_date = floor( ( strtotime( $subscription['trial_expiry_date'] ) - time() ) / ( 60 * 60 * 24 ) );
$market_scanner =  wcs_user_has_subscription( '', 47, 'active') || is_user_fx_distributor();
$auto_trader = wcs_user_has_subscription( '', 49, 'active');
$coaching = wcs_user_has_subscription( '', 50, 'active');
$subscription_type = $subscription_details[0]['type'];

// Signals Product ID's = 2699, 2928, 2927
$signal_ids = array(2699,2928,2927);
$user_subs = get_user_main_subscription();
$subscription_product_id = $user_subs['product_id'];

// Activate stage 2 navs 
if( isset( $_GET['activate_stage_2_nav'] ) ){
	update_user_meta( get_current_user_id(), '_activate_stage_2_navs', 1 );
	// echo "<script>alert('".get_user_meta( get_current_user_id(), '_activate_stage_2_navs', true ) ."')</script>";
}

?>
<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-dashboard'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>Awesome! You Can Now Access Your Products</h1>
					<p><span class="label-red">Step 2:</span> - Supercharge your learning experience using our products</p>
				</div>
			</div>
			<div class="col-md-8">
				<?php
					// Metabox Page Template Option - Video Embed
					echo get_mb_pto1( 'video_embed', 'pto1' );
				?>
			</div>
			<div class="col-md-4">
				<div class="fx-board access-products">
					<div class="fx-board-header">
						Your Membership Package
					</div>
					<div class="fx-board-content">
                        <?php if (!empty($subscription)) : ?>
						<p class="text-center">You are currently subscribed to the <strong><?php echo $subscription['package_type'];?> Package</strong> at <strong>$<?php echo $subscription['monthly_fee'];?>/Month</strong></p>
						<ul class="list-status">
							<li><label>Status</label> <span><?php echo ucfirst($subscription['status']);?></span></li>
							<li><label>Start Date</label> <span><?php echo date('F d, Y', strtotime($subscription['start_date']) );?></span></li>
							<li><label>Next Payment</label> <span><?php echo date('F d, Y', strtotime($subscription['next_payment_date']) );?></span></li>
							<?php if( $subscription['trial_expiry_date'] ):?><li><label>Trial End</label> <span><?php echo date('F d, Y', strtotime($subscription['trial_expiry_date']) );?></span></li><?php endif;?>
							<?php if($subscription['trial_expiry_date'] && $subscription_type == "trial"){ ?>
								<li>You Have <?php echo ($trial_expiration_date > 0) ? $trial_expiration_date : "0"; ?> Days Left On Trial</li>
							<?php } ?>
						</ul>
                        <?php else: ?>
                            <p class="text-center">You don't have any package subscription</p>
                        <?php endif; ?>
					</div>
					<?php if (!empty($subscription)) : ?>
					    <p class="text-center small">Auto Renew is Enabled. To change this, go to Account Settings</p>
					<?php endif; ?>
					<?php if (!empty($subscription) && $subscription_type == "trial") : ?>
						<div class="button-holder">
					    	<button type="button" class="action btn btn-lg btn-danger fx-btn" data-toggle="modal" data-target="#access-upgrade-modal">
							  Upgrade Your Trial
							</button>
					    </div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-mid-header">
					<h2>Select Product From The List Below:</h2>
				</div>
			</div>
			<div class="col-md-12">
				<ul class="fx-list-training">
					<?php  
						if(!in_array($subscription_product_id, $signal_ids)){
					?>
						<li>
							<span>Training</span>
							<a href="<?php bloginfo('url');?>/basic-training/" class="action btn btn-danger fx-btn">Explore Product</a>
						</li>
					<?php

						}
					?>
					<li>
						<span>Market Trade Signals</span>
						<a href="<?php bloginfo('url');?>/trade-signals/" class="action btn btn-danger fx-btn"><?php echo $market_scanner || current_user_can('administrator') ? 'Explore Product' : 'Upgrade Now <i class="fa fa-shopping-cart"></i>';?></a>
					</li>
					<li>
						<span>1 on 1 Coaching</span>
						<a href="<?php bloginfo('url');?>/coaching/" class="action btn btn-danger fx-btn"><?php echo $coaching || current_user_can('administrator')  ? 'Explore Product' : 'Upgrade Now <i class="fa fa-shopping-cart"></i>';?></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
<?php
$step = 'accessed_products';
$checklist = get_user_checklist();
if ( isset( $checklist[ $step ] ) && ! $checklist[ $step ] && ! empty( $subscription ) ) : ?>
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                var ajaxUrl = fx.ajax_url;
                var data = {
                    'action': 'checklist_pass',
                    'step': '<?= $step; ?>'
                };
                $.post(ajaxUrl, data);
            }, 5000);
        });
    </script>
<?php endif; ?>

<!-- Modal -->
<div class="modal fade modal-mini" id="access-upgrade-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
      	<h4 class="modal-title" id="exampleModalLabel">Would You Like To Upgrade Your Trial Account?</h4>
        Activating your Trial account before the "Renewal" date will charge the card we have on the file today. You will be active to qualify for personal volume, group volume and receive compensation
      </div>
      <div class="modal-footer">
        <a href="<?php echo get_option('home'); ?>/renewal" class="btn btn-primary">Yes, Upgrade My Account Now!</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No, Just Continue Trial</button>
        <div class="modal-footer-note">
        	...Or You Can Go To "Account Settings" <a href="/my-account">click here</a>
        </div>
      </div>
    </div>
  </div>
</div>


<?php get_footer(); ?>
