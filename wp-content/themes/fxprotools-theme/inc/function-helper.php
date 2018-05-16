<?php
/**
 * -----------------------------
 * Fxprotools - Helper Functions
 * -----------------------------
 * All helper functions
 */

// Styled Array
function dd($array) {
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

// Get query string
function get_query_string()
{
	$string = '';
	$counter = 1;
	foreach($_GET as $key => $val){
		if($counter == 1){
			echo '?' . $key . '=' . $val;
		}else{
			echo '&' . $key . '=' . $val;
		}
		$counter++;
	}
	return $string;
}

// Get url segment
function url_segment($segment = false)
{
	if($segment == false) return false;
	$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri_segments = explode('/', $uri_path);
	return $uri_segments[$segment];
}

