<?php 
/*
Template Name: Login
*/
get_header();
?>

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
            <p class="sub">Returning to this website?</p>
            <p class="small">You can sign into your account by using the username or email and password used during registration process</p>
			<?php
			$username = '';
			$error_class = '';
			$redirect_to = '';
			if ( isset( $_GET['username'] ) ) {
				$username = sanitize_text_field( $_GET['username'] );
				$error_class = 'has-error';
			}
			if ( isset( $_GET['redirect_to'] ) ) {
				$redirect_to = urlencode( $_GET['redirect_to'] );
			}
			if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) : ?>
                <p class="small error">ERROR: The password you entered for the username <strong><?= $username; ?></strong> is incorrect.
                    <a href="<?php bloginfo( 'url' ); ?>/forgot-password"><strong>Lost Your Password?</strong></a>
                </p>
                <br/>
			<?php endif; ?>
            <form action="<?php echo site_url( 'wp-login.php?action=login', 'login_post' ) ?>" method="post" class="<?= $error_class; ?>">
                <div class="form-group">
                    <input type="text" class="form-control no-border-radius" id="email" name="log" value="<?= $username; ?>" required placeholder="username or email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control no-border-radius" id="pwd" name="pwd" required placeholder="password">
                </div>
                <input type="hidden" name="redirect_to" value="<?= $redirect_to; ?>"/>
                <button type="submit" class="btn btn-lg btn-danger fx-btn block">Login</button>
                <a href="<?php bloginfo( 'url' ); ?>/forgot-password">Forgot Your Password</a>
            </form>
        </div>
    </div>
</div>
<?php get_footer(); ?>
