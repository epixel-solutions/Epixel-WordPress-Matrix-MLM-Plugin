<?php
/*
Template Name: Scanner
*/
	$product_id = 47; //professional package
	$product = wc_get_product( $product_id );
	$courses = get_courses_by_product_id( $product_id  ); 
?>
<?php get_header(); ?>

	<?php if ( is_user_fx_distributor() ||  wcs_user_has_subscription( '', $product_id, 'active')  || current_user_can('administrator') ) : ?>
		<?php get_template_part('inc/templates/nav-products'); ?>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="fx-list-courses">
						<?php if( $courses ) : ?>
							<?php $count = 0; foreach($courses as $post): setup_postdata($post); $count++; ?>
								<?php get_template_part('inc/templates/product/list-course'); ?>
							<?php endforeach;?>
							<?php wp_reset_query(); ?>
						<?php endif;?>
					</ul>
					<br/>
					<div class="fx-header-title">
						<h1><?php the_title();?></h1>
						<p><?php echo rwmb_meta('subtitle');?></p>
					</div>
				</div>
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php else: ?>
		<?php get_template_part('inc/templates/no-access'); ?>
	<?php endif; ?>


<?php get_footer(); ?>