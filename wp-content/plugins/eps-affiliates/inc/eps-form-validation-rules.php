<?php 
/*
 * ----------------------------------------------------------------
 * Validation for the fields
 * ----------------------------------------------------------------
*/
	class Form_validation_rules {

		
		//required field
		public function rule_required($name = '',$value = '', $field = ''){

			$response = array();
			if (empty($value)) {
				$response['status'] 	= 0;
				$response['message'] 	= 'Field '.$name.' field required';
				if (!empty($field) ){
					$field = str_replace('_','-',$field);
 					// wp_enqueue_script( 'jq-min', , array(), '1.0' );
 					$link = EPSAFFILIATE_PLUGIN_ASSETS."js/jquery.min.js";
 					echo "<script type='text/javascript' src='".$link."'></script>";
					echo '<script>$(function () {inform_error("'.$field.'");});</script>';
				}
			} else {
				$response['status'] 	= 1;
			}
			return $response;
		}
		//numeric validation
		public function rule_is_numeric($name = '',$value = '',$field = '') {
			if (!empty($value)) {
				if (!is_numeric($value)) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Field '.$name.' must contain a numeric number';

					if (!empty($field) ){
						$field = str_replace('_','-',$field);
 					wp_enqueue_script( 'jq-toast', EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js', array(), '1.0' );
 						$link = EPSAFFILIATE_PLUGIN_ASSETS."js/jquery.min.js";
 						echo "<script type='text/javascript' src='".$link."'></script>";
						echo '<script>$(function () {inform_error("'.$field.'");});</script>';
					}

				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
		}
		//user name legth
		public function rule_name_length ($name = '',$value = '',$field = '') {
			if (!empty($value)) {
				if ( 1 > strlen( $value ) ) {
					$response['status'] 	= 0;
					$response['message'] 	= $name.' too short. At least 4 characters is required';

					if (!empty($field) ){
						$field = str_replace('_','-',$field);
						$link = EPSAFFILIATE_PLUGIN_ASSETS."js/jquery.min.js";
 						echo "<script type='text/javascript' src='".$link."'></script>";
						echo '<script>$(function () {inform_error("'.$field.'");});</script>';
					}

				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
			
		}
		//user name legth
		public function rule_user_name_valid ($name = '',$value = '',$field = '') {
			if (!empty($value)) {
				if ( !validate_username( $value )) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Sorry, the username you entered is not valid';
					
					if (!empty($field) ){
						$field = str_replace('_','-',$field);
						
						$link = EPSAFFILIATE_PLUGIN_ASSETS."js/jquery.min.js";
 						echo "<script type='text/javascript' src='".$link."'></script>";
						echo '<script>$(function () {inform_error("'.$field.'");});</script>';
					}

				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
		}
		//user name exist
		public function rule_user_name_exists ($name = '',$value = '',$field = '') {
			if (!empty($value)) {
				if ( !username_exists( $value )) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Sorry, that '.$name.' not exists!';

					if (!empty($field) ){
						$field = str_replace('_','-',$field);
						$link = EPSAFFILIATE_PLUGIN_ASSETS."js/jquery.min.js";
 						echo "<script type='text/javascript' src='".$link."'></script>";
						echo '<script>$(function () {inform_error("'.$field.'");});</script>';
					}

				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
			
		}
		//user name already exists
		public function rule_user_already_name_exists ($name = '',$value = '',$field = '') {
			if (!empty($value)) {
				if ( username_exists( $value )) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Sorry, that username already exists!';

					if (!empty($field) ){
						$field = str_replace('_','-',$field);
						$link = EPSAFFILIATE_PLUGIN_ASSETS."js/jquery.min.js";
 						echo "<script type='text/javascript' src='".$link."'></script>";
						echo '<script>$(function () {inform_error("'.$field.'");});</script>';
					}

				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
			
		}
		//is email valid
		public function rule_valid_email ($name = '',$value = '',$field = '') {
			if (!empty($value)) {
				if ( !is_email( $value )) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Email is not valid';

					if (!empty($field) ){
						$field = str_replace('_','-',$field);
						$link = EPSAFFILIATE_PLUGIN_ASSETS."js/jquery.min.js";
 						echo "<script type='text/javascript' src='".$link."'></script>";
						echo '<script>$(function () {inform_error("'.$field.'");});</script>';
					}

				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
			
		}
		//is email exists
		public function rule_email_exists ($name = '',$value = '',$field = '') {
			if (!empty($value)) {
				if ( email_exists( $value )) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Email Already in use';

					if (!empty($field) ){
						$field = str_replace('_','-',$field);
						$link = EPSAFFILIATE_PLUGIN_ASSETS."js/jquery.min.js";
 						echo "<script type='text/javascript' src='".$link."'></script>";
						echo '<script>$(function () {inform_error("'.$field.'");});</script>';
					}

				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
			
		}
		//is  field is posative integer
		public function rule_is_numeric_posative($name = '',$value = '',$field = '') {
			if (!empty($value)) {
				if (!is_numeric($value) || $value < 0 ) {  
					$response['status'] 	= 0;
					$response['message'] 	= 'Field '.$name.' must contain a Positive number';

					if (!empty($field) ){
						$field = str_replace('_','-',$field);
						$link = EPSAFFILIATE_PLUGIN_ASSETS."js/jquery.min.js";
 						echo "<script type='text/javascript' src='".$link."'></script>";
						echo '<script>$(function () {inform_error("'.$field.'");});</script>';
					}

				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
		}
		//is  field is posative integer percentage 
		public function rule_is_numeric_percentage_posative($name = '',$value = '',$field = '') {
			if (!empty($value)) {
				$value =explode('%', $value);
				if (!is_numeric($value[0]) || $value[0] < 0 ) {  
					$response['status'] 	= 0;
					$response['message'] 	= 'Field '.$name.' must contain a Positive number';

					if (!empty($field) ){
						$field = str_replace('_','-',$field);
						$link = EPSAFFILIATE_PLUGIN_ASSETS."js/jquery.min.js";
 						echo "<script type='text/javascript' src='".$link."'></script>";
						echo '<script>$(function () {inform_error("'.$field.'");});</script>';
					}

				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
		}
	}

