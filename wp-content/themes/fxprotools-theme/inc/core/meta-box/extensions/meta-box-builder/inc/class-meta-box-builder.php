<?php
/**
 * Main plugin class
 *
 * @author  Tan Nguyen <tan@fitwp.com>
 * @package Meta Box
 */
class Meta_Box_Builder
{
	/**
	 * Initial scripts
	 */
	public function __construct()
	{
		$this->load_textdomain();

		// Register 'meta-box-builder' post type to store all meta boxes
		add_action( 'init', array( $this, 'register_post_type' ) );

		// Use all 'meta-box' post type to register meta box
		add_filter( 'rwmb_meta_boxes', array( $this, 'register_meta_box' ) );

		// Load 'meta-box' builder scripts on add and edit post page.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// Setup the script when WP admin is fully loaded
		add_action( 'init', array( $this, 'setup' ) );

		// Change the output of updated messages
		add_filter( 'post_updated_messages', array( $this, 'meta_box_updated_message' ), 10, 1 );

		add_filter( 'wp_insert_post_data', array( $this, 'update_meta_box' ), 10, 2 );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		add_action( 'admin_init', array( $this, 'remove_meta_box' ) );

		add_action( 'admin_menu', array( 'Meta_Box_Import', 'submenu' ) );

		add_action( 'admin_footer-edit.php', array( 'Meta_Box_Import', 'bulk_action' ) );

		add_action( 'admin_init', array( 'Meta_Box_Import', 'handle_post' ) );

        add_filter( 'redirect_post_location', array( $this, 'redirect_after_update' ) );
	}

	/**
	 * Load plugin text domain.
	 */
	public function load_textdomain()
	{
		load_plugin_textdomain( 'meta-box-builder', false, plugin_basename( dirname( dirname( __FILE__ ) ) ) . '/lang/' );
	}

	/**
	 * We don't need any meta box on meta box post type
	 */
	public function remove_meta_box()
	{
		remove_meta_box( 'submitdiv', 'meta-box', 'side' );
	}

	/**
	 * Setup when plugin fully loaded
	 *
	 * @return void
	 */
	public function setup()
	{
		add_action( 'edit_form_after_title', array( $this, 'setup_gui' ) );
	}

	/**
	 * Action when user save post. We have to manual store post_content field.
	 * Which takes value from post_excerpt and serialize
	 *
	 * @param  mixed    $data raw posted data
	 * @param  \WP_Post $post current post to save
	 *
	 * @return mixed $data after formatted
	 */
	public function update_meta_box( $data, $post )
	{
		if ( isset( $post['post_type'] ) && $post['post_type'] === 'meta-box' && ! empty( $data['post_excerpt'] ) )
		{
			$parser         = new Meta_Box_Processor( $data['post_excerpt'] );
			$meta_box       = $parser->get_meta_box();
			$meta_box['id'] = $data['post_name'];

			// Only allow Trash or Publish status
			$data['post_status']  = ( $data['post_status'] == 'trash' ) ? $data['post_status'] : 'publish';
			$data['post_content'] = @serialize( $meta_box );
		}

		return $data;
	}

    /**
     * Redirect to current tab after update
     *
     * @param $location
     * @return string URL to be redirected
     */
	public function redirect_after_update($location)
    {
        if ( ! $this->is_meta_box_post_type() )
            return $location;

        $tab = mbb_get_current_tab();

        return add_query_arg('tab', $tab, $location);
    }

	/**
	 * Notice when Meta Box plugin is not installed
	 *
	 * @return string
	 */
	public function admin_notices()
	{
		if ( class_exists( 'RW_Meta_Box' ) )
			return;

		echo '<div class="error"><p>';
		_e( 'Meta Box Builder requires Meta Box plugin to work. Please install it.', 'meta-box-builder' );
		echo '</p></div>';
	}

	/**
	 * Modify the output message of meta-box post type
	 *
	 * @param  mixed $messages Message array
	 *
	 * @return mixed $messages Message after modified
	 */
	public function meta_box_updated_message( $messages )
	{
		$messages['meta-box'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Meta Box updated.', 'meta-box-builder' ),
			2 => __( 'Custom field updated.', 'meta-box-builder' ),
			3 => __( 'Custom field deleted.', 'meta-box-builder' ),
			4 => __( 'Meta Box updated.', 'meta-box-builder' ),
			/* translators: %s: date and time of the revision */
			5 => isset( $_GET['revision'] ) ? sprintf( __( 'Meta Box restored to revision from %s', 'meta-box-builder' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __( 'Meta Box updated.', 'meta-box-builder' ),
			7 => __( 'Meta Box updated.', 'meta-box-builder' ),
			8 => __( 'Meta Box submitted.', 'meta-box-builder' ),
		);

		return $messages;
	}

	/**
	 * Load the GUI for Builder only when in meta-box post type
	 *
	 * @return void
	 */
	public function setup_gui()
	{
		if ( ! $this->is_meta_box_post_type() )
			return;

        $tab = mbb_get_current_tab();

		require MBB_INC_DIR . $tab . '-gui.php';
	}

	/**
	 * Check if current link is meta box post type or not
	 *
	 * @return boolean Meta box post type or not
	 */
	public function is_meta_box_post_type()
	{
		$screen = get_current_screen();

		return 'post' == $screen->base && 'meta-box' == $screen->post_type;
	}

	/**
	 * Only enqueue media on edit post page
	 *
	 * @return void
	 */
	public function enqueue()
	{
		if ( ! $this->is_meta_box_post_type() )
			return;

		$attrs = require MBB_INC_DIR . 'define.php';

        list( , $url ) = RWMB_Loader::get_path( dirname( dirname( __FILE__ ) ) );

		wp_enqueue_style( 'select2', RWMB_CSS_URL . 'select2/select2.css', array(), '3.2' );
		wp_enqueue_style( 'meta-box-builder', $url . 'assets/css/style.css', array(), '1.0.0' );

		wp_register_script( 'angularjs', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular.min.js', array(), '1.3.2', true );
		wp_register_script( 'angularjs-animate', 'https://code.angularjs.org/1.3.2/angular-animate.min.js', array( 'angularjs' ), '1.3.2', true );
		wp_register_script( 'angular-ui-sortable', $url . 'assets/js/sortable.js', array(), '1.3.2', true );
		wp_register_script( 'tg-dynamic-directive', $url . 'assets/js/tg.dynamic.directive.js', array(), '0.3.0', true );
		wp_register_script( 'meta-box-builder-directives', $url . 'assets/js/directives.js', array( 'angularjs' ), '2.0', true );
		wp_register_script( 'select2', RWMB_JS_URL . 'select2/select2.min.js', array( 'jquery' ), '3.2', true );

		wp_localize_script( 'meta-box-builder-directives', 'attrs', $attrs );

		// If we're updating metabox, load old data
		if ( isset( $_GET['post'] ) )
		{
			$post = get_post( $_GET['post'] );
			$meta = $post->post_excerpt;

			// Should convert to array to enqueue properly
			$meta = json_decode( $meta, true );

			// Just in case user change their post name outside this plugin
			$meta['id'] = $post->post_name;


			wp_localize_script( 'meta-box-builder-directives', 'meta', $meta );
		}

		$post_types = mbb_get_post_types();

		wp_localize_script( 'meta-box-builder-directives', 'post_types', $post_types );

		wp_enqueue_script( 'meta-box-builder', $url . 'assets/js/builder.js', array(
			'angularjs-animate', 'meta-box-builder-directives', 'select2',
			'accordion', 'angular-ui-sortable', 'tg-dynamic-directive',
		), '2.0.0', true );
	}

	/**
	 * Register post type named 'meta-box'
	 *
	 * Meta box name - Post Title
	 * Meta box id - Post Name
	 * Meta box array - Post Content
	 * Meta box json - Post Excerpt
	 *
	 * @return void
	 */
	public function register_post_type()
	{
		$post_type = 'meta-box';

		$labels = array(
			'name'               => _x( 'Meta Boxes', 'post type general name', 'meta-box-builder' ),
			'singular_name'      => _x( 'Meta Box', 'post type singular name', 'meta-box-builder' ),
			'menu_name'          => _x( 'Meta Boxes', 'admin menu', 'meta-box-builder' ),
			'name_admin_bar'     => _x( 'Meta Box', 'add new on admin bar', 'meta-box-builder' ),
			'add_new'            => _x( 'Add New', 'meta-box-builder', 'meta-box-builder' ),
			'add_new_item'       => __( 'Add New Meta Box', 'meta-box-builder' ),
			'new_item'           => __( 'New Meta Meta Box', 'meta-box-builder' ),
			'edit_item'          => __( 'Edit Meta Box', 'meta-box-builder' ),
			'view_item'          => __( 'View Meta Box', 'meta-box-builder' ),
			'all_items'          => __( 'All Meta Boxes', 'meta-box-builder' ),
			'search_items'       => __( 'Search Meta Boxes', 'meta-box-builder' ),
			'parent_item_colon'  => __( 'Parent Meta Boxes:', 'meta-box-builder' ),
			'not_found'          => __( 'No Meta Boxes found.', 'meta-box-builder' ),
			'not_found_in_trash' => __( 'No Meta Boxes found in Trash.', 'meta-box-builder' ),
		);

		$args = array(
			'labels'          => $labels,
			'descriptions'    => 'Meta Box GUI',
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'query_var'       => true,
			'rewrite'         => array( 'slug' => 'metabox' ),
			'capability_type' => 'post',
			'hierarchical'    => false,
			'menu_position'   => null,
			'supports'        => false,
			'menu_icon'       => 'dashicons-nametag',
			'capabilities'    => array(
				'edit_post'          => 'edit_theme_options',
				'read_post'          => 'edit_theme_options',
				'delete_post'        => 'edit_theme_options',
				'edit_posts'         => 'edit_theme_options',
				'edit_others_posts'  => 'edit_theme_options',
				'delete_posts'       => 'edit_theme_options',
				'publish_posts'      => 'edit_theme_options',
				'read_private_posts' => 'edit_theme_options',
			),
		);

		register_post_type( $post_type, $args );
	}

	/**
	 * Get all metabox attached to post
	 *
	 * @return array array of metabox
	 */
	public function register_meta_box( $meta_boxes )
	{
		$posts = get_posts( array(
			'post_type'      => 'meta-box',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		) );

		if ( empty( $posts ) )
		    return $meta_boxes;


        foreach ( $posts as $post )
        {
            $meta_box = @unserialize($post->post_content);

            if ( ! isset($meta_box['fields']))
               continue;

            $meta_boxes[] = $meta_box;
        }

		return $meta_boxes;
	}
}
