<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pf_order['shipments'] = [
	[
		'carrier'         => 'Test Carrier',
		'service'         => 'Test Service',
		'tracking_number' => 'sadfsa687^f23FD',
		'tracking_url'    => 'https://example.com'
	],
	[
		'carrier'         => 'Test Carrier 2',
		'service'         => 'Test Service 2',
		'tracking_number' => 'sadfsa687^f23FDfsfs',
		'tracking_url'    => 'https://example.com'
	],
];
?>

<?php if ( ! empty( $pf_order['shipments'] ) ) : ?>
    <section class="woocommerce-shipping-details">
        <h2><?php _e( 'Shipping details', 'woocommerce' ); ?> (<?= ucfirst($pf_order['status']) ;?>)</h2>

        <table class="woocommerce-table woocommerce-table--shipping-details shop_table customer_details">
            <tr>
                <th><?php _e( 'Carrier', 'woocommerce' ); ?></th>
                <th><?php _e( 'Service', 'woocommerce' ); ?></th>
                <th><?php _e( 'Tracking Number', 'woocommerce' ); ?></th>
            </tr>
			<?php foreach ( $pf_order['shipments'] as $shipment ) : ?>
                <tr>
                    <td><?= $shipment['carrier']; ?></td>
                    <td><?= $shipment['service']; ?></td>
                    <td>
                        <a href="<?= $shipment['tracking_url']; ?>" target="_blank"><?= $shipment['tracking_number']; ?></a>
                    </td>
                </tr>
			<?php endforeach; ?>
        </table>
    </section>

<?php endif; ?>
