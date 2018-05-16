<?php
/*
Template Name: F3
*/
$ref = isset( $_GET['ref'] ) ? $_GET['ref'] : 'business.admin';
$ref_user_id = affwp_get_affiliate_user_id( $ref );
?>
<?php get_header(); ?>
	
<div class="fx-capture-page f3">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
				<div class="text-heading m-b-md">
					<h2><span class="text-red">FREE!</span> “How To Get Up To 37 Deposits Per Month, Earn Upwards Of $4,954.35 While You Sleep At Night, And NEVER EVER Have To Recruit A Single Person!” </h2>
				</div>
				<div class="tab-form" role="tabpanel">
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active">
							<a href="#step1" aria-controls="step1" role="tab" data-toggle="tab">
								<label>Step 1</label>
								<span>How did you<br/> hear about us?</span>
							</a>
						</li>
						<li role="presentation">
							<a href="#step2" aria-controls="step2" role="tab" data-toggle="tab">
								<label>Step 2</label>
								<span>Your Access<br/> Information</span>
							</a>
						</li>
						<li role="presentation">
							<a href="#step3" aria-controls="step3" role="tab" data-toggle="tab">
								<label>Step 3</label>
								<span>Get instant<br/> access now!</span>
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade in active" id="step1">
							<div class="content">
								<span class="title">How Did You Hear About Us?</span>
								<ul class="checklist">
									<li>
										<div class="radio fx-radio-group">
											<label>
												<input type="radio" name="survey">
												<span class="fx-radio"></span>
												<span class="fx-text">Television</span>
											</label>
										</div>
									</li>
									<li>
										<div class="radio fx-radio-group">
											<label>
												<input type="radio" name="survey">
												<span class="fx-radio"></span>
												<span class="fx-text">Facebook</span>
											</label>
										</div>
									</li>
									<li>
										<div class="radio fx-radio-group">
											<label>
												<input type="radio" name="survey">
												<span class="fx-radio"></span>
												<span class="fx-text">Email Newsletter</span>
											</label>
										</div>
									</li>
									<li>
										<div class="radio fx-radio-group">
											<label>
												<input type="radio" name="survey">
												<span class="fx-radio"></span>
												<span class="fx-text">Other</span>
											</label>
										</div>
									</li>
								</ul>

								<a href="#step2" data-toggle="tab" class="btn btn-danger btn-lg fx-btn block">Next</a>
							</div>
							<p class="disclaimer"><img src="<?php bloginfo('template_url'); ?>/assets/img/lock.png" class="img-responsive"> This is free information and credit card is NOT required.</p>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="step2">
							<div class="progress m-t-md">
								<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:50%">
									Almost Complete
								</div>
							</div>

							<div class="content">
								<form class="fx-sendgrid" method="post">
									<span class="title">Enter Your Access Information</span>
									<div class="form-group m-t-md">
										<input type="text" class="form-control" name="name"  placeholder="Your Name">
									</div>
									<div class="form-group m-b-md">
										<input type="email" class="form-control" name="email" placeholder="Your Email Address">
									</div>
									<input type="hidden" name="funnel_id" value="f3">
									<input type="hidden" name="affiliate_user_id" value="<?php echo $ref_user_id;?>">
									<input type="hidden" name="redirect_to" value="<?php echo site_url ('lp3/?ref='.$ref );?>">
									<button type="submit" class="btn btn-danger btn-lg block">Next</button>
								</form>
							</div>
							<p class="disclaimer"><img src="<?php bloginfo('template_url'); ?>/assets/img/lock.png" class="img-responsive"> This is free information and credit card is NOT required.</p>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="step3">
							<!-- After clicking next on step2, user will be redirected to f3 landing page -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>