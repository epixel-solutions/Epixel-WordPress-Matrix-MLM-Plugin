<?php 

/**
 * ------------------------------------------------------------------------
 * Get Afl dashboard menu items.
 * ------------------------------------------------------------------------
 *
 * @since 2.6.0
 * @return array
 * ------------------------------------------------------------------------
 */
	function eps_get_account_menu_items() {
		$menus = array();
		$menus['dashboard'] = array(
			'#title' => __('Dashboard'),
			'#link'  => 'templates/dashboard.php',
			'#attributes' => array(
				'class' => array(''),
			),
			'#icon' => '<i class="fa fa-user"></i>',
			'#parent' => '',
		);
		$menus['network'] = array(
			'#title' => __('Network'),
			'#link'  => 'templates/network.php',
			'#attributes' => array(
				'class' => array(''),
			),
			'#icon' => '<i class="fa fa-users"></i>',
			'#childrens' => array(
				'network_explorer' => array(
					'#title' => __('Network Explorer'),
					'#link'  => 'templates/network_exporer.php',
					'#attributes' => array(
						'class' => array(''),
					),
				),
				'add_new_member' => array(
					'#title' => __('Add new Member'),
					'#link'  => 'templates/add_new_member.php',
					'#attributes' => array(
						'class' => array(''),
					),
				),
				'downline_members' => array(
					'#title' => __('Downline members'),
					'#link'  => 'templates/downline_members.php',
					'#attributes' => array(
						'class' => array(''),
					),
				),
				'genealogy_tree' => array(
					'#title' => __('Genealogy Tree'),
					'#link'  => 'templates/genealogy_tree.php',
					'#attributes' => array(
						'class' => array(''),
					),
				),
				'uplines_tree' => array(
					'#title' => __('My Uplines Tree'),
					'#link'  => 'templates/uplines_tree.php',
					'#attributes' => array(
						'class' => array(''),
					),
				),
			),
		);
		return apply_filters( 'eps_get_account_menu_items', $menus );
	}

/**
 * ------------------------------------------------------------------------
 * Get account menu item classes.
 * ------------------------------------------------------------------------
 *
 * @since 2.6.0
 * @param string $endpoint
 * @return string
 * ------------------------------------------------------------------------
 */
	function eps_get_account_menu_item_classes( $endpoint ) {
		global $wp;

		$classes = array(
			'woocommerce-MyAccount-navigation-link',
			'woocommerce-MyAccount-navigation-link--' . $endpoint,
		);

		// Set current item class.
		$current = isset( $wp->query_vars[ $endpoint ] );
		if ( 'dashboard' === $endpoint && ( isset( $wp->query_vars['page'] ) || empty( $wp->query_vars ) ) ) {
			$current = true; // Dashboard is not an endpoint, so needs a custom check.
		}

		if ( $current ) {
			$classes[] = 'is-active';
		}

		$classes = apply_filters( 'woocommerce_account_menu_item_classes', $classes, $endpoint );

		return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
	}
/**
 * ------------------------------------------------------------------------
 * Get account endpoint URL.
 * ------------------------------------------------------------------------
 *
 * @since 2.6.0
 * @param string $endpoint
 * @return string
 * ------------------------------------------------------------------------
 */
	function eps_get_account_endpoint_url( $endpoint ) {
		
		if ( 'dashboard' === $endpoint ) {
			return eps_get_page_permalink( 'eps-affiliates' );
		}

		return eps_get_endpoint_url( $endpoint, '', eps_get_page_permalink( 'eps-affiliates' ) );
	}
/**
 * ------------------------------------------------------------------------
 * Get endpoint URL.
 * ------------------------------------------------------------------------
 *
 * Gets the URL for an endpoint, which varies depending on permalink settings.
 *
 * @param  string $endpoint
 * @param  string $value
 * @param  string $permalink
 *
 * @return string
 * ------------------------------------------------------------------------
*/
	function eps_get_endpoint_url( $endpoint, $value = '', $permalink = '' ) {
		if ( ! $permalink ) {
			$permalink = get_permalink();
		}

		// Map endpoint to options
		$endpoint = ! empty( WC()->query->query_vars[ $endpoint ] ) ? WC()->query->query_vars[ $endpoint ] : $endpoint;
		$value    = ( get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ) === $endpoint ) ? wc_edit_address_i18n( $value ) : $value;

		if ( get_option( 'permalink_structure' ) ) {
			if ( strstr( $permalink, '?' ) ) {
				$query_string = '?' . parse_url( $permalink, PHP_URL_QUERY );
				$permalink    = current( explode( '?', $permalink ) );
			} else {
				$query_string = '';
			}
			$url = trailingslashit( $permalink ) . $endpoint . '/' . $value . $query_string;
		} else {
			$url = add_query_arg( $endpoint, $value, $permalink );
		}

		return apply_filters( 'eps_get_endpoint_url', $url, $endpoint, $value, $permalink );
	}
/**
 * ------------------------------------------------------------------------
 * Retrieve page permalink.
 * ------------------------------------------------------------------------
 *
 * @param string $page
 * @return string
 *
 * ------------------------------------------------------------------------
*/
	function eps_get_page_permalink( $page ) {
		$page_id   = eps_get_page_id( $page );
		$permalink = 0 < $page_id ? get_permalink( $page_id ) : get_home_url();
		return apply_filters( 'eps_get_' . $page . '_page_permalink', $permalink );
	}
/**
 * ------------------------------------------------------------------------
 * Retrieve page ids - used for afl dashboard found.
 * ------------------------------------------------------------------------
 *
 * @param string $page
 * @return int
 * ------------------------------------------------------------------------
*/
	function eps_get_page_id( $page ) {
		$page = get_page_by_title( $page );
		$page = $page->ID;

		return $page ? absint( $page ) : 0;
	}