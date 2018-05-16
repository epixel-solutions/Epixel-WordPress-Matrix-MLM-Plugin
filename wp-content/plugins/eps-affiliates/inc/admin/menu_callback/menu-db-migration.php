<?php 
	function afl_db_migrations () {
			new Afl_enque_scripts('common');
  	echo afl_eps_page_header();
  	echo afl_content_wrapper_begin();
    	afl_db_migrations_callback();
  	echo afl_content_wrapper_begin();
	}

	function afl_db_migrations_callback () {
		
		//update_option('eps_affiliate_db_updated_version', 0);

		if (isset($_POST['upgrade_db'])) {
			$check_exists = _check_db_upgrade_exists();
			if (isset($check_exists['status'])) {
				if(_upgrade_db_from_file($check_exists['file_path'], $check_exists['version_file_index'])){

					update_option('eps_affiliate_db_updated_version', EPSAFFILIATE_DB_VERSION);

					wp_set_message('DB has been upgraded successfully');
				} else {
					wp_set_message('Unable to upgrade the db.');
				}
			}
		}
		afl_db_migrations_upgrade_form();
	}

	function afl_db_migrations_upgrade_form () {
		$check_exists = _check_db_upgrade_exists();
		if (isset($check_exists['status']) && !empty($check_exists['status'])) {

			$form = array();
		 	$form['#action'] = $_SERVER['REQUEST_URI'];
		 	$form['#method'] = 'post';
		 	$form['#prefix'] ='<div class="form-group row">';
		 	$form['#suffix'] ='</div>';

		 	$form['markup'] = array(
		 		'#type' => 'markup',
		 		'#markup' => '<div class="alert alert-success">
				  <strong>Upgrade DB</strong> You have a file <b>'.$check_exists['file_name'].'</b> for updating the database.
				</div>',
		 	);
		 	$form['upgrade_db'] = array(
		 		'#title' => 'Upgrade DB',
		 		'#type' => 'submit',
		 		'#value' => 'Upgrade DB',
		 		'#name' => 'upgrade_db',
		 		'#attributes' => array(
		 			'class' => array(
		 				'btn','btn-primary'
		 			)
		 		),
		 		
		 	);
		 	echo afl_render_form($form);

		} else {

			$db_updated_version = !empty(get_option('eps_affiliate_db_updated_version')) ? get_option('eps_affiliate_db_updated_version') : 0;
			$plugin_updated_version = EPSAFFILIATE_VERSION;

			$form['markup'] = array(
		 		'#type' => 'markup',
		 		'#markup' => '<div class="col-md-12">
        <div class="alert alert-success bold">
            <h4 class="bold">Hi! Thanks for updating EpsAffiliates Plugin - You are using version '.$db_updated_version. ' DB and '.$plugin_updated_version.' version of codes.</h4>
        </div>
    </div>
    ',
		 	);
		 	echo afl_render_form($form);
		}




	}

/**
 * ----------------------------------------------------------------------------------\
 * check any latest upgrades are exists 
 * ----------------------------------------------------------------------------------
*/
	function _check_db_upgrade_exists () {
		$response = [];
		$response['status'] = 0;
		//latest updated database file version in this project
		$updated_version = !empty(get_option('eps_affiliate_db_updated_version')) ? get_option('eps_affiliate_db_updated_version') : 0;
		//get the db version
		$version = EPSAFFILIATE_DB_VERSION;
		//check the has latest update
		if ( $version > $updated_version) {
			if ($version) {
				$version_file_index = str_replace('.', '_', $version);
			}

			$version_file = 'Migration_database_version_'.$version_file_index;

			$file_path = EPSAFFILIATE_PLUGIN_DIR.'migrations/'.$version_file.'.php';
			if (file_exists($file_path)) {
				include_once($file_path);
				//check class exist
				if (class_exists("Migration_database_version_".$version_file_index)) {
					$class_name 	 = 'Migration_database_version_'.$version_file_index;		 
					$migration_obj = new $class_name;
					//run the upgrade function 
					if (method_exists($migration_obj, 'migration_upgrade')) {
						$response['status'] = 1;
						$response['file_name'] = $version_file ;
						$response['file_path'] = $file_path ;
						$response['version_file_index'] = $version_file_index ;
					}
				}
			}
		}

		return 	$response;
	}
/**
 * ----------------------------------------------------------------------------------\
 * upgrade the db from  the file
 * ----------------------------------------------------------------------------------
*/
	function _upgrade_db_from_file ( $file_path = '', $version_file_index = '' ) {
		if (file_exists($file_path)) {
			include_once($file_path);
			//check class exist
			if (class_exists("Migration_database_version_".$version_file_index)) {
				$class_name 	 = 'Migration_database_version_'.$version_file_index;		 
				$migration_obj = new $class_name;
				//run the upgrade function 
				if (method_exists($migration_obj, 'migration_upgrade')) {
					$migration_obj->migration_upgrade();
					return TRUE;
				}

				//run the update function 
				if (method_exists($migration_obj, 'migration_update')) {
					$migration_obj->migration_update();
					return TRUE;
				}

			}
		} else {
			return FALSE;
		}
	}