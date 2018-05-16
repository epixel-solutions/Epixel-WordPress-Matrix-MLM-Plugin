<?php
/**
 * Helper and Utility Functions
 */

/**
 * Get ProPanel Template
 *
 * Templates can be overridden by creating a 'learndash-propanel' directory in the theme directory and placing
 * templates from the learndash-propanel/templates directory in it.
 *
 * @param $template_name
 *
 * @return mixed|null|void
 */
function ld_propanel_get_template( $template_name ) {
	$template_path = locate_template( 'ld-propanel/' . $template_name );

	if ( ! $template_path ) {
		$template_path = LD_PP_PLUGIN_DIR . 'templates/' . $template_name;
	}

    return apply_filters( 'learndash_propanel_template', $template_path, $template_name );
}

/**
 * Set ProPanel Report Filename
 *
 * @param string $filename_part The base filename to be used
 *
 * @return array containg two keys 
 * 			'report_filename' as the server filename and path
 * 			'report_url'  as the URL to download the file
 */
function ls_propanel_set_report_filenames( $file_part = '' ) {
	
	$files_info = array();

	$path_part = 'ld_propanel';

	if ( !empty( $file_part ) ) {

		$wp_upload_dir = wp_upload_dir();
		$wp_upload_dir['basedir'] = str_replace('\\', '/', $wp_upload_dir['basedir']);
		$wp_upload_dir['basedir'] = trailingslashit( $wp_upload_dir['basedir'] );
		$wp_upload_dir['baseurl'] = trailingslashit( $wp_upload_dir['baseurl'] );
		
		if ( wp_mkdir_p( $wp_upload_dir['basedir'] . $path_part ) !== false ) {
			// Just to ensure the directory is not readable
			file_put_contents( $wp_upload_dir['basedir'] . $path_part .'/index.php', '// nothing to see here');

			$files_info['report_file'] = $wp_upload_dir['basedir'] . $path_part .'/'. $file_part;
			$files_info['report_url'] = $wp_upload_dir['baseurl'] . $path_part .'/'. $file_part;
		}
	}
	
	return $files_info;
}

function ld_propanel_get_pager_values() {
	return (array)apply_filters('ld_propanel_per_page_array', array(5, 10, 15, 25, 35, 50, 75, 100 ) );
}

function ld_propanel_get_users_count() {
	$return_total_users = 0;

	$default_args = array(
		'role__not_in'	=>	array( 'administrator' ),
		'count_total'	=>	true,
		'fields'		=>	'ID'
	);
	
	$user_query_args = apply_filters( 'ld_propanel_overview_students_count_args', $default_args );
	if ( !empty( $user_query_args ) ) {
		$user_query = new WP_User_Query( $user_query_args );
		if ( $user_query instanceof WP_User_Query ) 
			$return_total_users = $user_query->get_total();
	}
	
	return $return_total_users;
}