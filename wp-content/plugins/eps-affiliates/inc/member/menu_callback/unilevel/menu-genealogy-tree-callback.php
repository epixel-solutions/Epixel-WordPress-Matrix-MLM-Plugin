<?php 
function afl_unilevel_genealogy_tree() {
	echo afl_eps_page_header();
	echo afl_content_wrapper_begin();
	afl_unilevel_genealogy_tree_callback();
	echo afl_content_wrapper_end();
	// define( 'EPSAFFILIATE_PLUGIN_PLAN', plugin_dir_url('eps-affiliates/inc/plan'));
}

function afl_unilevel_genealogy_tree_callback() {
	$obje = new Afl_enque_scripts('eps-genealogy') ;
		new Afl_enque_scripts('common');
	
	$path = EPSAFFILIATE_PLUGIN_PLAN.'unilevel/';
	afl_get_template('plan/unilevel/genealogy-tree-all.php');
}