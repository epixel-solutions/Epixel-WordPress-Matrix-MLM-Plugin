<?php 
/*
Template Name: Bonuses
*/
get_header(); 
?>

	<?php get_template_part('inc/templates/nav-wallet'); ?>

	<div class="container epx bonuses">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>Bonuses Summary</h1>
					<p>All of your commisions you earned</p>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php echo do_shortcode('[afl_bonus_summary_widgets]'); ?>
			<div class="clearfix"></div>
		</div>
		<?php echo do_shortcode('[afl_bonus_summary_and_incentives]'); ?>
		<div class="row">
			<div class="col-md-12">
				<?php $customer = false; ?>
				<?php if($customer): ?>
				<div class="panel panel-default">
					<div class="panel-body text-center p-lg">
						<h3 class="m-t-sm">Make Your Money Work For You! Discover How...</h3>
						<p>Generate an additional stream of monthly income with our volume based bonus plan.</p>
						<a href="#" class="btn btn-lg btn-warning fx-btn m-t-sm">Unlock This Feature On My Account</a>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php get_footer(); ?>