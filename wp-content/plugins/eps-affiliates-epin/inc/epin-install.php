<?php
/*
 * ------------------------------------------------------------
 * Install functions and Features 
 * ------------------------------------------------------------
*/
function eps_affiliates_epin_install() {

	//install tables
	$afl_tables = new Eps_affiliates_epin_tables; 
	//set the variable for installation 
	update_option( 'eps_epin_is_installed', 1 );
	
}

function eps_affiliate_epin_check_if_installed() {
		// this is mainly for network activated installs
		if( !get_option( 'eps_epin_is_installed' ) ) {
			eps_affiliates_epin_install();
		}
}

