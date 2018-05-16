<?php

class MBB_Select extends MBB_Field
{
	public function __construct()
	{
		$options = Meta_Box_Attribute::get_attribute_content( 'options' );

		$this->basic = array(
			'id',
			'name',
			'desc',
			'options' => array( 
				'type' => 'custom', 
				'content' => $options,
				'size'	=> 'wide'
			),
			'clone' => 'checkbox',
			'multiple' => array(
				'type' => 'checkbox',
				'label'	=> 'Multiple?',
				'attrs' => array('ng-change', 'toggleMultiple()')
			)
		);

		parent::__construct();
	}
	
}

new MBB_Select;