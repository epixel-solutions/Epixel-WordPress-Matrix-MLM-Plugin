<?php
function email_content() {
	global $emails;
	global $smsPage;
	
	// Filter emails.
	if (isset($_POST['search'])) {
		$emails = array_filter($emails, function ($val) {
			$search = $_POST['search'];
			
			return strpos(strtolower($val->post_title), strtolower($search)) !== FALSE;
		});
	}
	
	?>
	<div class="table-responsive">
		<table class="table table-bordered table-hover fx-table-inbox with-padding no-border-l-r m-t-sm">
			<thead>
				<th class="text-center"><input type="checkbox" id="selectAll"></th>
				<th class="small" style="width: 75%;"><?php if (isset($smsPage) && $smsPage) { ?>Content<?php } else { ?>Subject<?php } ?></th>
				<th class="small text-center">Date</th>
			</thead>
			<tbody id="mailContainer">
				<?php
				if (count($emails) > 0) {
					foreach ($emails as $email) {
				?>
				<tr class="<?php echo get_post_meta($email->ID, '_user_' . get_current_user_id() . '_state')[0]; ?>">
					<td class="text-center"><input type="checkbox" class="email-select" data-id="<?php echo $email->ID; ?>" /></td>
					<td><a href="<?php bloginfo('url'); ?>/my-account/<?php if (isset($smsPage) && $smsPage) { ?>sms/read-sms<?php } else { ?>inbox/read<?php } ?>/?id=<?php echo $email->ID; ?>"><?php
						if (isset($smsPage) && $smsPage) {
							echo rwmb_meta('sms_content', null, $email->ID);
						} else {
							echo $email->post_title;
						}
					?></a></td>
					<td class="text-center"><?php echo date_i18n( 'm/d/Y', strtotime($email->post_date) ); ?></td>
				</tr>
				<?php }
				} else { ?>
				<tr>
					<td colspan="3">No <?php if (isset($smsPage) && $smsPage) { ?>SMS<?php } else { ?>emails<?php } ?> found.</td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
}

include('email.php');
?>