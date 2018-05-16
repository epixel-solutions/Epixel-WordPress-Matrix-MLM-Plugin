<?php 
/*
Template Name: Logout Notice
*/
get_header(); 
?>

<div class="fx-landing main">
    <?php get_template_part('inc/templates/nav-capture-page'); ?>
    <div class="fx-red-note">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p><?php echo get_bloginfo('name'); ?> is the map that teaches you specialized market knowledge!</p>
                </div>
            </div>
        </div>
    </div>
    <div class="log-out-notice">
        <div class="section-w-panel">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body ">
                                <div class="text-center heading">
                                    <img class="img-responsive centered-block" src="<?php echo site_url('/wp-content/uploads/2017/10/log-out-image.png');?>"/>
                                    <h1>You Have Logged Out<br/>Of Your Account</h1>
                                    <span class="label-red">Thank you for using our <?= get_bloginfo( 'name' ); ?></span>
                                    <p class="m-t-md">Please <a href="<?php echo site_url('login'); ?>">click here</a> to login back to our site</p>
                                </div>
                                <div class="quick-message m-t-lg">
    								<?php
    								global $post;
    								if ( ! empty( $post->post_content ) ) {
    									// display formatted content instead of post_content
    									while ( have_posts() ) {
    										the_post();
    										the_content();
    									}
    								} else {
    									$redirect_seconds = 3;
    									$redirect_url = '/';
    									$redirect_site = 'home page';
    									echo sprintf( '<meta http-equiv="refresh" content="%s;url=%s">', $redirect_seconds, $redirect_url );
    									echo sprintf( '<div class="text-center"><small>You\'ll be redirected to %s in %s secs. If not, click <a href="%s">here</a>.</small></div>', $redirect_site, $redirect_seconds, $redirect_url );
    								}
    								?>
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
