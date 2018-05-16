<?php

class MBB_Group extends MBB_Field 
{
	public $basic = array(
		'id', 
		'name', 
		'clone' 		=> 'checkbox', 
		'sort_clone' 	=> 'checkbox'
	);
}

new MBB_Group;