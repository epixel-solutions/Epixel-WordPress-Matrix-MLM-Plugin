<?php

class MBB_Number extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		'std' => 'number',
		'placeholder',
		null,
		'min' => 'number',
		'max' => 'number',
		'clone' => array(
			'type' => 'checkbox',
			'size'	=> 'wide'
		)
	);
}

new MBB_Number;