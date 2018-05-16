<?php 
/*
Template Name: Cancel Step 1
*/
get_header(); 
?>
	<div class="container cancellation-step cancellation-step-2">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>You have not cancelled your account just yet... </h1>
					<p>Select an option below:</p>
				</div>
			</div>
			<div class="col-md-7">
				<div class="fx-video-container">
					<iframe width="100%" height="360" src="https://www.youtube.com/embed/Ex8Q1WWh2jo" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="text-center">
					<h4>We're sorry to see you go...</h4>
					<p>Mind giving us a little feedback to help us improve?</p>
				</div>
			</div>
			<div class="col-md-5">
				<div class="fx-board checklist cancellation-list">
					<ul class="fx-board-list">
						<li>
							<a href="<?php echo get_option('home'); ?>/cancel-step-2/<?php get_query_string(); ?>&option=missing-features">
								<span class="fx-checkbox"></span>
								<span class="fx-text">Missing Features</span>
							</a>
						</li>
						<li>
							<a href="<?php echo get_option('home'); ?>/cancel-step-2/<?php get_query_string(); ?>&option=switching">
								<span class="fx-checkbox"></span>
								<span class="fx-text">Switching To Another Product Or Service</span>
							</a>
						</li>
						<li>
							<a href="<?php echo get_option('home'); ?>/cancel-step-2/<?php get_query_string(); ?>&option=not-using">
								<span class="fx-checkbox"></span>
								<span class="fx-text">Not Using It Enough</span>
							</a>
						</li>
						<li>
							<a href="<?php echo get_option('home'); ?>/cancel-step-2/<?php get_query_string(); ?>&option=forex">
								<span class="fx-checkbox"></span>
								<span class="fx-text">I'm Not Doing Forex Anymore</span>
							</a>
						</li>
						<li>
							<a href="<?php echo get_option('home'); ?>/cancel-step-2/<?php get_query_string(); ?>&option=bugs">
								<span class="fx-checkbox"></span>
								<span class="fx-text">Bugs or Support Problems</span>
							</a>
						</li>
						<li>
							<a href="<?php echo get_option('home'); ?>/cancel-step-2/<?php get_query_string(); ?>&option=other">
								<span class="fx-checkbox"></span>
								<span class="fx-text">Other Reasons</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>