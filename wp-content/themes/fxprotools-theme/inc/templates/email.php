<?php get_header(); ?>
<?php
$unreadCount = 0;
$sentCount = 0;
$trashCount = 0;

if (!isset($smsPage) || !$smsPage) {
	$unreadCount = count_emails_for_user(array('unread', 'read'));
	$sentCount = count_sent_emails();
	$trashCount = count_emails_for_user(array('trash'));
} else {
	$unreadCount = count_sms_for_user(array('unread', 'read'));
	$sentCount = count_sent_sms();
	$trashCount = count_sms_for_user(array('trash'));
}
?>
<div class="container top-marketing-buttons">
	<div class="col-xs-12 col-sm-6 col-md-6">
		<a href="<?php bloginfo('url'); ?>/my-account/inbox" <?php if (!isset($smsPage) || !$smsPage) { ?>class="active"<?php } ?>>
			Email Marketing
		</a>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-6">
		<a href="<?php bloginfo('url'); ?>/mail/sms" <?php if (isset($smsPage) && $smsPage) { ?>class="active"<?php } ?>>
			SMS Marketing
		</a>
	</div>
</div>
<?php
if (!function_exists('page_content')) {
    function page_content() {
        global $post;
        
        ?>
		<div class="row">
			<div class="col-md-3">
				<div class="dropdown">
					<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Actions <i class="fa fa-caret-down"></i></button>
					<ul class="dropdown-menu">
						<?php if ($post->post_name == 'read') { ?>
						<li><a href="<?php bloginfo('url'); ?>/my-account/inbox?delete=<?php echo $_GET['id']; ?>">Delete</a></li>
						<?php } else { ?>
						<li><a href="#" data-email-action="mark-read">Mark as Read</a></li>
						<li><a href="#" data-email-action="delete">Delete</a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<div class="col-md-4 col-md-offset-5">
				<form action="" method="POST" id="emailSearchForm">
					<div class="input-group">
						<input type="text" class="form-control" name="search" placeholder="Search e-mail" value="<?php if (isset($_POST['search'])) echo esc_html($_POST['search']); ?>">
						<a class="input-group-addon" href="javascript:search();"><i class="fa fa-search"></i></a>
					</div>
				</form>
			</div>
			<div class="clearfix"></div>
		</div>
		<?php email_content();
    }
}

if (isset($_GET['delete'])) {
	if (get_post_meta($_GET['delete'], '_user_' . get_current_user_id() . '_state')) {
		update_post_meta($_GET['delete'], '_user_' . get_current_user_id() . '_state', 'trash');
	}
}
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="fx-header-title">
				<h1>Your Contact</h1>
				<p>Check Below for your available contact</p>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="row">
								<div class="col-xs-12 col-sm-3 col-md-3">
									<?php
									if (current_user_can('administrator')) {
									?>
									<a href="<?php bloginfo('url'); ?>/my-account/<?php if (isset($smsPage) && $smsPage) { ?>sms/compose-sms<?php } else { ?>inbox/compose<?php } ?>" title="Compose" class="btn btn-danger block ">Compose Mail</a>
									<?php
									}
									?>
									<ul class="fx-inbox-nav">
										<li <?php if ($post->post_name == 'inbox' || $post->post_name == 'sms') { ?> class="active" <?php } ?>>
											<a href="<?php bloginfo('url'); ?>/my-account/<?php if (isset($smsPage) && $smsPage) { ?>sms<?php } else { ?>inbox<?php } ?>">
												<i class="fa fa-inbox"></i> Inbox
												<span class="label label-danger pull-right" id="unreadCount"><?php echo $unreadCount; ?></span>
											</a>
										</li>
										<?php
										if (current_user_can('administrator')) {
										?>
										<li <?php if ($post->post_name == 'sent' || $post->post_name == 'sent-sms') { ?> class="active" <?php } ?>>
											<a href="<?php bloginfo('url'); ?>/my-account/<?php if (isset($smsPage) && $smsPage) { ?>sms/sent-sms/<?php } else { ?>inbox/sent/<?php } ?>">
												<i class="fa fa-envelope-o"></i> Sent
												<span class="label label-danger pull-right"><?php echo $sentCount; ?></span>
											</a>
										</li>
										<?php
										}
										?>
										<li <?php if ($post->post_name == 'trash' || $post->post_name == 'trash-sms') { ?> class="active" <?php } ?>>
											<a href="<?php bloginfo('url'); ?>/my-account/<?php if (isset($smsPage) && $smsPage) { ?>sms/trash-sms/<?php } else { ?>inbox/trash/<?php } ?>">
												<i class=" fa fa-trash-o"></i> Trash
												<span class="label label-danger pull-right"><?php echo $trashCount; ?></span>
											</a>
										</li>
									</ul>
								</div>
								<div class="col-xs-12 col-sm-9 col-md-9 xs-m-t">
								    <?php page_content(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>