<?php 
/*
Template Name: Renewal
*/
$subscription = get_user_main_subscription();
$renewal_order_link = get_renewal_order_checkout_link( $subscription['subscription'] );
?>
<?php get_header(); ?>
<div class="fx-access-denied">
    <div class="fx-access-denied-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="fx-header-title">
                        <h1>It Looks Like You Do Not Have Access</h1>
                        <p>It looks like in order for you to see this page / content you will need to activate or upgrade your account.<br/>
                            You just need to pick a package & we can unlock the page for you instantly!</p>
                    </div>
                </div>
            </div>
            <div class="row m-t-md m-b-lg">
				<?php
				$membership_product_id = Woocommerce_Settings::MEMBERSHIP_PRODUCTS_ID;
				$membership_product = wc_get_product( $membership_product_id );
				$product_ids = $membership_product->get_children();
				$product_count = count( $product_ids );
				$col_denom = ( $product_count >= 1 && $product_count <= 4 ) ? 12 / $product_count : 4;

				foreach ( $product_ids as $product_name => $product_id ) :
                    //get regular price variation
					$product = wc_get_product( $product_id );
                    $variation = wc_get_product( $product->get_children()[1] );

					if ( isset( $product ) ) {
						$product_name = $product->get_name();
						$product_price = WC_Subscriptions_Product::get_sign_up_fee( $product );
						$product_price = $product_price == 0 ? $variation->get_regular_price() : $product_price;
                        $subscription_price = WC_Subscriptions_Product::get_price( $variation );
						$attributes = $product->get_attribute( 'inclusions' );
						$attribute_list = [];
						if ( ! empty( $attributes ) ) {
							$attribute_list = array_map( 'trim', explode( "|", $attributes ) );
						}
                        $checkout_url = $product_id == $subscription['product_id'] ? $renewal_order_link : $product->get_attribute('regular-checkout-link');
					}
					?>
                    <div class="col-md-<?= $col_denom; ?>">
                        <div class="panel fx-package-item active">
                            <span class="sash">UPGRADE</span>
                            <div class="panel-body">
                                <div class="heading">
                                    <h3 class="text-normal">Forex & Binary Options</h3>
                                    <h1 class="m-t-none"><?php echo $product_name; ?> Package</h1>
                                </div>
                                <div class="text-center">
                                    <h2 class="m-b-md"><?php echo wc_price( $subscription_price ); ?> / month</h2>

									<?php if ( ! empty( $attribute_list ) ) : ?>
                                        <ul class="list-checklist fa-ul m-t-md m-b-md text-left">
											<?php foreach ( $attribute_list as $attribute ) : ?>
                                                <li><i class="fa fa-check fa-li text-green"></i>
                                                    <p> <?= $attribute; ?> </p></li>
											<?php endforeach; ?>
                                        </ul>
									<?php endif; ?>

                                    <a href="<?php echo $checkout_url; ?>" class="btn btn-danger block btn-lg m-b-md btn-lg-w-text">
                                        Get Instant Access Now!
                                        <span>Training + Forex &amp; Binary Auto Trader</span>
                                    </a>
                                    <p class="text-bold">Downgrade / or Cancel At Anytime!</p>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
