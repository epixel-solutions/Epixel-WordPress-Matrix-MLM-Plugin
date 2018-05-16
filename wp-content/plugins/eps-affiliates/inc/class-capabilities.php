<?php
/**
 * ----------------------------------------------------------------------------
 * Roles and Capabilities
 * ----------------------------------------------------------------------------
 *
 * @package     Eps_affiliates
 * @subpackage  Classes/Roles
 * @copyright   Copyright (c) 2017, Epixelsolutions
 * @since       1.0
*/

/**
 * ----------------------------------------------------------------------------
 * EPS-Affiliates Roles
 * ----------------------------------------------------------------------------
 *
 * This class handles the role creation and assignment of capabilities (permissions)
 * for those roles.
 * @since 1.0
 *
 */
	class Eps_affiliates_Capabilities {

	/**
 	 * ----------------------------------------------------------------------------
	 * Get things going
	 *
	 * @since 1.0
	 *
	*/
		public function __construct() {
			add_filter( 'map_meta_cap', array( $this, 'map_meta_caps' ), 10, 4 );
		}

	/**
 	 * ----------------------------------------------------------------------------
	 * Add new capabilities (Permission )
 	 * ----------------------------------------------------------------------------
	 *
	 * @access public
	 * @since  1.0
	 * @global obj $wp_roles
	 * @return void
	 *
	*/
		public function add_caps() {
			global $wp_roles;

			if ( class_exists('WP_Roles') ) {
				if ( ! isset( $wp_roles ) ) {
					$wp_roles = new WP_Roles();
				}
			}

			if ( is_object( $wp_roles ) ) {
				/*
				 * -------------------------------------------------------
				 * Give all custom permission to the administrator
				 * -------------------------------------------------------
				*/
					$all_permissions = custom_permissions();
					foreach ($all_permissions as $key => $value) {
						$wp_roles->add_cap( 'administrator', $key);
						$wp_roles->add_cap( 'business_admin', $key);
						afl_variable_set($key.'_administrator',TRUE);
						afl_variable_set($key.'_business_admin',TRUE);
					}
			}
		}


	/**
 	 * ----------------------------------------------------------------------------
	 * Remove core post type capabilities (called on uninstall)
 	 * ----------------------------------------------------------------------------
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 *
	 */
		public function remove_caps() {

			if ( class_exists('WP_Roles') ) {
				if ( ! isset( $wp_roles ) ) {
					$wp_roles = new WP_Roles();
				}
			}

			if ( is_object( $wp_roles ) ) {
				// /* ------ Site Admin Permission capabilities ------------*/
				// 	$wp_roles->remove_cap( 'administrator', 'advanced_configurations' );
				// /* ------ View Network ----------------------------------*/
				// 	$wp_roles->remove_cap( 'administrator', 'AFL_network_view' );
				// /* ------- Add new Member --------------------------------*/
				// 	$wp_roles->remove_cap( 'administrator', 'AFL_add_new_memberAFL_add_new_member' );
				// 	$wp_roles->remove_cap( 'administrator', 'advanced_configurations' );
				// 	$wp_roles->remove_cap( 'administrator', 'compensation_plan_configurations' );
				
					$wp_roles->remove_cap( 'business_admin', 'edit_posts' );
					$wp_roles->remove_cap( 'afl_member', 'edit_posts' );
					// $wp_roles->remove_cap( 'afl_member', 'afl_network_view_afl_member' );
					// $wp_roles->remove_cap( 'afl_member', 'afl_add_new_member_afl_member' );
			}
		}

	/**
	 * Maps meta capabilities to primitive ones.
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @param array  $caps    The user's actual capabilities.
	 * @param string $cap     Capability name.
	 * @param int    $user_id The user ID.
	 * @param array  $args    Adds the context to the cap. Typically the object ID.
	 * @return array (Maybe) modified capabilities.
	 */
	public function map_meta_caps( $caps = array(), $cap, $user_id, $args ) {
		// switch( $cap ) {
		// 	case 'add_affiliate':
		// 		$caps[] = 'manage_affiliates';
		// 		break;

		// 	case 'edit_affiliate':
		// 	case 'delete_affiliate':
		// 	case 'view_affiliate':
		// 		$affiliate = affwp_get_affiliate( $args[0] );

		// 		$caps[] = $affiliate ? 'manage_affiliates' : 'do_not_allow';
		// 		break;

		// 	case 'add_creative':
		// 		$caps[] = 'manage_creatives';
		// 		break;

		// 	case 'edit_creative':
		// 	case 'delete_creative':
		// 	case 'view_creative':
		// 		$creative = affwp_get_creative( $args[0] );

		// 		$caps[] = $creative ? 'manage_creatives' : 'do_not_allow';
		// 		break;

		// 	case 'add_payout':
		// 		$caps[] = 'manage_payouts';
		// 		break;

		// 	case 'view_payout':
		// 		$payout = affwp_get_payout( $args[0] );

		// 		$caps[] = $payout ? 'manage_payouts' : 'do_not_allow';
		// 		break;

		// 	case 'add_referral':
		// 		$caps[] = 'manage_referrals';
		// 		break;

		// 	case 'edit_referral':
		// 	case 'delete_referral':
		// 		$referral = affwp_get_referral( $args[0] );

		// 		$caps[] = $referral ? 'manage_referrals' : 'do_not_allow';
		// 		break;

		// 	case 'add_visit':
		// 		$caps[] = 'manage_visits';
		// 		break;

		// 	case 'add_api_key':
		// 		$caps[] = 'manage_consumers';
		// 		break;

		// 	case 'regenerate_api_key':
		// 	case 'revoke_api_key':
		// 		$consumer = affwp_get_rest_consumer( $args[0] );

		// 		$caps[] = $consumer ? 'manage_consumers' : 'do_not_allow';
		// 		break;
		// }

		return $caps;
	}
	
}
// $obj = new Eps_affiliates_Capabilities;
// $obj->remove_caps();