<?php
add_filter('eps_affiliates_custom_permissions_table','eps_affiliates_epin_permissions',1);
function eps_affiliates_epin_permissions ($permissions) {
	//epin
		$permissions['epin'] = array(
			'#title' 				=> __('E-pin'),
			'#description' 	=> 'Create, modify and view Epin'
		);
		$permissions['epin_conf'] = array(
			'#title' 				=> __('E-pin Configuration'),
			'#description' 	=> 'E pIn configuration'
		);
	return $permissions;
}