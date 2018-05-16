<?php

class MBB_User extends MBB_Field
{
	public function __construct()
	{
		$query_args = Meta_Box_Attribute::get_attribute_content( 'key_value', 'query_args' );
		
		$field_type = '<label for="{{field.id}}_field_type">Field Type <br />
					<select ng-model="field.field_type" class="form-control" id="{{field.id}}_field_type">
						<option value="select">Select</option>
						<option value="select_advanced">Select Advanced</option>
					</select>
				</label>';

		$this->basic = array( 
			'id', 
			'name',
			'desc',
			'std',
			'query_args' => array(
				'type' => 'custom',
				'size'	=> 'wide',
				'content' => $query_args
			),
			'field_type' => array(
				'type' => 'custom',
				'content' => $field_type
			)
		);

		parent::__construct();
	}
}

new MBB_User;