<?php
/**
 * Activity Pagination
 */
?>

<div class="activity-item pagination">
	<?php if ( 1 < $paged ) : ?>
		<a href="#" class="prev" data-page="<?php echo $paged - 1; ?>"><?php echo _x('&laquo; Previous', 'activity widget pagnation previous page link', 'ld_propanel'); ?></a>
	<?php endif; ?>

	<?php if ( $paged != $activities['pager']['total_pages'] ) : ?>
		<a href="#" class="next" data-page="<?php echo $paged + 1; ?>"><?php echo _x('Next &raquo;', 'activity widget pagnation next page link', 'ld_propanel'); ?></a>
	<?php endif; ?>

	<div class="clearfix"></div>
</div>