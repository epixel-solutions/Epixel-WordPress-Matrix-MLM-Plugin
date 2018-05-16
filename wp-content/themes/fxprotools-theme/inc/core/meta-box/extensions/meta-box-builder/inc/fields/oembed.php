<?php

class MBB_OEmbed extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		'desc',
		'std',
		'placeholder',
		'size' 	=> 'number',
		'clone' => 'checkbox'
	);	
}

new MBB_OEmbed;