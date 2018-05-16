<?php

class MBB_Checkbox extends MBB_Field
{
	public $basic = array( 
		'id', 
		'name',
		'desc',
		null,
		'clone' => 'checkbox',
		'std'	=> array( 
			'type' 	=> 'checkbox', 
			'label' => 'Checked?' 
		)
	);
}

new MBB_Checkbox;