<?php

class MBB_Textarea extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		'placeholder',
		'rows',
		'cols',
		'std' => array(
			'type' 	=> 'textarea',
			'size'	=> 'wide'
		),
		'clone' => array(
			'type' => 'checkbox',
			'size'	=> 'wide'
		)
	);
}

new MBB_Textarea;