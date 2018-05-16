<div class="printful-stats">
	<div class="printful-stats-item">
		<h4>$<?php echo esc_html($stats['orders_today']['total']); ?></h4>
        <b>
            <?php
            echo esc_html($stats['orders_today']['orders']);
            echo ' ' . _n('ORDER', 'ORDERS', $stats['orders_today']['orders']);
            ?>
        </b>
          today
	</div>
	<div class="printful-stats-item">
        <h4>
            $<?php echo esc_html($stats['orders_last_7_days']['total']); ?>
	        <?php echo '<span class="dashicons dashicons-arrow-' . esc_attr($stats['orders_last_7_days']['trend']) .'-alt"></span>'; ?>
        </h4>
        <b>
            <?php
            echo esc_html($stats['orders_last_7_days']['orders']);
            echo ' ' . _n('ORDER', 'ORDERS', $stats['orders_last_7_days']['orders']);
            ?>
        </b>
        last 7 days
	</div>
	<div class="printful-stats-item">
        <h4>
            $<?php echo esc_html($stats['orders_last_28_days']['total']); ?>
	        <?php echo '<span class="dashicons dashicons-arrow-' . esc_attr($stats['orders_last_28_days']['trend']) .'-alt"></span>'; ?>
        </h4>
        <b>
	        <?php
	        echo esc_html($stats['orders_last_28_days']['orders']);
	        echo ' ' . _n('ORDER', 'ORDERS', $stats['orders_last_28_days']['orders']);
	        ?>
        </b> last 28 days
	</div>
	<div class="printful-stats-item">
        <h4>
            $<?php echo esc_attr($stats['profit_last_28_days']); ?>
	        <?php echo '<span class="dashicons dashicons-arrow-' . esc_attr($stats['profit_trend_last_28_days']) .'-alt"></span>'; ?>
        </h4>
        <b>PROFIT</b> last 28 days
	</div>
</div>