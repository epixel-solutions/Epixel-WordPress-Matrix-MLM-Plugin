<?php
/*
Template Name: Read
*/
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
		
		update_post_meta($email->ID, '_user_' . get_current_user_id() . '_open', true);
	}
	
	if (isset($_GET['unsub'])) {
		echo '<br />You have been unsubscribed.';
	} else {
	?>
		<h4><?php echo $email->post_title; ?></h4>
		<hr />
		<div id="emailContentArea">
			<?php echo get_post_meta( $email->ID, 'email_content' )[0]; ?>
		</div>
		<?php
		$list = get_post_meta($email->ID, 'email_list') ? get_post_meta($email->ID, 'email_list')[0] : null;
		
		if ($list && get_post_meta($email->ID, '_user_' . get_current_user_id() . '_state')) {
		?>
		<div class="unsub">
		<?php
		
		if (user_unsubbed_from_list(get_current_user_id(), $list)) {
			?>You've unsubscribed from this mail.<?php
		} else {
			?>You received this email as part of the list <b><?php
		$listDisplay = '';
		
		switch ($list) {
			case 'all':
				$listDisplay = 'All Users';
				break;
			case 'customer':
				$listDisplay = 'Customers';
				break;
			case 'distributor':
				$listDisplay = 'Distributors';
				break;
			case 'customer_distributor':
				$listDisplay = 'Customers and Distributors';
				break;
		}
		
		if (!$listDisplay) {
			preg_match('/prod-(\d+)/', $list, $matches);
			
			if (isset($matches[1])) {
				$post = get_post($matches[1]);
				$listDisplay = 'Product "' . $post->post_title . '"';
			}
		}
		
		echo $listDisplay;
		
		?></b>. <a class="unsubscribe-link" href="?id=<?php echo $_GET['id']; ?>&unsub=1">Click here</a> to unsubscribe.
		<?php } ?>
		</div>
		<?php
		}
		?>
	<?php
	}
	
	if (current_user_can('administrator')) {
		$stats['delivered'] = 0;
		$stats['open'] = 0;
		$stats['click'] = 0;
		$stats['bounce'] = 0;
		$stats['unsubscribe'] = 0;
		$stats['spamreport'] = 0;
		
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
			
			preg_match('/^_user_(\d+)_(open|click|unsubscribe)/', $key, $matches);
			
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
					<label>Sent</label><br />
					<h3><?php echo number_format($stats['delivered'], 0); ?></h3>
				</div>
			</div>
			<div class="col-md-4">
				<div class="clearfix">
					<label>Opened</label><br />
					<h3><?php echo number_format($stats['open'], 0); ?></h3>
				</div>
			</div>
			<div class="col-md-4">
				<div class="clearfix">
					<label>Clicked</label><br />
					<h3><?php echo number_format($stats['click'], 0); ?></h3>
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
			<div class="col-md-4">
				<div class="clearfix">
					<label>Complaints</label><br />
					<h3><?php echo number_format($stats['spamreport'], 0); ?></h3>
				</div>
			</div>
		</div>
		<small>Note: SendGrid stats may be delayed by up to 5 minutes.</small>
		<br />
		<br />
		<table class="table table-striped per-user-stats">
			<thead>
				<tr>
					<th>Recipient</th>
					<th>Opened</th>
					<th>Clicked</th>
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
						<?php if (isset($recipientStates[$item->id . '_open']) && $recipientStates[$item->id . '_open']) {?>
							<i class="fa fa-check"></i>
						<?php } else { ?>
							<i class="fa fa-times"></i>
						<?php } ?>
					</td>
					<td>
						<?php if (isset($recipientStates[$item->id . '_click']) && $recipientStates[$item->id . '_click']) {?>
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