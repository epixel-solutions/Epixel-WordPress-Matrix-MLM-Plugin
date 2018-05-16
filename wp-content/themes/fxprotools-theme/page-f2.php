<?php
/*
Template Name: F2
*/
$ref = isset( $_GET['ref'] ) ? $_GET['ref'] : 'business.admin';
$ref_user_id = affwp_get_affiliate_user_id( $ref );
?>
<?php get_header(); ?>

<div class="fx-capture-page f2">
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
				<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
					<div class="text-center">
						<p class="intro-note">ONLY 3% OF PEOPLE WILL EVER LEARN</p>
						<p class="intro-note">HOW TO <span class="text-yellow">TRADE FOREX PROPERLY...</span></p>
						<p class="intro-note sm m-t-sm">"DISCOVER HOW ThESE COLLEGE KIDS WENT FROM BROKE TO LIVING IN MIAMI BEACH!"</p>
					</div>
				</div>
			</div>
			<div class="row row-video">
				<div class="col-xs-12 col-sm-8 col-md-8">
					<?php 
						// Metabox Page Template Option - Video Embed 
						echo get_mb_pto1( 'video_embed', 'pto2' );
					?>
					<p class="video-disclaimer">* Submitting This Survey Will NOT Interrupt The Video *</p>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4">
					<div class="fx-board checklist">
						<div class="fx-board-header w-text text-center">
							<h3 class="inline-block">Tell Us Your <br/> <span class="label-red inline-block m-t-xs">Biggest Frustration</span></h3>
						</div>
						<ul class="fx-board-list f2-group-options">
							<li>
								<div class="radio fx-radio-group">
									<label>
										<input type="radio" name="f2-survey">
										<span class="fx-radio"></span>
										<span class="fx-text">I currently don't make enough money</span>
									</label>
								</div>
							</li>
							<li>
								<div class="radio fx-radio-group">
									<label>
										<input type="radio" name="f2-survey">
										<span class="fx-radio"></span>
										<span class="fx-text">I wish i had more time to have fun</span>
									</label>
								</div>
							</li>
							<li>
								<div class="radio fx-radio-group">
									<label>
										<input type="radio" name="f2-survey">
										<span class="fx-radio"></span>
										<span class="fx-text">I have not been happy in long awhile.</span>
									</label>
								</div>
							</li>
							<li>
								<div class="radio fx-radio-group">
									<label>
										<input type="radio" name="f2-survey">
										<span class="fx-radio"></span> 
										<span class="fx-text">All or several of the answers above.</span>
									</label>
								</div>
							</li>
							<li>
								<div class="radio fx-radio-group">
									<label>
										<input type="radio" name="f2-survey">
										<span class="fx-radio"></span> 
										<span class="fx-text">Other</span>
									</label>
								</div>
							</li>
						</ul>
						<div class="f2-group-form">
							<form class="fx-sendgrid" method="post">
								<div class="form-group">
									<input type="text" class="form-control" name="name" placeholder="Your Name">
								</div>
								<div class="form-group">
									<input type="text" class="form-control" name="email" placeholder="Your Email">
								</div>
								<div class="form-group">
									<input type="text" class="form-control" name="contact" placeholder="Phone #(Optional)">
								</div>
								<input type="hidden" name="funnel_id" value="f2">
								<input type="hidden" name="affiliate_user_id" value="<?php echo $ref_user_id;?>">
								<input type="hidden" name="redirect_to" value="<?php echo site_url ('lp2/?ref='.$ref );?>">
								<button type="submit" class="btn btn-danger btn-lg block">Submit</button>
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
					<div class="text-center">
						<p><span class="label-red">IMPORTANT:</span> If You LOVE MONEY & WANT MORE Watch The Entire Video!</p>
					</div>
					<hr/>
					<h4 class="text-center">AS SEEN ON</h4>
					<img src="<?php bloginfo('template_url'); ?>/assets/img/seen-on.jpg" class="centered-block img-responsive">
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