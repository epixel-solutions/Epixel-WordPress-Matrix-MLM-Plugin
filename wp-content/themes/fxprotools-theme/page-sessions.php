<?php 
/*
Template Name: Page Sessions
*/
get_header(); 
?>

	<?php get_template_part('inc/templates/nav-products'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>Coaching / Webinars</h1>
					<p>Check Below For Your Coaching Webinars</p>
				</div>
			</div>
			<div class="col-md-12">
				<div class="fx-coaching-tab">
					<a href="<?php bloginfo('url');?>/coaching" class="btn btn-danger no-border-radius pull-right">Schedule Coaching</a>
					<div role="tabpanel">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation">
								<a href="#one" aria-controls="one" role="tab" data-toggle="tab">Upcoming Sessions</a>
							</li>
							<li role="presentation" class="active">
								<a href="#two" aria-controls="two" role="tab" data-toggle="tab">Past Sessions</a>
							</li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane padding-md active" id="one">
								<div class="row">
									<div class="col-md-3">
										Start Date
									</div>
									<div class="col-md-3">
										Start Time
									</div>
									<div class="col-md-3">
										Topic Goes Here ...
									</div>
									<div class="col-md-3 text-center">
										<a href="#">Meeting Link</a>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane padding-md" id="two">
								<div class="row">
									<div class="col-md-3">
										Start Date
									</div>
									<div class="col-md-3">
										Start Time
									</div>
									<div class="col-md-3">
										Topic Goes Here ...
									</div>
									<div class="col-md-3 text-center">
										<a href="#">Meeting Link</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>