<?php

class MBB_Post extends MBB_Field
{
    public function __construct()
    {
        $query_args = Meta_Box_Attribute::get_attribute_content('key_value', 'query_args');

        $field_type = '<p class="description description-thin">
							<label for="{{field.id}}_field_type">Field Type <br />
								<select ng-model="field.field_type" class="form-control" id="{{field.id}}_field_type">
									<option value="select">Select</option>
									<option value="select_advanced">Select Advanced</option>
								</select>
							</label>
						</p>';

        $post_types = mbb_get_post_types();

        if (!empty($post_types)) :
            $post_type_field = '<p class="description description-thin">
                <label for="{{field.id}}_post_type">Post Type</label><br>
                <select ng-model="field.post_type" class="form-control" id="{{field.id}}_post_type">';


            foreach ($post_types as $post_type) {
                $post_type_field .= '<option value="' . $post_type . '"> ' . $post_type . '</option>';
            }

            $post_type_field .= '</select></label></p>';
        endif;

        $this->basic = array(
            'id',
            'name',
            'desc',
            'post_type' => array(
                'type' => 'custom',
                'content' => $post_type_field
            ),
            'query_args' => array(
                'type' => 'custom',
                'size' => 'wide',
                'content' => $query_args
            ),
            'field_type' => array(
                'type' => 'custom',
                'content' => $field_type
            ),
            'placeholder',
            'parent' => 'checkbox',
        );

        $datalist = Meta_Box_Attribute::get_attribute_content('datalist');

        $this->advanced['datalist'] = array(
            'type' => 'custom',
            'content' => $datalist,
            'size' => 'wide'
        );

        parent::__construct();
    }
}

new MBB_Post;