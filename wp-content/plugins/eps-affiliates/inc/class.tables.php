<?php 
/*
 * ----------------------------------------------------------------
 * Create tables if it doesnot exists
 * ----------------------------------------------------------------
 *
*/
class Eps_affiliates_tables {
	
	/*
 	 * ----------------------------------------------------------------
 	 * Set table prefix
 	 * ----------------------------------------------------------------
	*/
		private $tbl_prefix 			= '';
		private $charset_collate 	= '';
	/*
 	 * ----------------------------------------------------------------
	 * Constructor
 	 * ----------------------------------------------------------------
	*/
		public function __construct(){
			if( !get_option( 'eps_afl_is_installed' ) ) {
				//get the version of the plugin
				$version 	= EPSAFFILIATE_DB_VERSION;
				//check a file exist in the folder
				if ($version) {
					$version = str_replace('.', '_', $version);
					$file_name = EPSAFFILIATE_PLUGIN_DIR.'migrations/Migration_database_version_'.$version.'.php';
					if (file_exists($file_name)) {
						include_once($file_name);
						//check class exist
						if (class_exists("Migration_database_version_".$version)) {
							$class_name 	 = 'Migration_database_version_'.$version;		 
							$migration_obj = new $class_name;
							//run the upgrade function 
							if (method_exists($migration_obj, 'migration_upgrade')) {
								$migration_obj->migration_upgrade();
							}
							//run downgrade function
							if (method_exists($migration_obj, 'migration_downgrade')) {
								$migration_obj->migration_downgrade();
							}
						}
					}
				}
			}
		}
}

