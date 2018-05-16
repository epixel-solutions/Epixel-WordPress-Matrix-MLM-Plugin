<?php
	$subscription = isset( $_GET['subs_id'] ) ? wcs_get_subscription( $_GET['subs_id'] ) : 0;
	$user_login = isset( $_GET['id'] ) ? get_the_author_meta('user_login', $_GET['id'] ) : '';
	$cancellation_url = '';
	if( $subscription ){
		$cancellation_url = wcs_get_users_change_status_link( $subscription->get_id(), 'cancelled', $subscription->get_status() );
	}
	
?>
<?php if( $subscription ) :?>
	<div class="modal fade" id="cancellation-modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="cancellation-modal-label">ARE YOU SURE?</h4>
				</div>
				<div class="modal-body">
					<p><strong>IMPORTANT:</strong> If you cancel your account, please note that your username (<?php echo $user_login; ?>) will be made available for someone else; any progress and access to pages you've created will be disabled; optins and leads will not be collected; and videos will not display if you added your own.</p>
					<div class="title-loader">
						<div class="spinner">
							<div class="rect1"></div>
							<div class="rect2"></div>
							<div class="rect3"></div>
							<div class="rect4"></div>
							<div class="rect5"></div>
						</div>
						<h4>Removing Access to Training...</h4>
					</div>
					<p>4 Binary Options Courses<br>
					12 Training Lessons<br>
					4 Forex Courses<br>
					14 Forex Training Lessons</p>
					<div class="title-loader">
						<div class="spinner">
						  <div class="rect1"></div>
						  <div class="rect2"></div>
						  <div class="rect3"></div>
						  <div class="rect4"></div>
						  <div class="rect5"></div>
						</div>
						<h4>Removing Access to Software...</h4>
					</div>
					<p>1 Forex Scanners<br>
					1 Binary Scanner<br>
					All Trading Tools</p>
					<div class="title-loader">
						<div class="spinner">
						  <div class="rect1"></div>
						  <div class="rect2"></div>
						  <div class="rect3"></div>
						  <div class="rect4"></div>
						  <div class="rect5"></div>
						</div>
						<h4>Removing Access to Webinars / Coaching...</h4>
					</div>
					<p>30 Forex Webinars<br>
					20 Binary Webinars</p>
					<div class="title-loader">
						<div class="spinner">
						  <div class="rect1"></div>
						  <div class="rect2"></div>
						  <div class="rect3"></div>
						  <div class="rect4"></div>
						  <div class="rect5"></div>
						</div>
						<h4>Deleting Contacts...</h4>
					</div>
					<div class="title-loader">
						<div class="spinner">
						  <div class="rect1"></div>
						  <div class="rect2"></div>
						  <div class="rect3"></div>
						  <div class="rect4"></div>
						  <div class="rect5"></div>
						</div>
						<h4>Removing Access To Pages...</h4>
					</div>
					<div class="row">
						<div class="col-md-6">
							<h4>Or...Finalize Account Cancellation:</h4>
						</div>
						<div class="col-md-6">
							<div class="btn-2-holder">
								<button type="button" class="btn btn-success" data-dismiss="modal">NO</button>
								<a href="<?php echo $cancellation_url;?>" class="btn btn-success">YES</a>
							</div>
						</div>
					</div>
					<p><strong>IMPORTANT:</strong> If you cancel your account, please note that your username (USER_NAME) will be made available for someone else; any progress and access to pages you've created will be disabled; optins and leads will not be collected; and videos will not display if you added your own.</p>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
