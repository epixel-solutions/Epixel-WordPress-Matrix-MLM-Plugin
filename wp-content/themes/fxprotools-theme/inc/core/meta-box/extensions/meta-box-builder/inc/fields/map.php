<?php

class MBB_Map extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		'std',
		'address_field' => array(
			'type' 	=> 'text',
			'label'	=> 'Address Field',
			'size'	=> 'wide'
		),
		'clone' => array(
			'type' => 'checkbox',
			'size'	=> 'wide'
		)
	);
}

new MBB_Map;