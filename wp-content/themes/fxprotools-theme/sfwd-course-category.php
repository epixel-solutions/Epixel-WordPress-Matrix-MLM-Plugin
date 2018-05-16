<?php
$category_slug = get_query_var('category_slug');
$category = get_term_by('slug', $category_slug, 'ld_course_category' );
$child_categories = get_course_category_children($category->term_id);
?>


<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-products'); ?>

	<?php if( is_array( $child_categories) ): ?>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div role="tabpanel">
						<ul class="nav nav-tabs fx-tabs" role="tablist">
							<?php foreach($child_categories as $key => $category): ?>
								<li role="presentation" class="<?php echo $key == 0 ? 'active' : 'false';?>">
									<a href="#category-<?php echo $key + 1;?>" aria-controls="category-<?php echo $key + 1;?>" role="tab" data-toggle="tab"><?php echo $category->name;?></a>
								</li>
							<?php endforeach;?>
						</ul>
						<div class="tab-content">
							<?php foreach($child_categories as $key => $category): ?>
								<div role="tabpanel" class="tab-pane <?php echo $key == 0 ? 'active' : 'false';?>" id="category-<?php echo $key + 1;?>">
									<div class="fx-mid-header">
										<h1><?php echo $category->name;?></h1>
										<p>Everything Your Need To Know About <?php echo $category->name;?> Is Below:</p>
									</div>
									<ul class="fx-list-courses">
										<?php $courses = get_courses_by_category_id($category->term_id); ?>
										<?php if( $courses ) : ?>
											<?php $count = 0; foreach($courses as $post): setup_postdata($post); $count++; ?>
												<?php get_template_part('inc/templates/product/list-course'); ?>
											<?php endforeach;?>
											<?php wp_reset_query(); ?>
										<?php endif;?>
									</ul>
								</div>
							<?php endforeach;?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

<?php get_footer(); ?>
