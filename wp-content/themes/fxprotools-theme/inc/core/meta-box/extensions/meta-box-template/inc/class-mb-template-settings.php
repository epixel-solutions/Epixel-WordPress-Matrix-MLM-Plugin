<?php
/**
 * Meta Box Template Settings
 *
 * This class handles plugin settings, including adding settings page, show fields, save settings
 *
 * @package Meta Box Template
 * @since   0.1.0
 */

/**
 * Meta Box Template Settings class
 *
 * @package Meta Box Template
 * @since   0.1.0
 */
class MB_Template_Settings
{
	/**
	 * Constructor
	 * Add hooks
	 */
	public function __construct()
	{
		// Register plugin setting
		add_action( 'admin_init', array( $this, 'register_setting' ) );

		// Add plugin menu
		add_action( 'admin_menu', array( $this, 'add_plugin_menu' ) );
	}

	/**
	 * Register plugin setting, settings section and fields using Settings API
	 *
	 * @return void
	 */
	public function register_setting()
	{
		register_setting( 'meta_box_template', 'meta_box_template', array( $this, 'sanitize' ) );

		add_settings_section( 'general', '', '__return_null', 'meta-box-template' );
		add_settings_field( 'template', __( 'Enter template:', 'meta-box-template' ), array( $this, 'template_field' ), 'meta-box-template', 'general' );
		add_settings_field( 'file', __( 'Or specify path to config file:', 'meta-box-template' ), array( $this, 'file_field' ), 'meta-box-template', 'general' );
	}

	/**
	 * Add plugin menu under Settings WordPress menu
	 *
	 * @return void
	 */
	public function add_plugin_menu()
	{
		$page = add_options_page( __( 'Meta Box Template', 'meta-box-template' ), __( 'Meta Box Template', 'meta-box-template' ), 'manage_options', 'meta-box-template', array( $this, 'show_page' ) );
		add_action( "admin_print_styles-$page", array( $this, 'enqueue' ) );
	}

	/**
	 * Enqueue scripts for plugin settings page
	 *
	 * @return void
	 */
	public function enqueue()
	{
		wp_register_script( 'behave', MB_TEMPLATE_URL . 'js/behave.js', '', '1.5', true );
		wp_enqueue_script( 'meta-box-template', MB_TEMPLATE_URL . 'js/script.js', array( 'behave' ), '', true );
	}

	/**
	 * Show content of settings page
	 * Content is added via Settings API
	 *
	 * @return void
	 */
	public function show_page()
	{
		?>
		<div class="wrap">
			<h2><?php _e( 'Meta Box Template' ); ?></h2>

			<form action="options.php" method="post">

				<?php settings_fields( 'meta_box_template' ); ?>

				<?php do_settings_sections( 'meta-box-template' ); ?>

				<?php submit_button( __( 'Save Changes', 'meta-box-template' ) ); ?>

			</form>
		</div>
	<?php
	}

	/**
	 * Show template textarea field
	 *
	 * @return void
	 */
	public function template_field()
	{
		$option = get_option( 'meta_box_template' );
		$source = isset( $option['source'] ) ? $option['source'] : '';
		?>
		<textarea class="code large-text" rows="20" name="meta_box_template[source]" id="meta-box-template"><?php echo esc_textarea( $source ); ?></textarea>
		<p class="description">
			<?php
			printf(
				__( 'Supports YAML format. See <a href="%s" target="_blank">documentation</a>.', 'meta-box-template' ),
				'http://metabox.io/docs/meta-box-template/'
			);
			?>
		</p>
	<?php
	}

	/**
	 * Show file input field
	 *
	 * @return void
	 */
	public function file_field()
	{
		$option = get_option( 'meta_box_template' );
		$file   = isset( $option['file'] ) ? $option['file'] : '';
		?>
		<input type="text" class="large-text" name="meta_box_template[file]" value="<?php echo esc_attr( $file ); ?>">
		<p class="description">
			<?php _e( 'Please enter absolute path to <code>.yaml</code> file. Supports following variables (no trailing slash):', 'meta-box-template' ); ?>
		</p>
		<ul>
			<li>
				<code>%wp-content%</code> -
				<span class="description"><?php _e( 'Path to <code>wp-content</code> directory', 'meta-box-template' ); ?></span>
			</li>
			<li>
				<code>%plugins%</code> -
				<span class="description"><?php _e( 'Path to <code>wp-content/plugins</code> directory', 'meta-box-template' ); ?></span>
			</li>
			<li>
				<code>%themes%</code> -
				<span class="description"><?php printf( __( 'Path to <code>wp-content/themes</code> directory. Same as <a href="%s">get_theme_root()</a> function', 'meta-box-template' ), 'http://codex.wordpress.org/Function_Reference/get_theme_root' ); ?></span>
			</li>
			<li>
				<code>%template%</code> -
				<span class="description"><?php printf( __( 'Path to current theme directory. Same as <a href="%s">get_template_directory()</a> function', 'meta-box-template' ), 'http://codex.wordpress.org/Function_Reference/get_template_directory' ); ?></span>
			</li>
			<li>
				<code>%stylesheet%</code> -
				<span class="description"><?php printf( __( 'Path to current child theme directory. Same as <a href="%s">get_stylesheet_directory()</a> function', 'meta-box-template' ), 'http://codex.wordpress.org/Function_Reference/get_stylesheet_directory' ); ?></span>
			</li>
		</ul>
	<?php
	}

	/**
	 * Sanitize plugin option
	 * Parse the template to meta box array which will be used later to register meta boxes.
	 * That will make the parse job run only once, thus improve performance
	 *
	 * @param array $option Plugin option
	 *
	 * @return array
	 */
	public function sanitize( $option )
	{
		/**
		 * Parse the plugin template into meta box array
		 * Use user input first, then config file
		 */
		$input = $option['source'];
		if ( ! $input && $option['file'] )
		{
			$input = strtr( $option['file'], array(
				'%wp-content%' => WP_CONTENT_DIR,
				'%plugins%'    => WP_PLUGIN_DIR,
				'%themes%'     => get_theme_root(),
				'%template%'   => get_template_directory(),
				'%stylesheet%' => get_stylesheet_directory(),
			) );
		}

		// Store parsed array into plugin option for future reference, e.g. no need to parse again
		$option['parse'] = $this->parse( $input );

		return $option;
	}

	/**
	 * Parse YAML string into an array
	 * Uses native PHP function is possible with fallback to Spyc
	 *
	 * @param string $input Template text or absolute path to config file
	 * @return array
	 */
	public function parse( $input )
	{
		/**
		 * Use native PHP function if possible
		 * Requires PECL yaml package >= 0.4.0 installed
		 *
		 * @link http://php.net/manual/en/function.yaml-parse.php
		 */
		if ( function_exists( 'yaml_parse' ) )
			return yaml_parse( $input );

		/**
		 * Use Spyc library to parse YAML string as a fallback
		 *
		 * @link https://github.com/mustangostang/spyc
		 */
		if ( ! class_exists( 'Spyc' ) )
			require MB_TEMPLATE_DIR . 'lib/Spyc.php';

		return Spyc::YAMLLoad( $input );
	}
}
