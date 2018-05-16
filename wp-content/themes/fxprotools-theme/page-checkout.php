<?php 
/*
Template Name: Checkout
*/
get_header(); 
?>
<?php if ( !is_user_logged_in() ): ?>
<nav class="navbar fx-navbar-main" role="navigation">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-3">
				<div class="navbar-header fx-navbar-header">
					<a class="navbar-brand" href="#">
						<img src="<?php bloginfo('template_url'); ?>/assets/img/logo.png" class="img-responsive">
						<br>
					</a>
				</div>
				
			</div>
			<div class="xs-nav-side col-sm-9 col-md-9">
				<div class="row">
					<div class="col-xs-12 col-sm-8 col-md-8">
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>
<?php endif; ?>
	<?php if( is_user_logged_in() ) : ?>	
		<?php get_template_part('inc/templates/nav-import-user'); ?>
	<?php endif;?>
	<div class="container">
		<?php if( is_user_logged_in() ) :?>
			<?php $password_changed = get_user_meta( get_current_user_id(), '_imported_user_password_changed', true ); ?>
		<?php endif; ?>
		<?php if( is_user_logged_in() && $password_changed ) : ?>	
			<div class="heading m-t-n-md text-center">	
				<h1>Almost There! Add Your Billing Information</h1>
	        	<h4><span class="label-red">Step 1:</span> Since this is the first time accessing your account you need to add your billing information...</h4>
	        	<br>
			</div>
			<style>
				.woocommerce-message{display:none!important;}
			</style>
		<?php endif;?>
		

		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</div>
<?php get_footer(); ?>