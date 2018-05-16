<div class="panel panel-default text-center m-r-n-xs">
	<div class="panel-body">
		<span><?= __('Today'); ?></span>
		<?php $uid = !empty($_GET['uid']) ? $_GET['uid'] : '';?>

		<h3 class="m-t-xs">$<?= apply_filters('afl_ewallet_today_earnings',$uid, 'today') ?></h3>
	</div>
</div>