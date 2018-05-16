<?php
/*
Template Name: WC Account
*/


get_header();
?>

<br><br>
<div class="container woocommerce">
	<div class="row">
		<div class="col-md-12">
			<div class="fx-header-title">
				<h1>My Account</h1>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="fx-tabs-vertical marketing-contacts">
						<ul class="nav nav-tabs">
							<li ><a href="<?php echo home_url('my-account/?tab=a');?>" > <i class="fa fa-user visible-xs"></i> <span>Your Information</span></a></li>
							<li><a href="<?php echo home_url('my-account/?tab=b');?>" > <i class="fa fa-address-card-o visible-xs"></i> <span>Edit Contact</span></a></li>
							<li><a href="<?php echo home_url('my-account/?tab=c');?>" > <i class="fa fa-address-card-o visible-xs"></i> <span>Billing</span></a></li>
							<li><a href="<?php echo home_url('my-account/?tab=d');?>" > <i class="fa fa-credit-card visible-xs"></i> <span>Purchases</span></a></li>
							<li><a href="<?php echo home_url('my-account/?tab=e');?>" > <i class="fa fa-star-o visible-xs"></i> <span>Memberships</span></a></li>
							<li class="hide-on-customer"><a href="<?php echo home_url('my-account/?tab=f');?>" > <i class="fa fa-users visible-xs"></i> <span>Your Matrix</span></a></li>
							<li><a href="<?php echo home_url('my-account/?tab=g');?>" > <i class="fa fa-list visible-xs"></i> <span>Recent Activity</span></a></li>
							<li><a href="<?php echo home_url('my-account/?tab=h');?>" > <i class="fa fa-gift visible-xs"></i> <span>Your Sponsor</span></a></li>
							<li><a href="<?php echo wp_logout_url('/login/'); ?>"><i class="fa fa-sign-out visible-xs"></i> <span>Logout</span></a></li>
						</ul>
						<div class="tab-content">
							<?php while ( have_posts() ) : the_post(); ?>
								<?php the_content(); ?>
							<?php endwhile; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
