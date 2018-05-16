<?php $exclude_pages = array('dashboard'); ?>
<?php if(!is_page($exclude_pages)): ?>
<div class="section-nav">
	<div class="fx-top-nav one">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-md-6">
					<ul class="fx-nav w-border">
						<li><a href="mailto:support@copyprofitsuccess.com">support@copyprofitsuccess.com</a></li>
						<li><a href="tel:+1 800 781 0187">+1 800 781 0187</a></li>
						<li>Mon-Fri 10am-10pm EST</li>
					</ul>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-6">
					<ul class="fx-nav w-flag">
						<li><a href="#" class="btn-flag">English</a></li>
						<li class="fx-nav-btn"><a href="<?php echo get_option('home'); ?>/login">Members Login</a></li>
					</ul>
				</div>		
			</div>
		</div>
	</div>
	<div class="fx-top-nav two">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<?php if ( function_exists( 'the_custom_logo' ) ) {
					    the_custom_logo();
					} else { ?>
					<a href="<?php bloginfo('url'); ?>" class="logo"><?php echo get_bloginfo('name'); ?></a>
					<?php } ?>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<ul class="fx-nav">
						<li><a href="<?php bloginfo('url'); ?>/#trial-products" class="scroll-to">Become a Customer</a></li>
						<li><a href="<?php bloginfo('url'); ?>/#trial-distributor" class="scroll-to">Become a Distributor</a></li>
					</ul>
				</div>		
			</div>
		</div>
	</div>
</div>
<?php endif; ?>