<?php 
function afl_admin_pagination_added_pages_conf () {
	new Afl_enque_scripts('common');
	
	echo afl_eps_page_header();
	echo afl_content_wrapper_begin();
		afl_admin_pagination_added_pages_conf_callback();
	echo afl_content_wrapper_end();
		
}

function afl_admin_pagination_added_pages_conf_callback () {
		$form = array();
		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

}