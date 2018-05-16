<?php
/*
Template Name: My Account
*/
$checklist = get_user_checklist();
set_query_var('acc_id', get_current_user_id());
if(isset($_POST['user_login'])){
	if ( session_status() == PHP_SESSION_NONE ) {
		session_start();
	}
	$_SESSION["sec_password"] = "^%fxpro%$#@56&";
	$_SESSION["sec_user_id"]  = get_query_var('acc_id');
	$_SESSION["sec_redir"]  = get_option('home') . $_SERVER['REQUEST_URI'];
	$_SESSION["sec_login"] = 1;
}

if (isset($_GET['test'])) {
    if (class_exists('CPS_Printful')) {
        $pf = new CPS_Printful();
        var_dump($pf->get_order(29087));
    } else {
        var_dump('Class Not Found');
    }
}
?>
<?php
if( $_SERVER['REQUEST_METHOD'] === 'POST'){
	$has_phone_number = false;
	foreach($_POST as $key => $value){
		if($key == "user_email_subs" || $key == "user_sms_subs")
		{
			if($value == "on"){
				update_user_meta( get_query_var('acc_id'), $key,  "yes" );
			}
			else{
				update_user_meta( get_query_var('acc_id'), $key,  "no" );
			}
		}
		elseif($key == "user_login" || $key == 'user_email'){
			$wpdb->update($wpdb->users, array($key => $value), array('ID' => get_query_var('acc_id')));
		}
		else{
			update_user_meta( get_query_var('acc_id'), $key,  $value );
		}

		if ( $key == 'phone_number' && ! empty( $value ) ) {
			$has_phone_number = true;
		}
	}
	//for onboard checklist
	if( !$checklist['verified_profile'] && $has_phone_number){
		pass_onboarding_checklist('verified_profile');
	}
	// update Intercom account of the user
    $intercom = new CPS_Intercom();
	$intercom->intercom_update_user(get_query_var('acc_id'));
	wp_redirect( home_url() . '/autologin?user_id=' . get_query_var('acc_id') );
}

get_header();
?>

<?php get_template_part('inc/templates/nav-marketing'); ?>

<div class="container woocommerce">
	<div class="row">
		<div class="col-md-12">
			<div class="fx-header-title">
				<h1>Your Contact</h1>
				<p>Check Below for your available contact</p>
			</div>
			<div class="panel panel-default fx-contact-panel">
				<div class="panel-body">
					<div class="media">
						<div class="media-left">
							<img src="<?php echo get_avatar_url(get_current_user_id()); ?>" class="media-object">
						</div>
						<div class="media-body">
							<div class="info">
								<h4 class="media-heading text-normal">
									<?php
										if(get_the_author_meta('first_name', get_current_user_id())){
											echo get_the_author_meta('first_name', get_current_user_id()) . ' ' . get_the_author_meta('last_name', get_current_user_id());
										}else{
											echo get_the_author_meta('user_login', get_current_user_id());
										}
									?>
								</h4>
								<ul class="info-list">
									<li><i class="fa fa-envelope-o"></i> <?php echo get_the_author_meta('email', get_current_user_id()); ?></li>
									<li><i class="fa fa-mobile"></i> <?php echo get_the_author_meta('billing_phone', get_current_user_id()); ?></li>
									<li><i class="fa fa-home"></i> <?php echo get_the_author_meta('billing_city', get_current_user_id()); ?>, <?php echo get_the_author_meta('billing_state', get_current_user_id()); ?></li>
								</ul>
							</div>
							<div class="action">
								<div>
									<i class="fa fa-inbox block"></i>
									<a href="#">Send Message</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="fx-tabs-vertical marketing-contacts">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#a" data-toggle="tab"> <i class="fa fa-user visible-xs"></i> <span>Your Information</span></a></li>
							<li><a href="#b" data-toggle="tab"> <i class="fa fa-address-card-o visible-xs"></i> <span>Edit Contact</span></a></li>
							<li><a href="#b-2" data-toggle="tab"> <i class="fa fa-address-card-o visible-xs"></i> <span>Billing</span></a></li>
							<li><a href="#c" data-toggle="tab"> <i class="fa fa-credit-card visible-xs"></i> <span>Purchases</span></a></li>
							<li><a href="#d" data-toggle="tab"> <i class="fa fa-star-o visible-xs"></i> <span>Memberships</span></a></li>
							<li class="hide-on-customer"><a href="#f" data-toggle="tab"> <i class="fa fa-users visible-xs"></i> <span>Your Matrix</span></a></li>
							<li><a href="#g" data-toggle="tab"> <i class="fa fa-list visible-xs"></i> <span>Recent Activity</span></a></li>
							<li><a href="#h" data-toggle="tab"> <i class="fa fa-gift visible-xs"></i> <span>Your Sponsor</span></a></li>
							<li><a href="<?php echo wp_logout_url('/login/'); ?>"><i class="fa fa-sign-out visible-xs"></i> <span>Logout</span></a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="a">
								<form action="<?php echo get_the_permalink(); ?>" method="POST" class="<?php echo ( isset($_GET['action']) && $_GET['action'] == 'edit') ? 'form-edit' : ''; ?>">
									<div class="row">
										<div class="col-xs-12 col-sm-6 col-md-6 m-b-lg">
											<p class="text-label">General Information</p>
											<ul class="list-info list-info-fields">
												<li><span>First Name:</span> <?php echo get_the_author_meta('first_name', get_current_user_id()) ?></li>
												<li><span>Last Name:</span> <?php echo get_the_author_meta('last_name', get_current_user_id()); ?></li>
												<li><span>Website:</span> <?php echo get_the_author_meta('website', get_current_user_id()) ?></li>
												<li><span>Facebook:</span> <?php echo get_the_author_meta('facebook', get_current_user_id()); ?></li>
												<li><span>Twitter:</span> <?php echo get_the_author_meta('twitter', get_current_user_id()); ?></li>
												<li><span>Google Plus:</span> <?php echo get_the_author_meta('googleplus', get_current_user_id()); ?></li>
											</ul>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6 m-b-lg">
											<p class="text-label">Account Information</p>
											<ul class="list-info list-info-fields">
												<li><span>Affiliate ID:</span> <?php echo affwp_get_affiliate_id( get_current_user_id() ) ?></li>
												<li><span>Username:</span>
												<?php
													if(strpos(get_the_author_meta('user_login', get_current_user_id()), ' ') > 0){
														echo '{please add your username}';
													}else{
														echo get_the_author_meta('user_login', get_current_user_id());
													}
												?>
												</li>
												<li><span>Email:</span> <?php echo get_the_author_meta('user_email', get_current_user_id()) ?></li>
												<li><span>Phone Number:</span> <?php echo get_the_author_meta('phone_number', get_current_user_id()) ?></li>
												<li><span>SMS/Text Messaging:</span> <?php
													$sub = get_the_author_meta('user_sms_subs', get_current_user_id());

													if (strlen($sub) == 0) {
														echo 'no';
													} else {
														echo $sub;
													}
												?></li>
												<li><span>Email Updates:</span> <?php
													$sub = get_the_author_meta('user_email_subs', get_current_user_id());

													if (strlen($sub) == 0) {
														echo 'no';
													} else {
														echo $sub;
													}
												?></li>
											</ul>
										</div>
										<div class="clearfix"></div>
										<div class="col-xs-12 col-sm-6 col-md-6">
											<p class="text-label">Billing Information</p>
											<ul class="list-info list-info-fields">
												<li><span>Business Name:</span> <?php echo get_the_author_meta('billing_company', get_current_user_id()) ?></li>
												<li><span>House # & Street Name:</span> <?php echo get_the_author_meta('billing_address_1', get_current_user_id()) ?></li>
												<li><span>Apt.,suite,unit,etc.:</span> <?php echo get_the_author_meta('billing_address_2', get_current_user_id()) ?></li>
												<li><span>City:</span> <?php echo get_the_author_meta('billing_city', get_current_user_id()) ?></li>
												<li><span>State:</span> <?php echo get_the_author_meta('billing_state', get_current_user_id()) ?></li>
												<li><span>Zip Code:</span> <?php echo get_the_author_meta('billing_postcode', get_current_user_id()) ?></li>
											</ul>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6 xs-m-t">
											<p class="text-label">Shipping Information</p>
											<ul class="list-info list-info-fields">
												<li><span>Business Name:</span> <?php echo get_the_author_meta('shipping_company', get_current_user_id()) ?></li>
												<li><span>House # & Street Name:</span> <?php echo get_the_author_meta('shipping_address_1', get_current_user_id()) ?></li>
												<li><span>Apt.,suite,unit,etc.:</span> <?php echo get_the_author_meta('shipping_address_2', get_current_user_id()) ?></li>
												<li><span>City:</span> <?php echo get_the_author_meta('shipping_city', get_current_user_id()) ?></li>
												<li><span>State:</span> <?php echo get_the_author_meta('shipping_state', get_current_user_id()) ?></li>
												<li><span>Zip Code:</span> <?php echo get_the_author_meta('shipping_postcode', get_current_user_id()) ?></li>
											</ul>
										</div>
									</div>
								</form>
							</div>
							<div class="tab-pane fade" id="b">
								<?php get_template_part('inc/templates/my-account/form-edit'); ?>
								<?php get_template_part('inc/templates/my-account/payment-methods'); ?>
							</div>
							<div class="tab-pane fade" id="b-2">
								<?php get_template_part('inc/templates/my-account/payment-methods'); ?>
							</div>
							<div class="tab-pane fade" id="c">
								<?php get_template_part('inc/templates/my-account/purchases'); ?>
							</div>
							<div class="tab-pane fade" id="d">
								<?php get_template_part('inc/templates/my-account/membership'); ?>
							</div>
							<div class="tab-pane fade epx hide-on-customer" id="f">
								<p class="text-bold">Matrix Section</p>
								<?php get_template_part('inc/templates/my-account/matrix-section'); ?>
							</div>
							<div class="tab-pane fade" id="g">
								<p class="text-bold">Recent Activity</p>
								<?php get_template_part('inc/templates/my-account/recent-activity'); ?>
							</div>
							<div class="tab-pane fade" id="h">
								<p class="text-bold">Your Sponsor</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_template_part('inc/templates/my-account/modal'); ?>

<div aria-hidden="true" aria-labelledby="modalComposeLabel" role="dialog" tabindex="-1" id="modalCompose" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title">Compose</h4>
			</div>
			<div class="modal-body">
				<form role="form" class="form-horizontal">
					<div class="form-group">
						<label class="col-md-2 control-label">To</label>
						<div class="col-md-10">
							<input type="text" placeholder="" id="inputEmail1" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Cc / Bcc</label>
						<div class="col-md-10">
						<input type="text" placeholder="" id="cc" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Subject</label>
						<div class="col-md-10">
						<input type="text" placeholder="" id="inputPassword1" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Message</label>
						<div class="col-md-10">
						<textarea rows="10" cols="30" class="form-control" id="" name=""></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12 text-right">
							<button class="btn btn-send" type="submit">Send</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>

<script type="text/javascript">
	$(document).ready(function(){
		$('.view-purchase-details').click(function(e){
			e.preventDefault();
			var html = $('#'+$(this).attr('data-target'))[0].outerHTML;
			$('#view-purchase-details .purchase-details-info').html('');
			$('#view-purchase-details .purchase-details-info').prepend(html);
			$('#table-purchases').hide();
			$('#view-purchase-details').fadeIn();
		});
		$('#close-purchase-details').click(function(e){
			e.preventDefault();
			$('#view-purchase-details').hide();
			$('#table-purchases').fadeIn();
		});
		//check username
		var textInput = document.getElementById('user_login');
		var timeout = null;
		textInput.onkeyup = function (e) {
			clearTimeout(timeout);
			$('.form-edit button[type="submit"]').attr('disabled','disabled');

			timeout = setTimeout(function () {
				var username = $('#user_login').val();
				var id = $('#user_login').attr('id');
		        $.ajax({
			        url: "<?php echo get_option('home'); ?>/wp-admin/admin-ajax.php ?>",
			        data: {
			            'action':'check_valid_username',
			            'new_username' : username
			        },
			        beforeSend: function(){
			        	$('#' + 'validation-'+ id).remove();
			        	$('#user_login').parent().after('<li id="validation-'+ id +'" class="validation-field"></li>');
			        	$('#' + 'validation-'+ id).append('<span class="alert alert-warning">Verifying your new username...</span>');
			        },
			        success:function(data) {
			            if(data == "0"){
			            	$('#' + 'validation-'+ id + ' .alert').remove();
			            	$('#' + 'validation-'+ id).append('<span class="alert alert-danger"><i class="fa fa-times"></i> username "'+ username +'" is already in use. Please enter a different Username. (You might try adding a number to the end of the name entered.)</span>');
			            	$('.form-edit button[type="submit"]').attr('disabled','disabled');
			            }else if(data == "2"){
			            	$('#' + 'validation-'+ id + ' .alert').remove();
			            	$('#' + 'validation-'+ id).append('<span class="alert alert-danger"><i class="fa fa-times"></i> Your Username must be between 3 and 30 characters long. Your Username cannot include spaces or characters other than letters, numbers, and the following punctuation: !#%&()*+,-./:; =?@[]^_`{}~.</span>');
			            	$('.form-edit button[type="submit"]').attr('disabled','disabled');
			            }else{
			            	$('#' + 'validation-'+ id + ' .alert').remove();
			            	$('#' + 'validation-'+ id).append('<span class="alert alert-success"><i class="fa fa-check"></i> username "'+ username +'" is available</span>');
			            	$('.form-edit button[type="submit"]').removeAttr('disabled');
			            }
			        },
			        error: function(errorThrown){
			            console.log(errorThrown);
			        }
			    });
		    }, 1000);
		};
	});
</script>

<?php if(isset($_GET['tab'])) : ?>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('.marketing-contacts a[href="#<?php echo $_GET['tab'];?>').click();
		});
	</script>

<?php endif; ?>

<?php
if(isset($_GET['cancel']) && isset($_GET['order_type'])){
	if($_GET['cancel'] == "yes" && $_GET['order_type'] == "purchase"){ ?>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.marketing-contacts a[href="#c"]').click();
				$('.tab-pane#c').addClass('tab-pane-cancellation');
			});
		</script>
<?php
	}
}
?>


<?php
if(isset($_GET['cancel']) && isset($_GET['order_type'])){
	if($_GET['cancel'] == "yes" && $_GET['order_type'] == "membership"){ ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.marketing-contacts a[href="#d"]').click();
		$('.tab-pane#d').addClass('tab-pane-cancellation');
	});
</script>
<?php
	}
}
?>

<?php if(isset($_GET['order_id'])){ ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.marketing-contacts a[href="#c"]').click();
		$('.tab-pane#c .my_account_orders').hide();
		$('#back-to-purchases').click(function(e){
			e.preventDefault();
			$('.tab-pane#c .my_account_orders').fadeIn();
			$('.purchases-view-order').hide();
		});
	});
</script>
<?php } ?>

<?php if(isset($_GET['subs_id'])){ ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.marketing-contacts a[href="#d"]').click();
		$('.tab-pane#d .my_account_subscriptions').hide();
		$('#back-to-memberships').click(function(e){
			e.preventDefault();
			$('.tab-pane#d .my_account_subscriptions').fadeIn();
			$('.membership-view-subs').hide();
		});
		$('.membership-view-subs .wcs-switch-link').remove();
		$('.membership-view-subs .order_item .product-name').append('<a href="/product/membership-products/" class="btn btn-danger">Upgrade or Downgrade</a>');
	});
</script>
<?php } ?>
