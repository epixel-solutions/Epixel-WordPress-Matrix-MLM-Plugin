<?php

class Meta_Box_Import
{
	/**
	 * Create submenu for Import feature under Meta Boxes menu
	 *
	 * @return void
	 */
	public static function submenu()
	{
		add_submenu_page( 'edit.php?post_type=meta-box', __( 'Import', 'meta-box-builder' ), __( 'Import', 'meta-box-builder' ), 'manage_options', 'meta-box-import', array( __CLASS__, 'page' ) );
	}

	/**
	 * By default, WP doesn't support add bulk action hook, so this is handly JS to create new <option> tag into bulk actions select
	 *
	 * @return void
	 */
	public static function bulk_action()
	{
		if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'meta-box' ) :
			?>
			<script type="text/javascript">
				jQuery( document ).ready( function ( $ )
				{
					$( '<option>' ).val( 'export_metabox' ).text( 'Export' )
						.appendTo( "select[name='action'], select[name='action2']" );
				} );
			</script>
			<?php
		endif;
	}

	/**
	 * Render Meta Box Import page
	 *
	 * @return String Html Content
	 */
	public static function page()
	{
		?>
		<div class="wrap">
			<h2><?php _e('Import Meta Boxes', 'meta-box-builder'); ?></h2>
			<?php if ( isset( $_GET['success'] ) ) : ?>
				<div class="updated">
					<p><?php _e( 'Tigh tigh tigh! Meta Boxes has imported successfully!', 'meta-box-builder' ); ?></p>
				</div>
			<?php endif; ?>
			<p><?php _e( 'Choose a <code>.dat</code> file to upload, then click <strong>Upload file and import.</strong>', 'meta-box-builder' ); ?></p>

			<form enctype="multipart/form-data" id="import-upload-form" method="post" class="wp-upload-form" action="edit.php?post_type=meta-box&amp;page=meta-box-import">
				<p>
					<label for="upload"><?php _e( 'Choose a file from your computer:', 'meta-box-builder' ); ?></label>
					<input type="file" id="upload" name="import">
					<input type="hidden" name="uploadnonce" value="<?php echo wp_create_nonce( 'uploadnonce' ); ?>">
				</p>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button" value="<?php esc_attr_e( 'Upload file and import', 'meta-box-builder' ); ?>"></p>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle Import / Export action
	 *
	 * @return void
	 * @since 1.2.1
	 */
	public static function handle_post()
	{
	    $action = '';

        if ((isset($_REQUEST['action']) && $_REQUEST['action'] == 'export_metabox') || (isset($_REQUEST['action2']) && $_REQUEST['action2'] == 'export_metabox'))
            $action = 'export_metabox';

        // Handle Export action
		if ( $action === 'export_metabox' && ! empty( $_REQUEST['post'] ) )
		{
			$posts = $_REQUEST['post'];

			$meta_boxes = get_posts( array(
				'post_type' => 'meta-box',
				'post__in'  => $posts
			) );

			$ready = array();

			foreach ( $meta_boxes as $meta_box )
			{
				$ready[] = base64_encode( serialize( $meta_box ) );
			}

			$ready = serialize( $ready );
			$upload_dir = wp_upload_dir();
			$upload_dir = $upload_dir['basedir'];
			$upload_file = $upload_dir . '/meta-boxes.dat';

			$handle = fopen( $upload_file, "w" );
			fwrite( $handle, $ready );
			fclose( $handle );

			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Disposition: attachment; filename=' . basename( 'meta-boxes.dat' ) );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate' );
			header( 'Pragma: public' );
			header( 'Content-Length: ' . filesize( $upload_file ) );
			readfile( $upload_file );
			exit;
		}

		// Handle Import action
		if ( ! empty( $_POST ) && ! empty( $_FILES ) && $_GET['post_type'] === 'meta-box' && ends_with( $_FILES['import']['name'], '.dat' ) )
		{
			$uploadnonce = $_POST['uploadnonce'];

			if ( wp_verify_nonce( $uploadnonce, 'uploadnonce' ) )
			{
				$content = file_get_contents( $_FILES['import']['tmp_name'] );

				$meta_boxes = unserialize( $content );

				foreach ( $meta_boxes as $meta_box )
				{
					$meta_box_content = unserialize( base64_decode( $meta_box ) );
					$meta_box_content = (array) $meta_box_content;

					unset( $meta_box_content['ID'] );

					wp_insert_post( $meta_box_content );
				}

				wp_redirect( admin_url( '/edit.php?post_type=meta-box&page=meta-box-import&success' ) );
				die;
			}
		}
	}
}
