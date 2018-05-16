<form action="<?php echo get_the_permalink(); ?>/?id=<?php echo get_query_var('acc_id'); ?>" method="POST" class="form-edit">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 m-b-lg">
			<p class="text-label">General Information</p>
			<ul class="list-info list-info-fields">
				<li><span>First Name:</span> <input type="text" name="first_name" id="first_name" value="<?php echo get_the_author_meta('first_name', get_query_var('acc_id')) ?>" /></li>
				<li><span>Last Name:</span> <input type="text" name="last_name" id="last_name" value="<?php echo get_the_author_meta('last_name', get_query_var('acc_id')) ?>" /></li>
				<li><span>Website:</span> <input type="text" name="website" id="website" value="<?php echo get_the_author_meta('website', get_query_var('acc_id')) ?>" /></li>
				<li><span>Facebook:</span> <input type="text" name="facebook" id="facebook" value="<?php echo get_the_author_meta('facebook', get_query_var('acc_id')) ?>" /></li>
				<li><span>Twitter:</span> <input type="text" name="twitter" id="twitter" value="<?php echo get_the_author_meta('twitter', get_query_var('acc_id')) ?>" /></li>
				<li><span>Google Plus:</span> <input type="text" name="googleplus" id="googleplus" value="<?php echo get_the_author_meta('googleplus', get_query_var('acc_id')) ?>" /></li>
			</ul>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 m-b-lg">
			<p class="text-label">Account Information</p>
			<ul class="list-info list-info-fields">
				<li><span>Affiliate ID:</span> <input type="text" readonly value="<?php echo affwp_get_affiliate_id( get_query_var('acc_id') ) ?>" /></li>
				<li><span>Username:</span> <input type="text" name="user_login" id="user_login" value="<?php  if(strpos(get_the_author_meta('user_login', get_query_var('acc_id')), ' ') > 0){}else{echo get_the_author_meta('user_login', get_query_var('acc_id'));}?>" placeholder="<?php if(strpos(get_the_author_meta('user_login', get_query_var('acc_id')), ' ') > 0){echo '{please add your username}';}?>" /></li>
				<li><span>Email:</span> <input type="text" name="user_email" id="user_email" value="<?php echo get_the_author_meta('user_email', get_query_var('acc_id')) ?>" /></li>
				<li><span>Phone Number:</span> <input type="text" name="phone_number" id="phone_number" value="<?php echo get_the_author_meta('phone_number', get_query_var('acc_id')) ?>" /></li>
				<li><span>SMS/Text Messaging:</span>
					<span class="form-checkbox-holder">
						<input type="hidden" value="no" name="user_sms_subs">
						<input class="fx-slide-toggle" name="user_sms_subs" id="user_sms_subs" type="checkbox" <?php if(get_the_author_meta('user_sms_subs', get_query_var('acc_id')) == "yes"){echo 'checked';} ?>>
						<label class="fx-slide-toggle-btn" for="user_sms_subs"></label>
					</span>
				</li>
				<li><span>Email Updates:</span> 
					<span class="form-checkbox-holder">
					<input type="hidden" value="no" name="user_email_subs">
						<input class="fx-slide-toggle" name="user_email_subs" id="user_email_subs" type="checkbox" <?php if(get_the_author_meta('user_email_subs', get_query_var('acc_id')) == "yes"){echo 'checked';} ?>>
						<label class="fx-slide-toggle-btn" for="user_email_subs"></label>
					</span>
				</li>
			</ul>
		</div>
		<div class="clearfix"></div>
		<div class="col-xs-12 col-sm-12 col-md-6">
			<p class="text-label">Billing Information</p>
			<ul class="list-info list-info-fields">
				<li><span>Business Name:</span> <input type="text" name="billing_company" id="billing_company" value="<?php echo get_the_author_meta('billing_company', get_query_var('acc_id')) ?>" /></li>
				<li><span>House # & Street Name:</span> <input type="text" name="billing_address_1" id="billing_address_1" value="<?php echo get_the_author_meta('billing_address_1', get_query_var('acc_id')) ?>" /></li>
				<li><span>Apt.,suite,unit,etc.:</span> <input type="text" name="billing_address_2" id="billing_address_2" value="<?php echo get_the_author_meta('billing_address_2', get_query_var('acc_id')) ?>" /></li>
				<li><span>City:</span> <input type="text" name="billing_city" id="billing_city" value="<?php echo get_the_author_meta('billing_city', get_query_var('acc_id')) ?>" /></li>
				<li><span>State:</span> <input type="text" name="billing_state" id="billing_state" value="<?php echo get_the_author_meta('billing_state', get_query_var('acc_id')) ?>" /></li>
				<li><span>Zip Code:</span> <input type="text" name="billing_postcode" id="billing_postcode" value="<?php echo get_the_author_meta('billing_postcode', get_query_var('acc_id')) ?>" /></li>
			</ul>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 xs-m-t">
			<p class="text-label">Shipping Information</p>
			<ul class="list-info list-info-fields">
				<li><span>Business Name:</span> <input type="text" name="shipping_company" id="shipping_company" value="<?php echo get_the_author_meta('shipping_company', get_query_var('acc_id')) ?>" /></li>
				<li><span>House # & Street Name:</span> <input type="text" name="shipping_address_1" id="shipping_address_1" value="<?php echo get_the_author_meta('shipping_address_1', get_query_var('acc_id')) ?>" /></li>
				<li><span>Apt.,suite,unit,etc.:</span> <input type="text" name="shipping_address_2" id="shipping_address_2" value="<?php echo get_the_author_meta('shipping_address_2', get_query_var('acc_id')) ?>" /></li>
				<li><span>City:</span> <input type="text" name="shipping_city" id="shipping_city" value="<?php echo get_the_author_meta('shipping_city', get_query_var('acc_id')) ?>" /></li>
				<li><span>State:</span> <input type="text" name="shipping_state" id="shipping_state" value="<?php echo get_the_author_meta('shipping_state', get_query_var('acc_id')) ?>" /></li>
				<li><span>Zip Code:</span> <input type="text" name="shipping_postcode" id="shipping_postcode" value="<?php echo get_the_author_meta('shipping_postcode', get_query_var('acc_id')) ?>" /></li>
			</ul>

		</div>
		<div class="clearfix">	</div>
	</div>
	<div class="btn-holder btn-right m-t-lg">
		<button type="submit" class="btn btn-default">Save</button>
	</div>
</form>