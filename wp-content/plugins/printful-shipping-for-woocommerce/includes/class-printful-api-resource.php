<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Printful_API_Resource extends WC_API_Resource {

    /** @var string $base the route base */
    protected $base = '/printful';

	public function register_routes( $routes ) {

		$routes[ $this->base . '/version' ] = array(
			array( array( $this, 'get_status' ), WC_API_Server::READABLE | WC_API_Server::ACCEPT_DATA ),
		);

		$routes[ $this->base . '/access' ] = array(
			array( array( $this, 'put_access_data' ), WC_API_Server::EDITABLE | WC_API_Server::ACCEPT_DATA ),
		);

		$routes[ $this->base . '/products/(?P<product_id>\d+)/size-chart' ] = array(
			array( array( $this, 'post_size_chart' ), WC_API_Server::EDITABLE | WC_API_Server::ACCEPT_DATA ),
		);

		return $routes;
	}

	/**
	 * Push size chart to meta property
	 * @param $product_id
	 * @param $data
	 * @return array|WP_Error
	 */
	public function post_size_chart( $product_id, $data ) {

		if ( empty( $data['size_chart'] ) ) {
			return new WP_Error( 'printful_api_size_chart_empty', __( 'No size chart was provided', 'printful' ), array( 'status' => 400 ) );
		}

		//product id is valid
		$product_id = intval( $product_id );
		if ( $product_id < 1 ) {
			return new WP_Error( 'printful_api_product_not_found', __( 'The product ID is invalid', 'printful' ), array( 'status' => 400 ) );
		}

		//product exists
		$product = get_post( $product_id );
		if ( empty( $product ) || $product->post_type != 'product' ) {
			return new WP_Error( 'printful_api_product_not_found', __( 'The product is not found', 'printful' ), array( 'status' => 400 ) );
		}

		//how about permissions?
		$post_type = get_post_type_object( $product->post_type );
		if ( ! current_user_can( $post_type->cap->edit_post, $product->ID ) ) {
			return new WP_Error( 'printful_api_user_cannot_edit_product_size_chart', __( 'You do not have permission to edit the size chart', 'printful' ), array( 'status' => 401 ) );
		}

		//lets do this
		update_post_meta( $product_id, 'pf_size_chart', htmlspecialchars( $data['size_chart'] ) );

		return array(
			'product'    => $product,
			'size_chart' => $data['size_chart'],
		);
	}

    /**
     * Allow remotely get plugin version for debug purposes
     */
	public function get_status() {

		$error = false;
		try {
			$client    = Printful_Integration::instance()->get_client();
			$storeData = $client->get( 'store' );
		} catch ( Exception $e ) {
			$error = $e->getMessage();
		}

		$checklist = Printful_Admin_Status::get_checklist();
		$checklist['overall_status'] = ( $checklist['overall_status'] ? 'OK' : 'FAIL' );

		foreach ( $checklist['items'] as $key => $item ) {

			if ( $item['status'] == Printful_Admin_Status::PF_STATUS_OK ) {
				$item['status'] = 'OK';
			} elseif ( $item['status'] == Printful_Admin_Status::PF_STATUS_WARNING ) {
				$item['status'] = 'WARNING';
			} else {
				$item['status'] = 'FAIL';
			}

			$checklist['items'][ $key ] = $item;
		}

		return array(
			'version'          => Printful_Base::VERSION,
			'api_key'          => Printful_Integration::instance()->get_option('printful_key'),
			'store_id'         => ! empty( $storeData['id'] ) ? $storeData['id'] : false,
			'error'            => $error,
			'status_checklist' => $checklist,
		);
	}

	/**
	 * @param $data
	 *
	 * @return array
	 */
	public function put_access_data( $data ) {

		$error = false;

		$apiKey  = $data['accessKey'];
		$storeId = $data['storeId'];

		$option  = Printful_Integration::instance()->get_option( 'printful_settings', array() );
		$storeId = intval( $storeId );

		if ( ! is_string( $apiKey ) || strlen( $apiKey ) == 0 || $storeId == 0 ) {
			$error = 'Failed to update access data';
		}

		$option['printful_key']      = $apiKey;
		$option['printful_store_id'] = $storeId;

		Printful_Integration::instance()->update_settings( $option );

		return array(
			'error' => $error,
		);
	}

}