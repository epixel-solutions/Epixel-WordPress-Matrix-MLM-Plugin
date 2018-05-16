<?php

class MBB_Color extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		'std',
		'size' 	=> 'number',
		'clone' => 'checkbox',
	);
}

new MBB_Color;