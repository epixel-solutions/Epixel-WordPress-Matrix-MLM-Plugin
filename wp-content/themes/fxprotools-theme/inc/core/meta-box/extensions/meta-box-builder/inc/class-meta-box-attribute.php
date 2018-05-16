<?php

/**
 * Render Html for Attribute
 */
class Meta_Box_Attribute
{
	// Define labels for displaying on the builder
	// We just have to define some special labels, the remaining is auto generate by str_title function
	static $labels = array(
		'id' 			=> 'ID',
		'desc'			=> 'Description',
		'attrs'			=> 'Attributes',
		'std'			=> 'Default',
		'query_args'	=> 'Query Arguments',
		'options.args'  => 'Option Arguments'
	);

	/**
	 * Generate Input Content
	 * @param  string $name  Name of the input
	 * @param  string $label Label of the input
	 * @param  array  $attrs Other attributes
	 * @param  string $type  input type
	 * @return string html
	 */
	public static function input( $name, $label = null, $attrs = array(), $type = 'text' )
	{
		// Turn key => value array to key="value" html output
		$attrs = self::build_attributes( $attrs );
		
		// If label is not defined 
		$label = ( $label != null ) ? $label : $name;

		// Because field name is not Capitalized, so we have to convert it
		if ( array_key_exists( $label, self::$labels ) )
			$label = self::$labels[$label];
		else
			$label = str_title( $label );

		$classes = ( $type !== 'checkbox' ) ? 'form-control field-'.$name : '';
		
		$br = ( $type !== 'checkbox' ) ? '<br>' : '';

		$output = '
			<label for="{{field.id}}_' . $name .'">' . $label . $br . '
				<input type="'. $type .'" ng-model="field.' . $name .'" id="{{field.id}}_' . $name . '" class="' . $classes . '"' . $attrs . ' />
			</label>
		';

		return $output;
	}

	public static function text( $name, $label = null, $attrs = array() )
	{
		return self::input( $name, $label, $attrs );
	}

	public static function email( $name, $label = null, $attrs = array() )
	{
		return self::input( $name, $label, $attrs, 'email' );
	}

	public static function number( $name, $label = null, $attrs = array() )
	{
		return self::input( $name, $label, $attrs, 'number' );
	}

	public static function range( $name, $label = null, $attrs = array() )
	{
		return self::input( $name, $label, $attrs, 'range' );
	}

	public static function checkbox( $name, $label = null, $attrs = array() )
	{
		$attrs['ng-true-value'] = 1;
		$attrs['ng-false-value'] = 0;

		return self::input( $name, $label, $attrs, 'checkbox' );
	}

	public static function build_attributes( $attrs = array() )
	{
		$attributes = '';

		if ( ! empty( $attrs ) )
		{
			foreach ( $attrs as $k => $v )
			{
				$attributes .= " {$k}=\"{$v}\"";
			}
		}

		return $attributes;
	}

	public static function textarea( $name, $label = null, $attrs = array() )
	{
		$attributes = self::build_attributes( $attrs );

		$label = ( $label === null ) ? $name : $label;

		$output = '
			<label for="{{field.id}}_'.$name.'">'.$label.'</label>
			<textarea ng-model="field.'.$name.'" id="{{field.id}}_'.$name.'" class="form-control"'.$attributes.'></textarea>';
		
		return $output;
	}

	public static function get_attribute_content( $attribute, $replace = '' )
	{
		return mbb_get_attribute_content( $attribute, $replace );
	}
}