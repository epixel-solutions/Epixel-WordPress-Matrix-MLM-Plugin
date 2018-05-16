<?php

class MBB_Wysiwyg extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		null,
		'std' => array(
			'type' => 'textarea',
			'size'	=> 'wide'
		),
		'clone' => 'checkbox',
		'raw' 	=> 'checkbox'
	);

	public function __construct()
	{
		$options = Meta_Box_Attribute::get_attribute_content( 'key_value', 'options' );
		
		$this->advanced['options'] = array(
			'type' 		=> 'custom',
			'content' 	=> $options,
			'size'		=> 'wide'
		);

		parent::__construct();
	}
}

new MBB_Wysiwyg;