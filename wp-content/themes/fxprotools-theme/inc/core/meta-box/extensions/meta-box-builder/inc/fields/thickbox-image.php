<?php

class MBB_ThickboxImage extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		'std',
		'force_delete' => array(
			'type' 	=> 'checkbox',
			'label'	=> 'Force Delete?'
		),
		'max_file_uploads' => 'number',
		'clone' => array(
			'type' 	=> 'checkbox',
			'size'	=> 'wide'
		)
	);
}

new MBB_ThickboxImage;