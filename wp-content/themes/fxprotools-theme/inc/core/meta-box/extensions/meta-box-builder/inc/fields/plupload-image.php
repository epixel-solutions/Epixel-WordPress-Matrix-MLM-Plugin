<?php

class MBB_PluploadImage extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		'std',
		'max_file_uploads' => 'number',
		'force_delete' => array(
			'type' 	=> 'checkbox',
			'label'	=> 'Force Delete?'
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

new MBB_PluploadImage;