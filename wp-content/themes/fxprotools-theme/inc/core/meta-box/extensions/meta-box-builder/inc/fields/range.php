<?php

class MBB_Range extends MBB_Field
{
	public $basic = array('id', 'name', 'desc', 
		'min' 	=> 'number',
		'max'	=> 'number',
		'step'	=> 'number'
	);
}
new MBB_Range;