<?php

class MBB_FieldsetText extends MBB_Field
{
	public function __construct()
	{
		$options = Meta_Box_Attribute::get_attribute_content( 'key_value', 'options' );

		$this->basic = array(
			'id',
			'name',
			'desc',
			'rows' => 'number',
			'options' => array( 
				'type' => 'custom', 
				'content' => $options,
				'size'	=> 'wide'
			)
		);

		parent::__construct();
	}
}

new MBB_FieldsetText;