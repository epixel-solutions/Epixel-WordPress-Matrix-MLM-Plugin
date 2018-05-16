<?php

class MBB_Date extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		'std',
		'clone' => array(
			'type' 	=> 'checkbox',
			'size'	=> 'wide'
		)
	);

	public function __construct()
	{
		$js_options = Meta_Box_Attribute::get_attribute_content( 'key_value', 'js_options' );
		
		$this->advanced['js_options'] = array(
			'type' 		=> 'custom',
			'content' 	=> $js_options,
			'size'		=> 'wide'
		);

		parent::__construct();
	}
}

new MBB_Date;