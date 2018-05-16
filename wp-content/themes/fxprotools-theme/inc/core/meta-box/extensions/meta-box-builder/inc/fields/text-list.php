<?php

class MBB_TextList extends MBB_Field
{
	public function __construct()
	{
		$key_value_default = Meta_Box_Attribute::get_attribute_content( 'key-value-default' );

		$this->basic = array(
			'id',
			'name',
			'desc',
			'key_value_default' => array( 
				'type' => 'custom', 
				'content' => $key_value_default,
				'size'	=> 'wide'
			)
		);

		parent::__construct();
	}
	
}

new MBB_TextList;