<?php
$smsPage = true;

if (isset($_GET['redirect'])) {
	$email = (object)array('ID' => $_GET['id']);
	
	if (get_post_meta($email->ID, '_user_' . get_current_user_id() . '_state')) {
		update_post_meta($email->ID, '_user_' . get_current_user_id() . '_click', true);
	}
	
	// We should just redirect instead.
	header('Location: '.$_GET['redirect']);
	die();
}

if (isset($_GET['unsub']) && $_GET['unsub']) {
	$email = (object)array('ID' => $_GET['id']);
	
	if (get_post_meta($email->ID, '_user_' . get_current_user_id() . '_state')) {
		update_post_meta($email->ID, '_user_' . get_current_user_id() . '_unsubscribe', true);
	}
	
	update_user_meta(get_current_user_id(), 'sms_unsubbed', true);
}

function email_content() {
	$email = get_post($_GET['id']);
	
	if (!get_post_meta($email->ID, '_user_' . get_current_user_id() . '_state') && !current_user_can('administrator')) {
		die();
	}
	
	$sendgrid = new FX_Sendgrid_Api();
	$stats = array();
	
	if (get_post_meta($email->ID, '_user_' . get_current_user_id() . '_state')) {
		if (get_post_meta($email->ID, '_user_' . get_current_user_id() . '_state')[0] == 'unread') {
			update_post_meta($email->ID, '_user_' . get_current_user_id() . '_state', 'read');
		}
	}
	
	if (isset($_GET['unsub'])) {
		echo '<br />You have been unsubscribed.';
	} else {
	?>
		<hr />
		<div id="emailContentArea">
			<?php echo rwmb_meta('sms_content', null, $email->ID); ?>
		</div>
		<?php
		if (get_post_meta($email->ID, '_user_' . get_current_user_id() . '_state')) {
		?>
		<div class="unsub">
		<?php
		
		if (user_unsubbed_from_sms(get_current_user_id())) {
			?>You've unsubscribed from all SMS.<?php
		} else {
			?><a class="unsubscribe-link" href="?id=<?php echo $_GET['id']; ?>&unsub=1">Click here</a> to unsubscribe from all SMS marketing.
		<?php } ?>
		</div>
		<?php
		}
		?>
	<?php
	}
	
	if (current_user_can('administrator')) {
		$stats['delivered'] = 0;
		$stats['bounce'] = 0;
		$stats['unsubscribe'] = 0;
		
		$meta = get_post_meta($email->ID);
		$recipientList = array();
		$recipientStates = array();
		
		// First retrieve all recipients.
		foreach ($meta as $key => $value) {
			preg_match('/^_user_(\d+)_state$/', $key, $matches);
			
			if (isset($matches[1])) {
				$user = get_userdata($matches[1]);
				
				$recipientList[] = (object)array(
					'id' => $matches[1],
					'name' => $user->display_name,
					'email' => $user->user_email
				);
			}
			
			preg_match('/^_user_(\d+)_(delivered|bounce|unsubscribe)/', $key, $matches);
			
			if (isset($matches[1])) {
				$recipientStates[$matches[1] . '_' . $matches[2]] = $value[0];
			}
			
			preg_match('/^_user_(\d+)_(.+)/', $key, $matches);
			
			if (isset($matches[2]) && $matches[2] != 'state') {
				$stats[$matches[2]]++;
			}
		}
		?>
		<div style="height: 20px;"></div>
		<div class="container-fluid stats">
			<div class="col-md-4">
				<div class="clearfix">
					<label>Delivered</label><br />
					<h3><?php echo number_format($stats['delivered'], 0); ?></h3>
				</div>
			</div>
			<div class="col-md-4">
				<div class="clearfix">
					<label>Bounced</label><br />
					<h3><?php echo number_format($stats['bounce'], 0); ?></h3>
				</div>
			</div>
			<div class="col-md-4">
				<div class="clearfix">
					<label>Unsubscribed</label><br />
					<h3><?php echo number_format($stats['unsubscribe'], 0); ?></h3>
				</div>
			</div>
		</div>
		<br />
		<br />
		<table class="table table-striped per-user-stats">
			<thead>
				<tr>
					<th>Recipient</th>
					<th>Delivered</th>
					<th>Bounced</th>
					<th>Unsub</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($recipientList as $item)
			{
			?>
				<tr>
					<td>
						<b><?php echo $item->name; ?></b><br />
						<small><?php echo $item->email; ?></small>
					</td>
					<td>
						<?php if (isset($recipientStates[$item->id . '_delivered']) && $recipientStates[$item->id . '_delivered']) {?>
							<i class="fa fa-check"></i>
						<?php } else { ?>
							<i class="fa fa-times"></i>
						<?php } ?>
					</td>
					<td>
						<?php if (isset($recipientStates[$item->id . '_bounce']) && $recipientStates[$item->id . '_bounce']) {?>
							<i class="fa fa-check"></i>
						<?php } else { ?>
							<i class="fa fa-times"></i>
						<?php } ?>
					</td>
					<td>
						<?php if (isset($recipientStates[$item->id . '_unsubscribe']) && $recipientStates[$item->id . '_unsubscribe']) {?>
							<i class="fa fa-check"></i>
						<?php } else { ?>
							<i class="fa fa-times"></i>
						<?php } ?>
					</td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
	<?php
	}
}

include(__DIR__ . '/inc/templates/email.php');