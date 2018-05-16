<?php

class MBB_Email extends MBB_Field
{
	public $basic = array( 'id', 'name', 'desc', 
		'std' => 'email',
		null,
		'clone' => array( 'size' => 'wide', 'type' => 'checkbox' )
	);
}

new MBB_Email;