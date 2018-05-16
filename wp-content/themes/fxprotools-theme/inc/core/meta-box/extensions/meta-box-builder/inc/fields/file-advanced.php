<?php

class MBB_FileAdvanced extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		'desc',
		'force_delete' => array(
			'type' => 'checkbox',
			'label'	=> 'Force Delete?'
		),
		'max_file_uploads' => array(
			'type'	=> 'number',
			'label' => 'Max File Uploads',
			'attrs' => array( 'min' => 0, 'max' => 99 )
		),
		'size' => 'number'
	);	
}

new MBB_FileAdvanced;