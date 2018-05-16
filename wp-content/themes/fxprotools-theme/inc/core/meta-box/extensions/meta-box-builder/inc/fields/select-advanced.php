<?php

class MBB_SelectAdvanced extends MBB_Field
{
	public function __construct()
	{
		$options = Meta_Box_Attribute::get_attribute_content( 'options' );

		$this->basic = array(
			'id',
			'name',
			'desc',
			'placeholder',
			'size' => 'number',
			'options' => array( 
				'type' => 'custom', 
				'content' => $options,
				'size'	=> 'wide'
			),
			'clone' => 'checkbox',
			'multiple' => 'checkbox'
		);

		$js_options = Meta_Box_Attribute::get_attribute_content( 'key_value', 'js_options' );
		
		$this->advanced['js_options'] = array(
			'type' 		=> 'custom',
			'content' 	=> $js_options,
			'size'		=> 'wide'
		);
		parent::__construct();
	}
	
}

new MBB_SelectAdvanced;