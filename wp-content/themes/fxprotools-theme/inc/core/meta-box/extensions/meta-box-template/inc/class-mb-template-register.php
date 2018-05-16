<?php
/**
 * Meta Box Template Register class
 * This class uses the plugin settings and register meta boxes
 *
 * @package Meta Box Template
 * @since   0.1.0
 */

/**
 * Meta Box Template Register class
 *
 * @package Meta Box Template
 * @since   0.1.0
 */
class MB_Template_Register
{
	/**
	 * Constructor
	 * Add hook to register meta boxes
	 */
	public function __construct()
	{
		add_filter( 'rwmb_meta_boxes', array( $this, 'register_meta_boxes' ) );
	}

	/**
	 * Register meta boxes
	 *
	 * @param array $meta_boxes
	 *
	 * @return array
	 */
	public function register_meta_boxes( $meta_boxes )
	{
		$option = get_option( 'meta_box_template' );
		if ( empty( $option['parse'] ) || ! is_array( $option['parse'] ) )
			return $meta_boxes;

		// If the template is for single meta box (the sign is 'title' attribute), convert it into an array
		$new_meta_boxes = ! empty( $option['parse']['title'] ) ? array( $option['parse'] ) : $option['parse'];

		// Register meta boxes
		foreach ( $new_meta_boxes as $new_meta_box )
		{
			$meta_boxes[] = $new_meta_box;
		}

		return $meta_boxes;
	}

}
