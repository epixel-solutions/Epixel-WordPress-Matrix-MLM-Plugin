<?php

class MBB_Text extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		'std',
		'placeholder',
		'size',
		'clone' => array(
			'type' 	=> 'checkbox',
			'size'	=> 'wide'
		)
	);

	public function __construct()
	{
		$datalist = Meta_Box_Attribute::get_attribute_content( 'datalist' );
		
		$this->advanced['datalist'] = array(
			'type' 		=> 'custom',
			'content' 	=> $datalist,
			'size'		=> 'wide'
		);

		parent::__construct();
	}
}

new MBB_Text;