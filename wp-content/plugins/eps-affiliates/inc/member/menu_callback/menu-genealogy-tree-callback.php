<?php 
function afl_genealogy_tree() {
	echo afl_eps_page_header();
	echo afl_content_wrapper_begin();
	afl_genealogy_tree_callback();
	echo afl_content_wrapper_end();
	// define( 'EPSAFFILIATE_PLUGIN_PLAN', plugin_dir_url('eps-affiliates/inc/plan'));
}

function afl_genealogy_tree_callback() {
	$obje = new Afl_enque_scripts('eps-genealogy') ;
		new Afl_enque_scripts('common');
	
	
	// wp_enqueue_style( 'plan-style', EPSAFFILIATE_PLUGIN_PLAN.'matrix/css/tree-new/style.css');
	$path = EPSAFFILIATE_PLUGIN_PLAN.'matrix/';
	afl_get_template('plan/matrix/genealogy-tree-all.php');
}