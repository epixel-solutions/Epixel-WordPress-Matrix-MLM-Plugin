<!-- Modal -->
<div class="modal fade resched-webinar-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
			<h4>Current Date, Chosen : <span class="current-date"></span></h4>
			<h4>Current Time, Chosen : <span class="current-time"></span></h4>
			<div class="jquery-ui-datepicker">
				<div id="resched-product-datepicker"></div>
				<p></p>
				
				<div id="woowebinar-time-range">
					<div class="ajax-reched-woowebinar-time-rage"></div>
				</div>
				<p></p>
				<form class="resched-form" method="post" enctype='multipart/form-data'>
					<input type="hidden" name="selected_date" class="selected_date">
					<input type="hidden" name="selected_month" class="selected_month">
					<input type="hidden" name="selected_year" class="selected_year">
					<input type="hidden" name="selected_time" class="selected_time">
					<input type="submit" name="Re-Sched" class="resched-button">
				</form>
			</div>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>