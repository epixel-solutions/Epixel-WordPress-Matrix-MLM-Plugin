<?php

class MBB_ImageSelect extends MBB_Field
{
	public function __construct()
	{
		$options = Meta_Box_Attribute::get_attribute_content( 'options' );

		$this->basic = array(
			'id',
			'name',
			'desc',
			'multiple' => 'checkbox',
			'options' => array( 
				'type' => 'custom', 
				'content' => $options,
				'size'	=> 'wide'
			)
		);

		parent::__construct();
	}
	
}

new MBB_ImageSelect;