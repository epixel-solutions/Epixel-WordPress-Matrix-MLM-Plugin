<?php
/*
Template Name: Verify Email
*/
$verification_code = isset( $_GET['code'] ) ? $_GET['code'] : '';
?>
<?php get_header(); ?>
    <div class="fx-landing log-out-notice">
        <div class="section-w-panel">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel fx-package-item">
                            <div class="panel-body ">
                                <div class="text-center heading">
	                                <?php if( isset($_GET['action']) && $_GET['action'] == 'resend' ):
                                        resend_email_verification();?>
                                        <h1>Verification email has been sent to your email. </h1>
                                        <a href="<?php bloginfo('url');?>/dashboard/">Back to dashboard.</a>
	                                <?php elseif( verify_email_address($verification_code) ): ?>
                                        <h1>Your email has been been verified.</h1>
                                        <a href="<?php bloginfo('url');?>/dashboard/">Back to dashboard.</a>
	                                <?php else: ?>
                                        <h1>Verification has been sent to your email.</h1>
                                        <p class="m-t-md">Click <a href="<?php bloginfo('url');?>/verify-email/?action=resend">here</a> to resend.</p>
	                                <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>
