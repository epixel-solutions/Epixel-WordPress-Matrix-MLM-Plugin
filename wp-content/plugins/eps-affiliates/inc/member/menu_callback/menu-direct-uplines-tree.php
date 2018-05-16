<?php

function afl_network_direct_uplines () {
		echo afl_eps_page_header();
		afl_content_wrapper_begin();
			afl_network_direct_uplines_callback();
		afl_content_wrapper_end();
}

function afl_network_direct_uplines_callback () {
		new Afl_enque_scripts('eps-direct-uplines');
		new Afl_enque_scripts('common');
		
		afl_get_template('plan/matrix/member-direct-uplines.php');
}