<?php

class MBB_ImageAdvanced extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		'desc',
		'max_file_uploads' => array(
			'type'	=> 'number',
			'label' => 'Max File Uploads',
			'attrs' => array( 'min' => 0, 'max' => 99 )
		),
		'force_delete' => array(
			'type' 	=> 'checkbox',
			'label' => 'Force Delete?'
		),
		'clone'	=> 'checkbox',
	);	
}

new MBB_ImageAdvanced;