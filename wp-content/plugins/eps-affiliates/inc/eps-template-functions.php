<?php
if ( ! function_exists( 'eps_account_content' ) ) {

	/**
	 * Dashboard content output.
	 */
	function eps_account_content() {
		global $wp;

		foreach ( $wp->query_vars as $key => $value ) {
			// Ignore pagename param.
			if ( 'pagename' === $key ) {
				continue;
			}

			if ( has_action( 'eps_account_' . $key . '_endpoint' ) ) {
				do_action( 'eps_account_' . $key . '_endpoint', $value );
				return;
			}
		}

		// No endpoint found? Default to dashboard.
		afl_get_template( 'eps-affiliates/dashboard.php', array(
			'current_user' => get_user_by( 'id', get_current_user_id() ),
		) );
	}
}