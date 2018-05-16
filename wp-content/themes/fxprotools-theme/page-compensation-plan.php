<?php 
/*
Template Name: Compensation Plan
*/
get_header(); 
?>

	<?php get_template_part('inc/templates/nav-dashboard'); ?>

	<div class="container page-compensation-plan">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>Our Compensation Plan Explained</h1>
					<p><span class="label-red">Step 4:</span> Understanding How to Qualify For Compensation & Bonuses</p>
				</div>
			</div>
			<div class="col-md-8">
				<?php
					// Metabox Page Template Option - Video Embed
					echo get_mb_pto1( 'video_embed', 'pto1' );
				?>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4">
				<div class="fx-board checklist">
					<div class="fx-board-header w-text">
                        <span class="title">Compensation Plan Documents</span>
                        <span class="sub">How to Build A Business with <?php echo get_bloginfo('name'); ?></span>
					</div>
					<ul class="fx-compensation-documents">
						<li>
							<h4 class="inline-block">PDF Document</h4>
							<a href="<?php bloginfo('template_directory');?>/assets/doc/CopyProfitSuccess-Compensation-Presentation.pdf" class="btn btn-danger btn-lg fx-btn inline-block pull-right">Download</a>
							<div class="clearfix"></div>
						</li>
						<li>
							<h4 class="inline-block">Your eWallet</h4>
							<a href="<?php bloginfo('url');?>/wallet/" class="btn btn-danger btn-lg fx-btn inline-block pull-right">Setup</a>
							<div class="clearfix"></div>
						</li>
						<li>
							<h4 class="inline-block">Your Matrix</h4>
							<a href="<?php bloginfo('url');?>/team/matrix-tree/" class="btn btn-danger btn-lg fx-btn inline-block pull-right">View</a>
							<div class="clearfix"></div>
						</li>
						<li>
							<h4 class="inline-block">Bonuses</h4>
							<a href="<?php bloginfo('url');?>/wallet/bonuses" class="btn btn-danger btn-lg fx-btn inline-block pull-right">View</a>
							<div class="clearfix"></div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
