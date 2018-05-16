<?php if ( $checklist['overall_status'] ) {
	?>
	<div class="notice notice-success">
		<p>Looks like the everything is set up correctly and Printful integration should work as intended.</p>
	</div>
	<?php
} else {
	?>
	<div class="notice notice-error">
		<p>There are errors with your store setup that may cause the Printful integration to not work as intended!</p>
	</div>
	<?php
}
?>

<table class="wp-list-table widefat fixed striped printful-status">
	<thead>
	<tr>
		<td class="col-name">Name</td>
		<td class="col-desc">Description</td>
		<td class="col-status">Status</td>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ( $checklist['items'] as $item ) : ?>
		<tr>
			<td><?php echo esc_html( $item['name'] ); ?></td>
			<td><?php echo esc_html( $item['description'] ); ?></td>
			<td>
				<?php
				$status = 'OK';
				if ( $item['status'] == 1 ) {
					echo '<span class="pass">OK</span>';
				} else if ( $item['status'] == 0 ) {
					echo '<span class="warning">WARNING&#42;</span>';
				} else {
					echo '<span class="fail">FAIL</span>';
				}
				?>
            </td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<td class="col-name">Name</td>
		<td class="col-desc">Description</td>
		<td class="col-status">Status</td>
	</tr>
	</tfoot>
</table>

<p class="asterisk">&#42; Warnings are issued when the test was unable to come to a definite conclusion or if the result was passable, but not ideal.</p>
