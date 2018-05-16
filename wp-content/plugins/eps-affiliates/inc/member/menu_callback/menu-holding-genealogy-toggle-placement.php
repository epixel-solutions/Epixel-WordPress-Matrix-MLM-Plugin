<?php 
	function afl_holding_tank_genealogy_toggle_placement () {
		do_action('eps_affiliate_page_header');
	 	do_action('afl_content_wrapper_begin');
 			afl_holding_tank_genealogy_toggle_placement_form();
	 	do_action('afl_content_wrapper_end');
	}

	function afl_holding_tank_genealogy_toggle_placement_form() {

		new Afl_enque_scripts('eps-holding-tank-toggle-tree');
		new Afl_enque_scripts('common');
		
		
		// wp_enqueue_style( 'plan-style', EPSAFFILIATE_PLUGIN_PLAN.'matrix/css/tree-new/style.css');
		$path = EPSAFFILIATE_PLUGIN_PLAN.'matrix/';
		afl_get_template('plan/matrix/holding-toggle-genealogy-tree-all.php');
	}