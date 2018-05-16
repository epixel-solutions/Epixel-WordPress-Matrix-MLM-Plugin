<?php

class MBB_Radio extends MBB_Field
{
	public function __construct()
	{
		$options = Meta_Box_Attribute::get_attribute_content( 'options' );

		$this->basic = array(
			'id',
			'name',
			'desc',
			null,
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

new MBB_Radio;