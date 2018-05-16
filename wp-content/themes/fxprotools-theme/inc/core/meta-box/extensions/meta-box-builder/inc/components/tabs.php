<h2 class="nav-tab-wrapper wp-clearfix">

    <?php $tab = mbb_get_current_tab(); ?>

	<a href="<?php echo add_query_arg('tab', 'builder'); ?>" class="nav-tab <?php if ($tab === 'builder' ) echo 'nav-tab-active' ?>">Fields</a>
	<a href="<?php echo add_query_arg('tab', 'settings'); ?>" class="nav-tab <?php if ($tab === 'settings' ) echo 'nav-tab-active' ?>">Settings</a>
</h2>