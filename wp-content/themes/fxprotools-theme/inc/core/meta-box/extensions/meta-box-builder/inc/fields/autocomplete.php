<?php

class MBB_AutoComplete extends MBB_Field
{
	public function __construct()
	{
		$options = Meta_Box_Attribute::get_attribute_content( 'options' );

		$this->basic = array(
			'id',
			'name',
			'desc',
			'size' => 'number',
			'options' => array( 
				'type' => 'custom', 
				'content' => $options,
				'size'	=> 'wide'
			),
			'clone' => array(
				'type' => 'checkbox',
				'size' => 'wide'
			)
		);

		parent::__construct();
	}
}

new MBB_AutoComplete;