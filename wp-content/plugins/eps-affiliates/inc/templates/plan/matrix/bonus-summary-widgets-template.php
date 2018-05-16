<div class="col-md-12">
<div class="col-md-5">
	<div class="panel panel-default text-center m-r-n-xs">
		<div class="panel-body">
			<span>You are a</span>
			<h3 class="m-t-xs"><?= apply_filters('afl_member_current_rank_name',get_uid()) ?></h3>
		</div>
	</div>
</div>
<div class="col-md-2">
	<div class="panel panel-default text-center m-r-n-xs m-l-n-xs">
		<div class="panel-body">
			<span>Global Percentage</span>
			<h3 class="m-t-xs">%<?= apply_filters('afl_member_global_pool_bonus_percentage', get_uid()) ?></h3>
		</div>
	</div>
</div>
<div class="col-md-5">
	<div class="panel panel-default text-center m-l-n-xs">
		<div class="panel-body">
			<span>Global Pool "Bonus" Amount Earned</span>
			<h3 class="m-t-xs">$<?= apply_filters('afl_member_total_global_pool_bonus_earned',get_uid())?> / Monthly</h3>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="col-md-3">
	<div class="panel panel-default text-center m-r-n-xs">
		<div class="panel-body">
			<span>Personal Volume</span>
			<h3 class="m-t-xs"><?= apply_filters('afl_member_personal_volume',get_uid()) ?></h3>
		</div>
	</div>
</div>
<div class="col-md-3">
	<div class="panel panel-default text-center m-l-n-xs m-r-n-xs">
		<div class="panel-body">
			<span>Customers</span>
			<h3 class="m-t-xs"><?= apply_filters('afl_my_customers_count',get_uid())?></h3>
		</div>
	</div>
</div>
<div class="col-md-3">
	<div class="panel panel-default text-center m-l-n-xs m-r-n-xs">
		<div class="panel-body">
			<span>Distributors</span>
			<h3 class="m-t-xs"><?= $result = apply_filters('afl_my_downline_distributors_count',get_uid(), 'unilevel'); ?></h3>
		</div>
	</div>
</div>
</div>

