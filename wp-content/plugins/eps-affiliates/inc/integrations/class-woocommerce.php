<?php

/*
 * --------------------------------------------------------
 * All the related woo commerce datas
 * --------------------------------------------------------
*/
class Eps_Woocommerce {

		public function __construct ( ){
			global $woocommerce;
			$this->context = 'woocommerce';
			//add new ta in prodtc 
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'eps_product_tab' ) );
			//action settings
			add_action( 'woocommerce_product_data_panels', array( $this, 'eps_product_settings' ), 101 );
			//save the new field datas
			add_action( 'save_post', array( $this, 'eps_save_meta' ) );
			//add the product point with the order
			add_action ('woocommerce_before_checkout_process', array( $this, 'eps_add_item_meta', 10, 2) );


		}
	/**
	 * Register the product settings tab
	 *
	 * @access  public
	 * @since   1.0
	*/
		public function eps_product_tab( $tabs ) {

			$tabs['eps_affiliates'] = array(
				'label'  => __( 'EPS-Affiliates', 'EPS-Affiliates' ),
				'target' => 'eps_afl_product_settings',
				'class'  => array( ),
			);
			return $tabs;
		}
	/**
	 * Adds per-product referral rate settings input fields
	 *
	 * @access  public
	 * @since   1.0
	*/
		public function eps_product_settings() {

		global $post;

?>
		<div id="eps_afl_product_settings" class="panel woocommerce_options_panel">

			<div class="options_group">
				<p><?php _e( 'Configure affiliates point for this product', 'eps-affiliates' ); ?></p>
<?php
				woocommerce_wp_text_input( array(
										'id'          => '_eps_afl_woocommerce_product_point',
										'label'       => __( 'Affiliates Point (PV)', 'eps-affiliates' ),
										'desc_tip'    => true,
										'description' => __( 'These settings will be used to calculate affiliates personal volume per-sale. Leave blank to use default affiliate point 0.', 'eps-affiliates' )
									) );

				wp_nonce_field( 'eps_woo_product_nonce', 'eps_woo_product_nonce' );
?>
			</div>
		</div>
<?php
		}

	/**
	 * Saves per-product referral rate settings input fields
	 *
	 * @access  public
	 * @since   1.0
	*/
		public function eps_save_meta( $post_id = 0 ) {

			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			// Don't save revisions and autosaves
			if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
				return $post_id;
			}

			if( empty( $_POST['affwp_woo_product_nonce'] ) || ! wp_verify_nonce( $_POST['affwp_woo_product_nonce'], 'affwp_woo_product_nonce' ) ) {
				return $post_id;
			}

			$post = get_post( $post_id );

			if( ! $post ) {
				return $post_id;
			}

			// Check post type is product
			if ( 'product' != $post->post_type ) {
				return $post_id;
			}

			// Check user permission
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			//save the product point data 
			if( ! empty( $_POST['_eps_afl_' . $this->context . '_product_point'] ) ) {

				$rate = sanitize_text_field( $_POST['_eps_afl_' . $this->context . '_product_point'] );
				update_post_meta( $post_id, '_eps_afl_' . $this->context . '_product_point', $rate );

			} else {

				delete_post_meta( $post_id, '_eps_afl_' . $this->context . '_product_point' );

			}
		}
	/*
	 * -------------------------------------------------
	 * Addd new field with the order
	 * -------------------------------------------------
	*/
	 public function eps_add_item_meta ($item_id, $values ) {
	 	 wc_add_order_item_meta($item_id, 'product_point', '10' );
	 }
}


	new Eps_Woocommerce;
