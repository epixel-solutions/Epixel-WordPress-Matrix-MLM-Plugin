<div class="printful-connect">

    <div class="printful-connect-inner">

        <h1>Connect to Printful</h1>

        <img src=" <?php echo esc_url(Printful_Base::get_asset_url() . 'images/connect.svg'); ?>" class="connect-image" alt="connect to printful">

        <?php
        if ( ! empty( $issues ) ) {
            ?>
            <p>To connect your store to Printful, fix the following errors:</p>
            <div class="printful-notice">
                <ul>
                    <?php
                    foreach ( $issues as $issue ) {
                        echo '<li>' . $issue . '</li>';
                    }
                    ?>
                </ul>
            </div>
            <?php
            $url = '#';
        } else {
            ?><p class="connect-description">You're almost done! Just 2 more steps to have your WooCommerce store connected to Printful for automatic order fulfillment.</p><?php
            $url = Printful_Base::get_printful_host() . 'dashboard/woocommerce/plugin-connect?website=' . urlencode( trailingslashit( get_home_url() ) ) . '&key=' . urlencode( $consumer_key ) . '&returnUrl=' . urlencode( get_admin_url( null,'admin.php?page=' . Printful_Admin::MENU_SLUG_DASHBOARD ) );
        }

        echo '<a href="' . esc_url($url) . '" class="button button-primary printful-connect-button ' . ( ! empty( $issues ) ? 'disabled' : '' ) . '" target="_blank">Connect</a>';
        ?>

        <img src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ) ?>" class="loader hidden" width="20px" height="20px" alt="loader"/>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                Printful_Connect.init('<?php echo admin_url( 'admin-ajax.php?action=ajax_force_check_connect_status' ); ?>');
            });
        </script>
    </div>
</div>