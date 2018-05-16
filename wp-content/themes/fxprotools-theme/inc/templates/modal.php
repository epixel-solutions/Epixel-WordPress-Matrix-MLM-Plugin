<!-- Modal -->
<div class="modal fade webinar-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Register for FREE Live Webinar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<form name="register-webinar" class="register-webinar" method="post">
			<div class="form-group">
				<label for="firstName">First Name</label>
				<input type="text" class="form-control" name="firstName" id="firstName" placeholder="First Name" value="<?php echo isset($current_user->first_name) ? $current_user->first_name:'';?>">
			</div>
			<div class="form-group">
				<label for="lastName">Last Name</label>
				<input type="text" class="form-control" name="lastName" id="lastName" placeholder="Last Name" value="<?php echo isset($current_user->last_name) ? $current_user->last_name:'';?>">
			</div>
			<div class="form-group">
				<label for="emailaddress">Email Address</label>
				<input type="email" name="email" class="form-control" id="email" placeholder="Email Address" value="<?php echo isset($current_user->user_email) ? $current_user->user_email:'';?>">
			</div>
			<div class="form-group">
				<label for="phone">Phone</label>
				<input type="text" name="phone" class="form-control" id="phone" placeholder="Phone" value="<?php echo isset($current_user->mobile) ? $current_user->mobile:'';?>">
			</div>
			<div class="form-check">
				<label class="form-check-label">
					<div class="ajax-webinars-msg"></div>
					<div class="ajax-webinars"></div>
				</label>
			</div>
			
		</form>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>