<?php 
/*
Template Name: Wallet Payment Method
*/
get_header(); 
?>

	<?php get_template_part('inc/templates/nav-wallet'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="fx-list-courses">
					<li class="list-item">
						<div class="left">
							<div class="box">
								<span class="sash">Active</span>
								<span class="number">01</span>
							</div>
						</div>
						<div class="right">
							<div class="row">
								<div class="col-md-12">
									<span class="title">Setup & Understanding Your E-Wallet</span>
								</div>
								<div class="col-md-10">
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
									quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
									consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
									cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
									proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
								</div>
								<div class="col-md-2">
									<a href="<?php bloginfo('url');?>/product/course" class="btn btn-default block">Learn More</a>
								</div>
								<div class="col-md-12">
									<div class="progress">
									 	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
											25%
									 	</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</li>
				</ul>
				<br/>
				<div class="fx-header-title">
					<h1>How Can We Pay You?</h1>
					<p>Select Payment Method</p>
				</div>
				<div class="panel panel-default epx wallet-form">
					<div class="panel-body">
						<?php echo do_shortcode('[select_payment_method_form]'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
