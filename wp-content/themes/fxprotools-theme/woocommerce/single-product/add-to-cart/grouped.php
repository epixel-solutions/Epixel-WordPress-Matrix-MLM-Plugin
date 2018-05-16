<?php
/**
 * Grouped product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/grouped.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.0.7
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $post;

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<?php  
/*
2920 - Business + IBO Kit Normal
2930 - Professional Normal
2927 - Signals Normal
2871 - IBO Kit
*/
$packages = array(2920,2930,2927,2871);

$subscriptions = get_user_main_subscription();
$subs_prod_id  = $subscriptions['product_id'];

$product = new WC_Product($subs_prod_id);
$upsells = $product->get_upsell_ids();
$cross_sells = $product->get_cross_sell_ids();

?>

<div class="membership-sell-list">
	<h2>Available Upgrades</h2>

	<div class="sell-list upsell-list">
		<?php 
			if($upsells){
				foreach($upsells as $upsell){
					$product = wc_get_product( $upsell );
		?>

					<div class="sell-item">
						<div class="row">
							<div class="col-md-4">
								<h5 class="sell-item-title"><?php echo $product->get_title(); ?></h5>
								<span class="sell-item-sub"><?php echo ($upsell == 2871) ? "Package Addon" : "Replacement Package" ?></span>
							</div>
							<div class="col-md-4">
								<span class="sell-item-price">$<?php echo number_format($product->get_price(), 2, '.', '');; ?> / month</span>
							</div>
							<div class="col-md-4">
								<a href="<?php echo get_option('home'); ?>/product/<?php echo $product->get_slug(); ?>" class="sell-item-link btn btn-danger">Select Options</a>
							</div>
						</div>
					</div>
		<?php 
				}
			}else{
		?>
				<div class="sell-item sell-item-empty"><h5>There are no upgrades available</h5></div>
		<?php
			}
		?>
	</div>

	<h2>Available Downgrades</h2>

	<div class="sell-list cross-list">
		<?php 
			if($cross_sells){
				foreach($cross_sells as $cross_sell){
					$product = wc_get_product( $cross_sell );
		?>

					<div class="sell-item">
						<div class="row">
							<div class="col-md-4">
								<h5 class="sell-item-title"><?php echo $product->get_title(); ?></h5>
								<span class="sell-item-sub">Replacement Package</span>
							</div>
							<div class="col-md-4">
								<span class="sell-item-price">$<?php echo number_format($product->get_price(), 2, '.', '');; ?> / month</span>
							</div>
							<div class="col-md-4">
								<a href="<?php echo get_option('home'); ?>/product/<?php echo $product->get_slug(); ?>" class="sell-item-link btn btn-danger">Select Options</a>
							</div>
						</div>
					</div>
		<?php 
				}
			}else{
				if(!in_array(11645, $cross_sells)){
		?>
				<div class="sell-item">
					<div class="row">
						<div class="col-md-4">
							<h5 class="sell-item-title">Cancel Account</h5>
							<span class="sell-item-sub">Replacement Package</span>
						</div>
						<div class="col-md-4">
							<span class="sell-item-price">$0.00 / month</span>
						</div>
						<div class="col-md-4">
							<a href="<?php echo get_option('home'); ?>/cancel-step-1/" class="sell-item-link btn btn-danger">Select Options</a>
						</div>
					</div>
				</div>
		<?php
				}else{
		?>
				<div class="sell-item sell-item-empty"><h5>There are no downgrades available</h5></div>
		<?php
				}
			}
		?>
	</div>
</div>

<form class="cart" method="post" enctype='multipart/form-data'>
	<table cellspacing="0" class="group_table">
		<tbody>
			<?php
				$quantites_required = false;
				$previous_post      = $post;

				foreach ( $grouped_products as $grouped_product ) {
					$post_object        = get_post( $grouped_product->get_id() );
					$quantites_required = $quantites_required || ( $grouped_product->is_purchasable() && ! $grouped_product->has_options() );

					setup_postdata( $post =& $post_object );
					?>
					<tr id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
						<td>
							<?php if ( ! $grouped_product->is_purchasable() || $grouped_product->has_options() ) : ?>
								<?php woocommerce_template_loop_add_to_cart(); ?>

							<?php elseif ( $grouped_product->is_sold_individually() ) : ?>
								<input type="checkbox" name="<?php echo esc_attr( 'quantity[' . $grouped_product->get_id() . ']' ); ?>" value="1" class="wc-grouped-product-add-to-cart-checkbox" />

							<?php else : ?>
								<?php
									/**
									 * @since 3.0.0.
									 */
									do_action( 'woocommerce_before_add_to_cart_quantity' );

									woocommerce_quantity_input( array(
										'input_name'  => 'quantity[' . $grouped_product->get_id() . ']',
										'input_value' => isset( $_POST['quantity'][ $grouped_product->get_id() ] ) ? wc_stock_amount( $_POST['quantity'][ $grouped_product->get_id() ] ) : 0,
										'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 0, $grouped_product ),
										'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $grouped_product->get_max_purchase_quantity(), $grouped_product ),
									) );

									/**
									 * @since 3.0.0.
									 */
									do_action( 'woocommerce_after_add_to_cart_quantity' );
								?>
							<?php endif; ?>
						</td>
						<td class="label">
							<label for="product-<?php echo $grouped_product->get_id(); ?>">
								<?php echo $grouped_product->is_visible() ? '<a href="' . esc_url( apply_filters( 'woocommerce_grouped_product_list_link', get_permalink( $grouped_product->get_id() ), $grouped_product->get_id() ) ) . '">' . $grouped_product->get_name() . '</a>' : $grouped_product->get_name(); ?>
							</label>
						</td>
						<?php do_action( 'woocommerce_grouped_product_list_before_price', $grouped_product ); ?>
						<td class="price">
							<?php
								echo $grouped_product->get_price_html();
								echo wc_get_stock_html( $grouped_product );
							?>
						</td>
					</tr>
					<?php
				}
				// Return data to original post.
				setup_postdata( $post =& $previous_post );
			?>
		</tbody>
	</table>

	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />

	<?php if ( $quantites_required ) : ?>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php endif; ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
