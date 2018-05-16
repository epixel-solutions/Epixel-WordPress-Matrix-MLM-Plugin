<?php
function afl_network_holding_tank () {
	echo afl_eps_page_header();
	
	echo afl_content_wrapper_begin();
		afl_network_holding_tank_callback();
	echo afl_content_wrapper_end();
}

function afl_network_holding_tank_callback () {
	$obje = new Afl_enque_scripts('eps-holding-tank');
		new Afl_enque_scripts('common');
	
		afl_get_template('plan/matrix/holding-tank.php');

}