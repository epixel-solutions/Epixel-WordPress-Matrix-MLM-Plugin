<?php

class MBB_File extends MBB_Field
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
		'mime_type' => array(
			'type'	=> 'number',
			'label' => 'Mime Types',
			'attrs' => array( 'size' => 99 )
		),
		'size' => 'number',
		'clone'	=> 'checkbox',
		'force_delete' => array(
			'type' 	=> 'checkbox',
			'label' => 'Force Delete?'
		)
	);	
}

new MBB_File;