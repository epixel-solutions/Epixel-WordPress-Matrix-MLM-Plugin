<?php 
/**
 * ----------------------------------------------------------
 * @author < pratheesh@epixelsolutions.com > 
 *
 * Enque the styles and scripts based on the page loaded,
 * this will be called from each page of eps affiliate
 * ---------------------------------------------------------- 
*/

	class Afl_enque_scripts {
		public function __construct ( $page = '') {
			$enque_array['css'] = array();
			$enque_array['js'] 	= array();
			$enque_array['ajax_object'] = array();

		  	switch ( $page ) {

		  		//common
		  		case 'common':
		  			$enque_array['js'] = array(
							'jquery-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js',
							'toaster-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/toastr.js',
							'jquery-ui' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js',
							'widget-scripts' 	=> EPSAFFILIATE_PLUGIN_ASSETS.'js/widget-scripts.js',
							'bootstrap-typehead-ui'	=> EPSAFFILIATE_PLUGIN_ASSETS.'js/bootstrap-typeahead.js',
							'common-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js',
						);
		  			
		  			$enque_array['css'] = array(
		  				'bootstrap-css'		=> '',
		  				'toaster-cs' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/toastr.css',
		  				'app' 						=> EPSAFFILIATE_PLUGIN_ASSETS.'css/app.css',
		  				'developer' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/developer.css',
		  				'simple-line-ico'	=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/simple-line-icons/css/simple-line-icons.css',
		  			);

					if(is_admin() || current_user_can('eps_affiliates_access_backoffice')){
						$enque_array['css']['bootstrap-css'] = EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css';
					}

					$enque_array['ajax_object'] = array(
						'common-js' => array(
							'object_name' => 'ajax_object',
							'data' 				=> array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))	
						)
					);
		  		break;


				  //Dashboard scripts
		  		case 'eps-dashboard':
		  			$enque_array['js'] = array(
						'jquery-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js',
						'widget-scripts' 	=> EPSAFFILIATE_PLUGIN_ASSETS.'js/widget-scripts.js',
						'highchart-js' 		=> EPSAFFILIATE_PLUGIN_ASSETS.'js/highcharts.js',
						'jquery-ui' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js',
						'toaster-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/toastr.js',
						'common-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js',
					);
		  			
		  			$enque_array['css'] = array(
		  				'toaster-cs' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/toastr.css',
		  				'bootstrap-css'		=> '',
		  				'app' 						=> EPSAFFILIATE_PLUGIN_ASSETS.'css/app.css',
		  				'developer' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/developer.css',
		  				'simple-line-ico'	=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/simple-line-icons/css/simple-line-icons.css',
		  			);

		  			if(is_admin() || current_user_can('eps_affiliates_access_backoffice')){
						$enque_array['css']['bootstrap-css'] = EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css';
					}

					$enque_array['ajax_object'] = array(
						'common-js' => array(
							'object_name' => 'ajax_object',
							'data' 				=> array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))	
						)
					);
		  		break;
				   
			    //add-new-member scripts
		  		case 'eps-add-new-member':
		  			$enque_array['js'] = array(
						'jquery-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js',
						'jquery-ui' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js',
						'toaster-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/toastr.js',
						'common-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js',
					);
					$enque_array['css'] = array(
		  				'toaster-cs' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/toastr.css',
		  				'bootstrap-css'		=> '',
		  			);
		  			if(is_admin() || current_user_can('eps_affiliates_access_backoffice')){
						$enque_array['css']['bootstrap-css'] = EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css';
					}
		  		break;

		  		//enque scripts
		  		case 'eps-toaster':
		  			$enque_array['js'] = array(
						'jquery-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js',
						'jquery-ui' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js',
						'toaster-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/toastr.js',
						'common-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js',
					);
					$enque_array['css'] = array(
		  				'toaster-cs' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/toastr.css',
		  				'bootstrap-css'		=> '',
		  			);
		  			if(is_admin() || current_user_can('eps_affiliates_access_backoffice')){
						$enque_array['css']['bootstrap-css'] = EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css';
					}
		  		break;

		  		//genealogy
		  		case 'eps-genealogy':
		  			$enque_array['js'] = array(
						'jquery-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js',
						'jquery-ui' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js',
						'toaster-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/toastr.js',
						'bootstrap-js'		=> EPSAFFILIATE_PLUGIN_ASSETS.'js/bootstrap.min.js',
						'common-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js',
					);

		  			$enque_array['css'] = array(
		  				'toaster-cs' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/toastr.css',
						'plan-heirarchy'	=> EPSAFFILIATE_PLUGIN_PLAN.'/matrix/css/heirarchy/css/hierarchy-view.css',
		  				'plan-style' 			=> EPSAFFILIATE_PLUGIN_PLAN.'matrix/css/heirarchy/css/main.css',
		  				'bootstrap-css'		=> '',
		  			);

		  			if(is_admin() || current_user_can('eps_affiliates_access_backoffice')){
						$enque_array['css']['bootstrap-css'] = EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css';
					}

					$enque_array['ajax_object'] = array(
						'common-js' => array(
							'object_name' => 'ajax_object',
							'data' 				=> array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))	
						)
					);
	  			break;

	  			//direct uplines
	  			case 'eps-direct-uplines':
		  			$enque_array['css'] = array(
		  				'zig-zag-cs'		  => EPSAFFILIATE_PLUGIN_ASSETS.'plugins/zigzag-timeline/Zigzagtimeline.css',
		  				'bootstrap-css'		=> '',
		  			);
		  			if(is_admin() || current_user_can('eps_affiliates_access_backoffice')){
						$enque_array['css']['bootstrap-css'] = EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css';
					}
	  			break;

		  		//holding tank
		  		case 'eps-holding-tank':

		  			$enque_array['js'] = array(
						'jquery-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js',
						'widget-scripts' 	=> EPSAFFILIATE_PLUGIN_ASSETS.'js/widget-scripts.js',
						'highchart-js' 		=> EPSAFFILIATE_PLUGIN_ASSETS.'js/highcharts.js',
						'jquery-ui' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js',
						'toaster-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/toastr.js',
						'bootstrap-js'		=> EPSAFFILIATE_PLUGIN_ASSETS.'js/bootstrap.min.js',
						'bootstrap-typehead-ui'	=> EPSAFFILIATE_PLUGIN_ASSETS.'js/bootstrap-typeahead.js',
						'common-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js',
					);
		  			
		  			$enque_array['css'] = array(
		  				'toaster-cs' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/toastr.css',
		  				'bootstrap-css'		=> '',
		  			);

		  			if(is_admin() || current_user_can('eps_affiliates_access_backoffice')){
						$enque_array['css']['bootstrap-css'] = EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css';
					}

					$enque_array['ajax_object'] = array(
						'common-js' => array(
							'object_name' => 'ajax_object',
							'data' 				=> array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))	
						)
					);
	  			break;

	  			//holiding tank toggle
	  			case 'eps-holding-tank-toggle-tree':

		  			$enque_array['js'] = array(
						'jquery-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js',
						'widget-scripts' 	=> EPSAFFILIATE_PLUGIN_ASSETS.'js/widget-scripts.js',
						'highchart-js' 		=> EPSAFFILIATE_PLUGIN_ASSETS.'js/highcharts.js',
						'jquery-ui' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js',
						'toaster-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/toastr.js',
						'jquery-cnfrm-js'	=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-confirm/js/jquery-confirm.js',
						'common-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js',
					);
		  			
		  			$enque_array['css'] = array(
		  				'toaster-cs' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/toastr.css',
						'jquery-cnfrm-cs'	=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-confirm/css/jquery-confirm.css',
						'plan-heirarchy'	=> EPSAFFILIATE_PLUGIN_PLAN.'/matrix/css/heirarchy/css/hierarchy-view.css',
		  				'plan-style' 			=> EPSAFFILIATE_PLUGIN_PLAN.'matrix/css/heirarchy/css/main.css',
		  				'bootstrap-css'		=> '',
		  			);

		  			if(is_admin() || current_user_can('eps_affiliates_access_backoffice')){
						$enque_array['css']['bootstrap-css'] = EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css';
					}

					$enque_array['ajax_object'] = array(
						'common-js' => array(
							'object_name' => 'ajax_object',
							'data' 				=> array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))	
						)
					);
	  			break;

	  			// jquery bootstrap tables
	  			case 'eps-jquery-tables':
	  				$enque_array['js'] = array(
						'jquery-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js',
						'jquery-ui' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js',
						'toaster-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/toastr.js',
						'jquery-data-tbl'	=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/dataTables/js/jquery.dataTables.min.js',
						'jq-btdata-tbl'		=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/dataTables/js/dataTables.bootstrap.min.js',
						'common-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js',
					);
		  			
		  			$enque_array['css'] = array(
		  				'toaster-cs' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/toastr.css',
		  				'plan-develoepr' 	=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/dataTables/css/dataTables.bootstrap.min.css',
		  				'bootstrap-css'		=> '',
		  			);

		  			if(is_admin() || current_user_can('eps_affiliates_access_backoffice')){
						$enque_array['css']['bootstrap-css'] = EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css';
					}

					$enque_array['ajax_object'] = array(
						'common-js' => array(
							'object_name' => 'ajax_object',
							'data' 				=> array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))	
						)
					);
	  			break;

	  			//test 
	  			case 'test':
		  			$enque_array['js'] = array(
						'jquery-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js',
						'jquery-ui' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js',
						'toaster-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/toastr.js',
						'bootstrap-js'		=> EPSAFFILIATE_PLUGIN_ASSETS.'js/bootstrap.min.js',
						'bts-typehead-ui'	=> EPSAFFILIATE_PLUGIN_ASSETS.'js/bootstrap-typeahead.js',
						'common-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js',
					);
		  			
		  			$enque_array['css'] = array(
		  				'toaster-cs' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/toastr.css',
		  				'bootstrap-css'		=> '',
		  			);

		  			if(is_admin() || current_user_can('eps_affiliates_access_backoffice')){
						$enque_array['css']['bootstrap-css'] = EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css';
					}

					$enque_array['ajax_object'] = array(
						'common-js' => array(
							'object_name' 	=> 'ajax_object',
							'data' 					=> array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))	
						)
					);
	  			break;

	  			//remote api 
	  			case 'remote-api':
	  				$enque_array['js'] = array(
						'jquery-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js',
						'api-js' 					=> EPSAFFILIATE_PLUGIN_ASSETS.'js/api.js',
						'jquery-ui' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js',
						'toaster-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/toastr.js',
						'common-js' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js',
					);
		  			
		  			$enque_array['css'] = array(
		  				'toaster-cs' 			=> EPSAFFILIATE_PLUGIN_ASSETS.'css/toastr.css',
		  				'bootstrap-css'		=> '',
		  			);

		  			if(is_admin() || current_user_can('eps_affiliates_access_backoffice')){
							$enque_array['css']['bootstrap-css'] = EPSAFFILIATE_PLUGIN_ASSETS.'css/bootstrap/css/bootstrap.css';
						}

					$enque_array['ajax_object'] = array(
						'common-js' => array(
							'object_name' 	=> 'ajax_object',
							'data' 					=> array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))	
						)
					);
	  			break;
		  	}
		  	
				$this->_enque_scripts($enque_array);
		}

	  public function _enque_scripts ( $enque_array = array()) {
	  	// here enque the scripts
	  	if ( !empty($enque_array['js']) ){
	  		foreach ($enque_array['js'] as $handle => $path) {
	  			wp_register_script( $handle,  $path);
					wp_enqueue_script( $handle );
	  		}
	  	}
	  	
	  	// here enque the css
	  	if ( !empty($enque_array['css'])) {
	  		foreach ($enque_array['css'] as $handle => $path) {

	  			wp_register_style( $handle, $path);
					wp_enqueue_style( $handle);
	  		}
	  	}

	  	// here localize the objects
	  	if ( !empty($enque_array['ajax_object'])) {
	  		foreach ($enque_array['ajax_object'] as $handle => $data) {
	  			if ( !empty($data['object_name']) && !empty($data['data'])) {
 						wp_localize_script( $handle, $data['object_name'], $data['data'] );
	  				
	  			}
	  		}
	  	}
	  }
	}