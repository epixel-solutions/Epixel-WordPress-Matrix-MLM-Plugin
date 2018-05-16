<?php
/*
Template Name: F1
*/
$ref = isset( $_GET['ref'] ) ? $_GET['ref'] : 'business.admin';
$ref_user_id = affwp_get_affiliate_user_id( $ref );
?>
<?php get_header(); ?>

<div class="fx-capture-page f1">
	<div class="fx-red-note">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<p>The Map That Teaches You Specialized Market Knowledge! </p>
				</div>
			</div>
		</div>
	</div>
	<div class="section-one">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 visible-sm visible-xs">
					<img src="<?php bloginfo('template_url'); ?>/assets/img/funnel-bg/f1-bg.png" class="img-responsive">
				</div>		
				<div class="col-xs-12 col-sm-12 col-md-5">
					<div class="content-wrapper">
						<div class="content">
							<div class="text-center">
								<span>An Exclusive Message from Industy Leaders...</span>
								<h1>Join The Exciting Market Of  Foreign Exchange Trading</h1>
								<p>Our elite group of traders have achieved master level profits with strategies & techniques learned directly from our training courses. Achieving profits of $4,954.35 & higher at night while they sleep!</p>
							</div>
							<form class="fx-sendgrid" method="post">
								<div class="fx-input-email">
									<input type="email" class="form-control" placeholder="E-mail ..." name="email">
								</div>
								<div class="text-center m-t-md">
									<input type="hidden" name="funnel_id" value="f1">
									<input type="hidden" name="affiliate_user_id" value="<?php echo $ref_user_id;?>">
									<input type="hidden" name="redirect_to" value="<?php echo site_url ('lp1/?ref='.$ref );?>">
									<button type="submit" class="btn btn-danger btn-lg">Click To Get Access</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="section-two">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="header-text">
						<span>Sneak Peek Of What You Get With Us</span>
						<h2>Everything You Need To Become A Better Market Trader</h2>
						<span class="red"></span>
					</div>
				</div>
			</div>
			<div class="row row-item-info">
				<div class="col-xs-6 col-sm-3 col-md-3">
					<div class="item-info">
						<div class="image">
							<img src="<?php bloginfo('template_url'); ?>/assets/img/ico-dark1.png" class="centered-block img-responsive">
						</div>
						<span>Interactive <br/>Education Lessons</span>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3 col-md-3">
					<div class="item-info">
						<div class="image">
							<img src="<?php bloginfo('template_url'); ?>/assets/img/ico-dark2.png" class="centered-block img-responsive">
						</div>
						<span>Live 24 Hour <br/>Signals</span>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3 col-md-3">
					<div class="item-info">
						<div class="image">
							<img src="<?php bloginfo('template_url'); ?>/assets/img/ico-dark3.png" class="centered-block img-responsive">
						</div>
						<span>Live Trading <br/>Webinars</span>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3 col-md-3">
					<div class="item-info">
						<div class="image">
							<img src="<?php bloginfo('template_url'); ?>/assets/img/ico-dark4.png" class="centered-block img-responsive">
						</div>
						<span>Updates On <br/>Market News</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="fx-red-banner">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
					<h2 class="m-t-none"2>Confidently Trade the Market with Our Expert Help</h2>
					<a href="#" class="btn btn-lg btn-outline m-t-xs reserve-your-seat">Click To Get Access</a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>