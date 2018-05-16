<?php 
/*
Template Name: Expert Training
*/
get_header();
?>
	
	<?php
		$category = get_term_by('slug', 'expert-training', 'ld_course_category' );
		$courses = get_courses_by_category_id($category->term_id);
	?>

	<?php get_template_part('inc/templates/nav-products'); ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="fx-header-title">
					<h1><?php the_title();?></h1>
					<p><?php echo rwmb_meta('subtitle');?></p>
				</div>
				<br/>
				<?php if( $courses ) : ?>
				<ul class="fx-list-courses">
					<?php $count = 0; foreach($courses as $post): setup_postdata($post); $count++; ?>
						<?php get_template_part('inc/templates/product/list-course'); ?>
					<?php endforeach;?>
					<?php wp_reset_query(); ?>
				</ul>
				<?php else: ?>
				<div class="panel">
					<div class="panel-body">
						<h2 class="m-t-none">Oops!</h2>
						<p>There are no available courses at this moment</p>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php get_footer(); ?>