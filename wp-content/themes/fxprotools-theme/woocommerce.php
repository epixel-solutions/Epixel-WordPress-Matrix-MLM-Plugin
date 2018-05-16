<?php get_header('shop'); ?>
	<?php if(is_singular('product')){ woocommerce_breadcrumb(); } ?>
    <div class="container">
        <?php do_action( 'woocommerce_before_main_content' ); ?>
        <?php woocommerce_content(); ?>
        <?php do_action( 'woocommerce_after_main_content' ); ?>
    </div>
<?php get_footer(); ?>
