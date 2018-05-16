<?php 
/*
 * ---------------------------------------------------------------------
 * Roles configuration settings form
 * ---------------------------------------------------------------------
 * 
 * here sets the custom pemissions to the user access.
 * Here loads all the permissions from the function custom_permissions
 * get all the roles 
 * Exclude some of the roles and some permissions
 *
*/
	function afl_roles_config_settings(){
		echo afl_eps_page_header();
		new Afl_enque_scripts('common');
		
		if (isset($_POST['submit'])) {
			afl_roles_config_settings_form_submit($_POST);
		}

 		afl_content_wrapper_begin();

		global $wp_roles;
	  $all_roles 			= $wp_roles->roles;
	  $editable_roles = apply_filters('editable_roles', $all_roles);
	  //exclude some roles
	  // $excluded_roles = array(
	  // 	'editor',
	  // 	'author',
	  // 	'contributor',
	  // 	'subscriber',
	  // 	'customer',
	  // 	'shop_manager',
	  // );
	  // $excluded_roles = list_extract_allowed_values(afl_variable_get('afl_var_permission_table_roles'),'list_text','');
	  $included_roles = list_extract_allowed_values(afl_variable_get('afl_var_permission_table_roles'),'list_text','');
	  
	  $roles 					= array();

	  foreach ($editable_roles as $key => $value) {
	  	if (in_array($key,$included_roles) ){
		  	$roles['name'][] 	= $value['name'];
		  	$roles['key'][] 	= $key;
	  	}
	  }

	  $count_roles 					= count($roles['name']);
	  $table 								= array();
		$table['#name'] 			= '';
		$table['#title'] 			= '';
		$table['#prefix'] 		= '';
		$table['#suffix'] 		= '';
		$table['#attributes'] = array(
						'class' => array(
								'table',
								'table-bordered',
								'table-responsive'
							)
						);

		$roles['name'][] 			= 'Permission Name';
		$table['#header'] 		= array_reverse($roles['name']);
		$roles 								= array_reverse($roles['key']);
		$custom_permissions   = custom_permissions();

		//exclude some permissions
		$excluded_perm = array(
			// 'afl_roles_configuration' => 'afl_roles_configuration'
		);
		//remove excluded permissions
		$custom_permissions = array_diff_key($custom_permissions, $excluded_perm);

		

		$i = 0;
		foreach ($custom_permissions as $key => $value) {
				$rows[$i]['label_1'] 	= array(
					'#type'  => 'label',
					'#title' => isset($value['#title'])? $value['#title'] : '',
					'#description' => isset($value['#description'])? $value['#description'] : '',
				);
				for ($inc = 0; $inc < $count_roles ; $inc++) { 
					$rows[$i][$key.'_'.$roles[$inc]] = array(
						'#type' 					=> 'checkbox',
						'#attributes'			=> array('form-checkbox','checkbox'),
						'#default_value' 	=> isset($_POST['var'][$key.'_'.$roles[$inc]]) ? TRUE : afl_variable_get($key.'_'.$roles[$inc],''),
				 	);	
				}
				
				$i++;
		}

	 	$table['#rows'] = $rows;
		$render_table  = '';
		$render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-afl-roles-config'));
		$render_table .= afl_render_table($table);
		$render_table .= afl_input_button('submit', 'Save configuration', '',array('class'=>'btn btn-default btn-primary'));

		$render_table .= afl_form_close();

		echo $render_table;

 		afl_content_wrapper_end();

	}
/*
 * ---------------------------------------------------------------------
 * Submit hook
 * ---------------------------------------------------------------------
 * here sets the custom pemissions to the user access.
 * Here loads all the permissions from the function custom_permissions
 * get all the roles 
 * Exclude some of the roles and some permissions
 * Excluded permission cannot be updated
 *
*/
	function afl_roles_config_settings_form_submit($POST){
		$val = $POST;
		//get the role names
		global $wp_roles;
	  $all_roles 			= $wp_roles->roles;
	  $editable_roles = apply_filters('editable_roles', $all_roles);
	  $roles 					= array();
	  foreach ($editable_roles as $key => $value) {
	  	$roles[] = $key;
	  }
	  $count_roles 					= count($roles);

	  //get he permission names and name that gives to the checkbox
	  $per_names 						= array();
	  $custom_permissions   = custom_permissions();

		$i = 0;

		//get permission name and role
		foreach ($custom_permissions as $key => $value) {
			for ($inc = 0; $inc < $count_roles ; $inc++) { 
				$per_names['name'][] 	= $key.'_'.$roles[$inc];

				$per_names['per_name_vs_role'][$key][]  = $roles[$inc];
				$per_names['role_vs_perm_name'][$key.'_'.$roles[$inc]]  = $key;
			}
			$i++;
		}
		//exclude some permissions
		$excluded_perm = array(
			// 'afl_roles_configuration' => 'afl_roles_configuration'
		);
		
		//exclude the admin 
	  // $excluded_roles = list_extract_allowed_values(afl_variable_get('afl_var_permission_table_roles'),'list_text','');
	  $included_roles = list_extract_allowed_values(afl_variable_get('afl_var_permission_table_roles'),'list_text','');

		//add capability to the role
		foreach ($per_names['per_name_vs_role'] as $permission_name => $var) {
			if (!array_key_exists($permission_name, $excluded_perm)) {
				foreach ($var as $role) {
					if (in_array($role, $included_roles)) {
						if (array_key_exists($permission_name.'_'.$role,$val) && !empty($val[$permission_name.'_'.$role])) {
							// $per_names['key'][$var] 	: Role name 
							// $var 						 				: Permission name
							if (!has_permission($permission_name, $role)) {
								add_permission($permission_name, $role);
							}
							afl_variable_set($permission_name.'_'.$role, maybe_serialize($val[$permission_name.'_'.$role]));
						} else {
							//not remove the permission of 
								if (has_permission($permission_name, $role)) {
									remove_permission($permission_name, $role);
							}
							afl_variable_set($permission_name.'_'.$role, '');
						}
					}
				}
			}
		}
	//set the variable "eps_afl_is_configur_permissions"
	afl_variable_set('eps_afl_is_configur_permissions',1);
	wp_set_message(__('Configuration has been saved successfully.'), 'success');
	}
