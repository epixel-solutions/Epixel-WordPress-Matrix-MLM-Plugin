<?php
/*
Template Name: Dashboard
*/
$checklist = get_user_checklist();

/*
// Get Your CPS Shirt
if ( ! $checklist['got_shirt'] ) {
	if ( Woocommerce_Settings::has_claimed_shirt() ) {
		pass_onboarding_checklist( 'got_shirt' );
	}
}

*/



// Share Video && Refer a friend
if ( ! $checklist['shared_video'] ) {
	$funnels = get_funnels();
	$shared_video = $checklist['shared_video'];
	foreach ( $funnels as $funnel ) {
		$stats = get_funnel_stats( $funnel->ID );

		foreach ( $stats as $stat ) {
			if ( isset($stat['page_views']) && $stat['page_views']['unique'] >= 1 && ! $shared_video ) {
				pass_onboarding_checklist( 'shared_video' );
				$shared_video = true;
			}

			// exit the second loop as soon as both are satisfied
			if ( $shared_video ) {
				break;
			}
		}
		// exit the first loop
		if ( $shared_video ) {
			break;
		}
	}
}

// Refer a friend
if ( ! $checklist['referred_friend'] ) {
	$active_referrals = get_user_active_referrals();
	if ( count( $active_referrals ) > 0 ) {
		pass_onboarding_checklist( 'referred_friend' );
	}
}

// Refresh the checklist
$checklist = get_user_checklist();

$accomplished = 0;
if ( ! empty( $checklist ) ) {
	foreach ( $checklist as $list ) {
		if ( $list ) {
			$accomplished ++;
		}
	}
}

$dashboard_checklist = [
	'verified_email'    => [
		'title'   => 'Verify your e-mail',
		'subtext' => 'Check your Inbox and confirm your email to complete this step, or you can confirm it is correct in "My Account" by <a href="/my-account">clicking here</a>',
		'access'  => 'unlocked'
	],
	'verified_profile'  => [
		'title'   => 'Update/Verify Profile (SMS #)',
		'subtext' => 'Add / Update your Phone Number in your profile to get instant notifications by going to "My Account" <a href="/my-account">click here</a>',
		'access'  => 'unlocked'
	],
	'scheduled_webinar' => [
		'title'   => 'Schedule For Webinar',
		'subtext' => 'Don\'t miss out weekly Q&A webinars to answer all your questions, click the "Reserve A Seat" button.',
		'access'  => 'unlocked'
	],
	'got_shirt'         => [
		'title'   => 'Get your CPS Shirt',
		'subtext' => 'Get A CPS T-shirt from our store 75% OFF by clicking on "Get CPS Shirt Button" or by <a href="/product/copy-profit-success-tshirt/">clicking here</a>.',
		'access'  => ( get_user_stage_lvl() > 1 ) ? 'unlocked' : 'locked'
	],
	'accessed_products' => [
		'title'   => 'Access your product',
		'subtext' => 'Full Access to the products you purchased 24/7, <a href="/access-products">click here</a>.',
		'access'  => ( get_user_stage_lvl() > 1 ) ? 'unlocked' : 'locked'
	],
	'shared_video'      => [
		'title'   => 'Share Video',
		'subtext' => 'Use our special invitation video to share this valuable skillset with someone. Start sharing by <a href="/marketing/funnels">clicking here</a>.',
		'access'  => ( get_user_stage_lvl() > 1 ) ? 'unlocked' : 'locked'
	],
	'referred_friend'   => [
		'title'   => 'Refer A Friend',
		'subtext' => 'Refer people to our platform & we will reward you! Find out more about our referral program by <a href="/referral-program">clicking here</a>.',
		'access'  => ( get_user_stage_lvl() > 1 ) ? 'unlocked' : 'locked'
	],
];

?>
<?php get_header(); ?>

<?php get_template_part( 'inc/templates/nav-dashboard' ); ?>
<div class="container page-dashboard">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="fx-header-title">
				<?php if ( is_user_fx_customer() ) : ?>
                    <h1>Welcome! Thanks for Being A Loyal Customer</h1>
                    <p><span class="label-red">Step 1:</span> Onboarding Message &amp; Getting The Most Out Of <?php echo get_bloginfo('name'); ?>!</p>
				<?php elseif ( is_user_fx_distributor() ) : ?>
                    <h1>Welcome! Thanks for Being A Loyal Distributor</h1>
                    <p><span class="label-red">Step 1:</span> Onboarding Message &amp; Getting The Most Out Of <?php echo get_bloginfo('name'); ?>!</p>
				<?php else : ?>
                    <h1>Welcome! Thanks for Being A Loyal Distributor</h1>
                    <p><span class="label-red">Step 1:</span> Onboarding Message &amp; Getting The Most Out Of <?php echo get_bloginfo('name'); ?>!</p>
				<?php endif; ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-8">
							<?php
							// Metabox Page Template Option - Video Embed
							echo get_mb_pto1( 'video_embed', 'pto1' );
							?>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <a href="#" class="btn btn-danger btn-lg btn-lg-w-text block btn-ico-lg btn-one">
                                        <div class="left">
                                            <img src="<?php bloginfo( 'template_url' ); ?>/assets/img/ico1.png" class="img-responsive">
                                        </div>
                                        <div class="right">
                                            Reserve A Seat
                                            <span>** Seats Are Limited **</span>
                                        </div>
                                    </a>
                                </div>
								<?php if(is_nav_locked() && get_user_stage_lvl() < 2 ) : ?>
								<div class="col-xs-12 col-sm-6 col-md-6">
									<div class="image-lock-overlay">
										<div>
											<i class="fa fa-lock" aria-hidden="true"></i>
											<span>Complete Steps</br>To Unlock Buttons</span>
										</div>
									</div>
                                    <a href="#" class="btn btn-danger btn-lg block btn-ico-lg btn-two"></a>
                                </div>
								<?php else : ?>	
								<div class="col-xs-12 col-sm-6 col-md-6">
                                    <a href="/<?php echo Woocommerce_Settings::POST_NAME_FREE_SHIRT; ?>" class="btn btn-danger btn-lg block btn-ico-lg btn-two"></a>
                                </div>
                                
								<?php endif; ?>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4">
                            <div class="fx-board checklist">
                                <div class="fx-board-header">
                                    <div class="group-title">
                                        <span class="title">Onboarding Checklist</span>
                                        <span class="sub">Learn More About CPS 3.0</span>
                                    </div>
                                    <div class="group-counter">
	                                    <?php echo display_fx_gauge(count($dashboard_checklist), $accomplished); ?>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <ul class="fx-board-list w-toggle">
									<?php
										if( is_nav_locked() ) :
											foreach ( $dashboard_checklist as $step => $dashboard_checklist ) :
									?>
                                        <li class="<?php if( $dashboard_checklist['access'] === 'locked' ) echo "list-locked"; ?>">
											<?php if( $dashboard_checklist['access'] === 'locked' ) : ?>
												<i class="fa fa-lock" aria-hidden="true"></i>
											<?php elseif( $dashboard_checklist['access'] === 'unlocked' ) : ?>
												<span class="fx-checkbox <?php echo ! empty( $checklist[ $step ] ) ? 'checked' : ''; ?>"></span>
											<?php endif; ?>
											<span class="fx-text"><?= $dashboard_checklist['title']; ?></span>
                                            <?php if( $dashboard_checklist['access'] === 'unlocked' ) : ?>
											<div class="content">
												<?= $dashboard_checklist['subtext']; ?>
                                            </div>
											<?php endif; ?>
                                            <span class="fa fa-angle-down icon"></span>
                                        </li>
									<?php 
											endforeach;
										else :
											foreach ( $dashboard_checklist as $step => $dashboard_checklist ) :
									?>
										<li>
											<span class="fx-checkbox <?php echo ! empty( $checklist[ $step ] ) ? 'checked' : ''; ?>"></span>
											<span class="fx-text"><?= $dashboard_checklist['title']; ?></span>
											<div class="content">
												<?= $dashboard_checklist['subtext']; ?>
											</div>
											<span class="fa fa-angle-down icon"></span>
										</li>
									
									<?php 
											endforeach;
										endif;
									?>
                                    <li><a href="<?php echo get_checklist_next_step_url(); ?>" class="btn btn-danger btn-lg fx-btn block">I'm ready for the next step</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
