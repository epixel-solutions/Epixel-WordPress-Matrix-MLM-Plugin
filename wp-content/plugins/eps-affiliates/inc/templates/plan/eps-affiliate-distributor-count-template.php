<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default text-center">
		  <div class="panel-heading"><?= __('Distributors'); ?></div>
		  <div class="panel-body"><?= apply_filters('afl_my_distributors_count',get_uid()); ?></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default text-center">
		  <div class="panel-heading"><?= __('Customers'); ?></div>
		  <div class="panel-body"><?= apply_filters('afl_my_customers_count',get_uid()); ?></div>
		</div>
	</div>
</div>