<?php

class MBB_Field
{
	public $basic = array( 'id', 'name', 'desc' );

	public $advanced = array();

	public function __construct()
	{
        echo '<ul class="ui-tabs">
                <li class="ui-tab-item {{ pane == \'general\' ? \'active\' : \'\' }}"><a role="button" href="#" ng-click="setActivePane(\'general\')">General</a></li>
                <li class="ui-tab-item {{ pane == \'advanced\' ? \'active\' : \'\' }}"><a role="button" href="#" ng-click="setActivePane(\'advanced\')">Advanced</a></li>
              </ul>';

        echo '<div class="ui-pane pane-general" ng-show="pane == \'general\'">';
		echo $this->get_fields( $this->basic );
        echo '</div>';

		if ( ! is_array( $this->advanced ) )
			return;


		echo 	'<div class="ui-pane pane-advanced" ng-show="pane == \'advanced\'">';

            $attrs = Meta_Box_Attribute::get_attribute_content( 'key_value', 'attrs' );

            // Add a class section with full size
            $this->advanced['class'] = array( 'size' => 'wide' );

            // Add a custom attribute section
            $this->advanced['attrs'] = array(
                'type' 		=> 'custom',
                'content' 	=> $attrs
            );

            // Add conditional logic section
            $conditional_logic = Meta_Box_Attribute::get_attribute_content( 'conditional_logic' );

            $this->advanced['conditional_logic'] = array(
                'type' 		=> 'custom',
                'content' 	=> $conditional_logic
            );

            // Add columns section
            $this->advanced['columns'] = array(
                'type' => 'number',
                'attrs' => array(
                    'min' => 1,
                    'max' => 12
                )
            );

            echo $this->get_fields( $this->advanced );

		echo	'</div>';
	}

	public function get_fields( $fields )
	{
		$output = '';

		foreach ( $fields as $index => $field )
		{
			// Clearfix
			if ( is_null( $index ) || is_null( $field ) )
			{
				$output .= '<div class="clear"></div>';
				continue;
			}

			if ( is_numeric( $index ) )
			{
				// Normal text field, normal size
				$output .= '<p class="description description-wide">';
					$output .= Meta_Box_Attribute::text( $field );
				$output .= '</p>';

				continue;
			}

			if ( is_string( $field ) )
			{
				$output .= '<p class="description description-wide">';
					$output .= Meta_Box_Attribute::$field( $index );
				$output .= '</p>';
			}

			if ( is_array( $field ) && ! empty( $field ) )
			{
				$size 	= isset( $field['size'] ) ? $field['size'] : 'wide';
				$label 	= isset( $field['label'] ) ? $field['label'] : null;
				$attrs 	= isset( $field['attrs'] ) ? $field['attrs'] : array();
				$type 	= isset( $field['type'] ) ? $field['type'] : 'text';
				
				$output .= "<p class='description description-$size'>";

				if ( $type === 'custom' )
					$output .= $field['content'];
				else
					$output .= Meta_Box_Attribute::$type( $index, $label, $attrs );
				$output .= '</p>';
			}
		}
		
		return $output;
	}
}