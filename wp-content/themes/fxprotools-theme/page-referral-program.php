<?php
/*
Template Name: Referral Program
*/
$active_referrals = get_user_active_referrals();
$referral_count = count( $active_referrals);

// sort the active referrals by date ASC
usort($active_referrals, function($a, $b) {
	return strtotime($a->date) - strtotime($b->date);
});

$valid_referral_count = 0;
foreach ($active_referrals as $active_referral) {
    // first referral will just check if there's a paid subscription
    if ($valid_referral_count == 0) {
        $valid_referral_count++;
        continue;
    }

    // 2nd to nth referral
    // valid only if subscribed for more than 30days
	if(strtotime($active_referral->date) < strtotime('+1 days')) {
		$valid_referral_count++;
	}
}

$username = wp_get_current_user()->user_login;
$referral_link = get_highest_converting_funnel_link();
?>
<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-dashboard'); ?>

	<div class="container page-dashboard">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1><span style="color: #de1515;">Wait!</span> Did You Know Your Product Access Could Be Free?</h1>
					<p><span class="label-red">IMPORTANT</span> When You Refer <u>3</u> People To Our Products - Yours Become Free</p>
				</div>
			</div>
			<div class="col-md-8">
				<?php
					// Metabox Page Template Option - Video Embed
					echo get_mb_pto1( 'video_embed', 'pto1' );
				?>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4">
				<div class="fx-board checklist">
					<div class="fx-board-header w-text">
                        <div class="group-title">
                            <span class="title">Refer 3 & Your Free</span>
                            <span class="sub">With Our Referral program You Can Gain Access To Your Membership For Free!</span>
                        </div>
                        <div class="group-counter">
                            <?php echo display_fx_gauge(3, $valid_referral_count); ?>
                        </div>
                        <div class="clearfix"></div>
					</div>
					<ul class="fx-board-list">
						<li>
							<span class="fx-checkbox <?php echo $valid_referral_count > 0 ? 'checked' : '';?>"></span>
							<span class="fx-text">Refer First Friend</span>
						</li>
						<li>
							<span class="fx-checkbox <?php echo $valid_referral_count > 1 ? 'checked' : '';?>"></span>
							<span class="fx-text">Refer Second Friend</span>
						</li>
						<li>
							<span class="fx-checkbox <?php echo $valid_referral_count > 2 ? 'checked' : '';?>"></span>
							<span class="fx-text">Refer Third Friend</span>
						</li>
						<?php if(is_user_fx_distributor()) : ?>
						<li>
							<h4 class="inline-block">Fast Start Bonuses</h4>
							<a href="<?php bloginfo('url');?>/wallet/bonuses/" class="btn btn-danger btn-lg fx-btn inline-block pull-right">View</a>
							<div class="clearfix"></div>
						</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-ref-links">
					<div class="row">
						<div class="col-md-6">
							<div class="fx-ref-link">
								<div class="fx-ref-title">
									<h3>Option 1: Refer To Someone</h3>
									<p>Tell your friends and coworkers about <?php echo get_bloginfo('name'); ?></p>
								</div>
								<div class="box">
									Share your unique referral link
									<div class="link">

										<?php
											// commented out this code in regards to this task https://github.com/MastermindMedia/FXProTools/issues/148
											// if(strpos(get_the_author_meta('user_login', get_current_user_id()), ' ') > 0){
											// 	echo $referral_link; ?><!--?ref=--><?php //echo affwp_get_affiliate_id(wp_get_current_user()->ID);
											// }else{
											// 	echo $referral_link; ?><!--?ref=--><?php //echo urlencode($username);
											// }
											$p_h = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://' . $_SERVER['HTTP_HOST']; 
											$temp_referral_link =  $p_h . "/lp1/";
											if(strpos(get_the_author_meta('user_login', get_current_user_id()), ' ') > 0){
												echo $temp_referral_link; ?>?ref=<?php echo affwp_get_affiliate_id(wp_get_current_user()->ID);
											}else{
												echo $temp_referral_link; ?>?ref=<?php echo urlencode($username);
											}
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="fx-ref-link">
								<div class="fx-ref-title">
									<h3>Option 2: With Our Sales Funnels</h3>
									<p>Promoting your business should be easy</p>
								</div>
								<div class="box">
									<div class="text-center">
										We provide you with all the Marketing Sales <br/>Funnels you need to explain <?php echo get_bloginfo('name'); ?>
									</div>
									<a href="<?php bloginfo('url');?>/markting/funnels" class="btn btn-danger block padding-md">
										Access Your Sales Funnels
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<br/>
			<div class="col-md-12">
				<a id="xbtn" href="<?php bloginfo('url');?>/access-products?activate_stage_2_nav=true" class="btn btn-danger block p-m skip-referral">Thanks For Letting Me Know... To Continue Click Here!</a>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
