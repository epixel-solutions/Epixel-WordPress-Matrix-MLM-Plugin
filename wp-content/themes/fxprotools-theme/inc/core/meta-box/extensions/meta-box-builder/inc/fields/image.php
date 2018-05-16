<?php

class MBB_Image extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		'std',
		'clone' => 'checkbox',
	);
}

new MBB_Image;