<?php

class MBB_Url extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		'std',
		'placeholder',
		null,
		'clone' => 'checkbox',
	);
}

new MBB_Url;