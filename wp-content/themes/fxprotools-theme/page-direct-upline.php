<?php 
/*
Template Name: Direct Upline
*/
get_header(); 
?>

	<?php get_template_part('inc/templates/nav-team'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>Matrix Direct Upline</h1>
					<p>Check Below Direct Upline</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?php echo do_shortcode('[afl_eps_matrix_direct_uplines_shortcode]'); ?>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>Unilevel Direct Upline</h1>
					<p>Check Below Direct Upline</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?php echo do_shortcode('[afl_eps_unilevel_direct_uplines_shortcode]'); ?>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
