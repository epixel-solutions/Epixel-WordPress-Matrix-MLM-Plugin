<?php
/*
Template Name: Cancel Step 2
*/
	$option = $_GET['option'];
?>
<?php get_header(); ?>

	<div class="container cancellation-step cancellation-step-2">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>
						<?php 
							if($option == "missing-features"){
								echo "YIKES! We're Missing Features?";
							}
							if($option == "switching"){
								echo "Oh Man! You're Going Elsewhere?";
							}
							if($option == "not-using"){
								echo "Sorry To Hear Your Not Using Enough!";
							}
							if($option == "forex"){
								echo "Giving Up?! Don't Be Discouraged...Let Us Help!";
							}
							if($option == "bugs"){
								echo "We're Very Sorry We Caused You Trouble...";
							}
							if($option == "other"){
								echo "We Want To Hear Your Reasons! Seriously.";
							}
						?>
					</h1>
				</div>
				<div class="fx-video-container">
					<iframe width="100%" height="450" src="https://www.youtube.com/embed/Ex8Q1WWh2jo" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="text-center m-b-md">
					<h3>We're sorry to see you go...</h3>
					<p>Mind giving us a little feedback to help us improve?</p>
				</div>
				<div class="panel panel-default">
					<h2>
						<?php 
							if($option == "missing-features"){
								echo "Could you help us understand exactly what features<br> we're missing that you wish we had?";
							}
							if($option == "switching"){
								echo "Could you help us understand exactly who you believe does this better than us?";
							}
							if($option == "not-using"){
								echo "We can give you more time if you would like?";
							}
							if($option == "forex"){
								echo "We can give you more time if you would like?";
							}
							if($option == "bugs"){
								echo "Could you tell us what Bugs or Support Issues you have experienced with us?";
							}
							if($option == "other"){
								echo "Please tell us why your leaving?";
							}
						?>
					</h2>
					<form class="m-b-lg">
						<p><input class="form-control" type="text" placeholder="What Features Are We Missing?"></p>
						<p><input class="form-control" type="email" placeholder="Your Email Address"></p>
						<button type="submit" class="btn btn-danger btn-lg btn-block">Continue To Finalize Cancelation</button>
					</form>
					<h3><?php echo get_bloginfo('name'); ?> is the leading Binary Options & Forex Platform...but maybe you feel lost in your journey to learn the foreign exchange market....</h3>
					<h3><a href="#">Join us for one of our LIVE Webinars</a> hosted by our Expert Forex Veterans dedicated to seeing you win!</h3>
				</div>
				<div class="panel panel-default">
					<h2>Get Live Training?</h2>
					<h3 class="text-center">You can join our experts live on our next on line webinar for an in-depth look at our platform and recieve free forex tips.</h3>
					<div class="text-center m-b-md btn-holder">
						<a href="#" class="btn btn-success btn-lg">Reserve Your Spot</a>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="row">
						<div class="col-md-5">
							<div class="cancel-box">
								<h3>Pause Your Account</h3>
								<div class="btn-holder">
									<a href="<?php echo get_option('home'); ?>/marketing/contacts/user/<?php get_query_string(); ?>&cancel=yes" class="btn btn-success btn-lg">Pause Account</a>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<h2 class="cancel-or">OR</h2>
						</div>
						<div class="col-md-5">
							<div class="cancel-box">
								<h3>Cancel My Account</h3>
								<div class="btn-holder">
									<a href="<?php echo get_option('home'); ?>/marketing/contacts/user/<?php get_query_string(); ?>&cancel=yes" class="btn btn-success btn-lg">Continue To Cancel</a>
								</div>
							</div>
						</div>
					</div>
					<div class="text-center">
						<p><strong>NOTE:</strong> If you Cancel your account you will lose all your progress, all your customers data, all your bonuses, all your training, all your funnels and any eligibility related to your business... </p>
						<p>You can pause your account to keep your pages live until you get back for only $9.99/month</p>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>