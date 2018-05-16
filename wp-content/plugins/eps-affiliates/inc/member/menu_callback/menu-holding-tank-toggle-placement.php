<?php
	function afl_holding_tank_toggle_placement () {
		do_action('eps_affiliate_page_header');
	 	do_action('afl_content_wrapper_begin');
 			afl_holding_tank_toggle_placement_form();
	 	do_action('afl_content_wrapper_end');
	}

	function afl_holding_tank_toggle_placement_form () {
		$uid = !empty($_GET['uid']) ? $_GET['uid'] : 0; 
		afl_get_template('plan/matrix/holding-tank-toggle-placement.php', array('uid' => $uid));
	}