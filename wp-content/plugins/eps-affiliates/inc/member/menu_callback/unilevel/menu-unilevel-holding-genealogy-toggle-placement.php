<?php 
	function afl_unilevel_holding_tank_genealogy_toggle_placement () {
		do_action('eps_affiliate_page_header');
	 	do_action('afl_content_wrapper_begin');
 			afl_unilevel_holding_tank_genealogy_toggle_placement_callback();
	 	do_action('afl_content_wrapper_end');
	}

	function afl_unilevel_holding_tank_genealogy_toggle_placement_callback() {
		new Afl_enque_scripts('eps-holding-tank-toggle-tree');
		new Afl_enque_scripts('common');
		
		$path = EPSAFFILIATE_PLUGIN_PLAN.'matrix/';
		afl_get_template('plan/unilevel/unilevel-holding-toggle-genealogy-tree-all.php');
	}