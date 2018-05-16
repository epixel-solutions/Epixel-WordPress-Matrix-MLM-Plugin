
<h2>Printful product orders</h2>

<?php if ( ! empty( $orders ) && $orders['count'] > 0 ): ?>

    <table class="wp-list-table widefat fixed striped printful-latest-orders">
        <thead>
            <tr>
                <th class="col-order">Order</th>
                <th class="col-date">Date</th>
                <th class="col-from">From</th>
                <th class="col-status">Status</th>
                <th class="col-total">Total</th>
                <th class="col-actions">Actions</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ( $orders['results'] as $order ) : ?>

                <tr>
                    <td>
                        <?php
                        if ( $order['external_id'] ) {
	                        echo '<a href="' . get_edit_post_link( $order['external_id'] ) . '">';
	                        echo '#' . esc_html( $order['external_id'] );
	                        echo '</a>';
                        } else {
	                        echo '#' . esc_html( $order['id'] );
                        }
                        ?>
                    </td>
                    <td>
	                    <?php echo esc_html( date('Y-m-d', $order['created']) ); ?>
                    </td>
                    <td>
	                    <?php echo esc_html( $order['recipient']['name'] ); ?>
                    </td>
                    <td>
	                    <?php echo esc_html( ucfirst($order['status']) ); ?>
                    </td>
                    <td>
	                    $<?php echo esc_html( $order['costs']['total'] ); ?>
                    </td>
                    <td>
                        <a href="<?php echo esc_url(Printful_Base::get_printful_host()); ?>dashboard?order_id=<?php echo esc_attr($order['id']); ?>" target="_blank">Open in Printful</a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
        <tfoot>
            <tr>
                <th class="col-order">Order</th>
                <th class="col-date">Date</th>
                <th class="col-from">From</th>
                <th class="col-status">Status</th>
                <th class="col-total">Total</th>
                <th class="col-actions">Actions</th>
            </tr>
        </tfoot>
    </table>

<?php else: ?>
    <div class="printful-latest-orders">
        <p>Once your store gets some Printful product orders, they will be shown here!</p>
    </div>
<?php endif; ?>