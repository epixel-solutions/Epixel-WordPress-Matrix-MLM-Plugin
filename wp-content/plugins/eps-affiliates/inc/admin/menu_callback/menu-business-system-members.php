<?php 

function afl_add_edit_business_system_members () {
	echo afl_eps_page_header();
	afl_add_edit_business_system_members_form();
}

/*
 * ------------------------------------------------------
 * Add new business member form
 * ------------------------------------------------------
*/
 function afl_add_edit_business_system_members_form () { 
	new Afl_enque_scripts('common');
 	
 	$table = array();
 	$add_new_button = '';   
 	afl_content_wrapper_begin();

 	$add_new_button .= '<div class="row">';
 	$add_new_button .='<button class="btn btn-bordered-primary" style= "color:#428BCA;background:#FFFFFF;border:2px solid #428BCA" data-toggle="modal" data-target="#add_new_member">';
 	$add_new_button .='<span class="fa fa-plus"></span>';
 	$add_new_button .='Create New';
 	$add_new_button .='</button>';
 	$add_new_button .='</div>';

 	echo $add_new_button;

 	$table['#prefix'] = '';
 	$table['#suffix'] = '';
 	$table['#header']	=	array('uid','Name','Role'); 
 	$table['#attributes']	=	array(
 		'class' => array('table')
 	); 

 	echo afl_render_table($table);

 	echo afl_get_template( 'eps-new-business-staff-model.php' );
 	
 	afl_content_wrapper_end();
 }
