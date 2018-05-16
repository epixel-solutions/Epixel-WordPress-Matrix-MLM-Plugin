<?php 
/*
 * ---------------------------------------------------------------
 * Validation functions for fields
 * ---------------------------------------------------------------
*/
class Form_validation {
	global $reg_errors;
	$reg_errors = new WP_Error;

	function element_null_validation($element = '', $value = ''){
		if ($value == '') {
	    $reg_errors->add($element, $element.' field is  required');
		}
	}	
}