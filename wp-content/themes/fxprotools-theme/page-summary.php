<?php 
/*
Template Name: Summary
*/
get_header(); 
?>

	<?php get_template_part('inc/templates/nav-wallet'); ?>

	<div class="container epx">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>E-Wallet Summary</h1>
					<p>All of your commisions you earned</p>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php echo do_shortcode('[afl_ewallet_all_earnings_summary_blocks_shortcode]'); ?>
		</div>
		<?php echo do_shortcode('[afl_ewallet_summary]'); ?>
		<div class="row">
			<div class="col-md-12">
				<?php $customer = false; ?>
				<?php if($customer): ?>
				<div class="panel panel-default">
					<div class="panel-body text-center p-lg">
						<h3 class="m-t-sm">Earn Commissions For Your Referrals</h3>
						<p>Go beyond just a free account & build a business that can generate an additional stream of monthly income with our volume based bonus plan.</p>
						<a href="#" class="btn btn-lg btn-warning fx-btn m-t-sm">Unlock This Feature On My Account</a>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php get_footer(); ?>