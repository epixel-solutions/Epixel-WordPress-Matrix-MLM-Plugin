<?php 
/*
Template Name: Forgot Password
*/
get_header(); ?>

<div class="left">
	<div class="content-wrapper">
		<div class="content">						
			<h2>Applying With Us</h2>
			<p class="sub"><?php echo get_bloginfo('name'); ?> Which provides forex educations & training is accessible by invitation only.</p>
			<p class="small">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. sit amet, consectetur adipisicing. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. sit amet, consectetur adipisicing</p>
			<p class="small">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. sit amet, consectetur adipisicing. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. sit amet, consectetur adipisicing</p>
			<p class="small">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. sit amet, consectetur adipisicing. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. sit amet, consectetur adipisicing</p>
		</div>
	</div>
</div>
<div class="right">
	<div class="content-wrapper">
		<div class="content">
			<h2>Login Area</h2>
			<p class="sub">Reset your password</p>
			<p class="small">You can reset your password by simply providing your recovery email</p>
			<form  action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" method="post"> 
				<div class="form-group">
					<input type="email" class="form-control no-border-radius" id="user_login" name="user_login">
				</div>
				<button type="submit"  name="submit" class="btn btn-lg btn-danger fx-btn block">Submit Recovery Request</button>
				<a href="<?php bloginfo('url'); ?>/login">Sign Into Your Account</a>
			</form>
		</div>
	</div>
</div>

<?php get_footer(); ?>