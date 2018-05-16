<?php

class MBB_Taxonomy extends MBB_Field
{
	public function __construct()
	{
		$query_args = Meta_Box_Attribute::get_attribute_content( 'key_value', 'query_args' );
		
		$taxonomy = '
			<label for="{{field.id}}_taxonomy">Taxonomy</label>
			<select ng-model="field.taxonomy" class="form-control" id="{{field.id}}_taxonomy">';

		foreach ( mbb_get_taxonomies() as $tax ) :
			$taxonomy .= '<option value="' . $tax . '">'. $tax . '</option>';
		endforeach;

		$taxonomy .= '</select>';

		$field_type = '<label for="{{field.id}}_type">Field Type <br />
				<select ng-model="field.field_type" class="form-control" id="{{field.id}}_type">
					<option value="select">Select</option>
					<option value="select_tree">Select Tree</option>
					<option value="select_advanced">Select Advanced</option>
					<option value="checkbox_list">Checkbox List</option>
					<option value="checkbox_tree">Checkbox Tree</option>
				</select>
			</label>';

		$this->basic = array( 
			'id', 
			'name',
			'desc',
			'taxonomy' => array(
				'type' 		=> 'custom',
				'content' 	=> $taxonomy
			),
			'field_type' => array(
				'type' => 'custom',
				'content' => $field_type
			),
			'clone' => 'checkbox',
			'parent' => 'checkbox'
		);
		
		$this->advanced['query_args'] = array(
			'type' 		=> 'custom',
			'content' 	=> $query_args,
			'size'		=> 'wide'
		);

		parent::__construct();
	}
}

new MBB_Taxonomy;