<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
get_header( 'shop' ); ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<?php 
			if($post->post_name == "free-shirt"){
				if( wc_customer_bought_product('',get_current_user_id(),$post->ID) ){
					echo "You already got your free T Shirt.";
				} else {
					wc_get_template_part( 'content', 'single-product' );
				}
			} else {
				wc_get_template_part( 'content', 'single-product' );
			}
		 ?>
	<?php endwhile; // end of the loop. ?>


<?php get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
