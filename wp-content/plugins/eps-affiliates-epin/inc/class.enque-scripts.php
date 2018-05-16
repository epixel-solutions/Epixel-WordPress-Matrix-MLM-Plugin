<?php 
/**
 * ----------------------------------------------------------
 * @author < pratheesh@epixelsolutions.com > 
 *
 * Enque the styles and scripts based on the page loaded,
 * this will be called from each page of eps affiliate
 * ---------------------------------------------------------- 
*/

	class Afl_epin_enque_scripts {
		public function __construct ( $page = '') {
			$enque_array['css'] = array();
			$enque_array['js'] 	= array();
			$enque_array['ajax_object'] = array();

		  	switch ( $page ) {

				  //Dashboard scripts
		  		case 'epin':
		  			$enque_array['js'] = array(
							'jquery-js' 		=> EPSAFFILIATE_EPIN_PLUGIN_ASSETS.'js/jquery.min.js',
							'epin-js' 			=> EPSAFFILIATE_EPIN_PLUGIN_ASSETS.'js/epin.js',
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