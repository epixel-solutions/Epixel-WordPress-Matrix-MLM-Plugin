<div class="panel panel-default text-center m-r-n-xs">
	<div class="panel-body">
		<span><?= __('Yesterday'); ?></span>
		<?php $uid = !empty($_GET['uid']) ? $_GET['uid'] : '';?>

		<h3 class="m-t-xs">$<?= apply_filters('afl_ewallet_yesterday_earnings',$uid, 'yesterday') ?></h3>
	</div>
</div>