
<div class="panel panel-default text-center m-r-n-xs">
	<div class="panel-body">
		<span><?= __('Last Month'); ?></span>
		<?php $uid = !empty($_GET['uid']) ? $_GET['uid'] : '';?>
		<h3 class="m-t-xs">$<?= apply_filters('afl_ewallet_last_month_earnings',$uid, 'last_month') ?></h3>
	</div>
</div>