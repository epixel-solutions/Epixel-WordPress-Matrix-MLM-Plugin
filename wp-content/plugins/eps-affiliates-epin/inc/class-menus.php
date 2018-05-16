<?php 
/*
 * -------------------------------------------------------------------------
 * Includes All the menus of admin
 * -------------------------------------------------------------------------
 *
*/
	class Eps_Affiliates_epin_Menu {
		/* -------------------------------------------------------------------------
		 * Construct Admin Menus
		 * -------------------------------------------------------------------------
		*/
			public function __construct() {
				add_action( 'admin_menu', array( $this , 'afl_general_help_menus' ) );

			}
		/* -------------------------------------------------------------------------
		 *  All system Configuration
		 * -------------------------------------------------------------------------
		*/
			public function afl_general_help_menus (){
				$menu = array();
				$menu['e_pin'] = array(
					'#page_title'			=> __( 'E-pin', 'e_pin' ),
					'#menu_title' 		=> __( 'E-pin', 'e_pin' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin', 
					'#page_callback' 	=> 'afl_my_e_pin', 
					'#weight'					=> 4
				);
				/*$menu['e_pin_sub'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'E-pin', 'e_pin' ),
					'#menu_title' 		=> __( 'E-pin', 'e_pin' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin', 
					'#page_callback' 	=> 'afl_epins', 
				);*/
				$menu['e_pin_generate'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'Generate E-pin', 'generate-e-pin' ),
					'#menu_title' 		=> __( 'Generate E-pin', 'generate-e-pin' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin-generate', 
					'#page_callback' 	=> 'afl_epin_generate', 
				);
				$menu['e_pin_my'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'My E-pins', 'My E-pins' ),
					'#menu_title' 		=> __( 'My E-pins', 'My E-pins' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'my-e-pin', 
					'#page_callback' 	=> 'afl_my_e_pin', 
				);
				$menu['e_pin_history'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'E-pin History', 'e-pin-history' ),
					'#menu_title' 		=> __( 'E-pin History', 'e-pin-history' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin-history', 
					'#page_callback' 	=> 'afl_epin_history', 
				);
				$menu['e_pin_all'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'All E-pins', 'e-pin-all' ),
					'#menu_title' 		=> __( 'All E-pin', 'e-pin-all' ),
					'#access_callback'=> 'epin_conf', 
					'#menu_slug' 			=> 'all-e-pin', 
					'#page_callback' 	=> 'afl_admin_all_e_pin', 
				);
				$menu['e_pin_config'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'E-pin Configurations', 'e-pin-configs' ),
					'#menu_title' 		=> __( 'E-pin Configurations', 'e-pin-configs' ),
					'#access_callback'=> 'epin_conf', 
					'#menu_slug' 			=> 'e-pin-configs', 
					'#page_callback' 	=> 'afl_epin_configurations', 
				);
				
				$menu['e_pin_config_'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'E-Pin Purchase', 'e-pin-purchase' ),
					'#menu_title' 		=> __( 'E-pin Purchase', 'e-pin-purchase' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin-purchase', 
					'#page_callback' 	=> 'afl_epin_user_purchase', 
				);
				
				/*$menu['e_pin_refund'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'Refund from E-pin', 'e-pin-refund' ),
					'#menu_title' 		=> __( 'Refund from E-pin', 'e-pin-refund' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin-refund', 
					'#page_callback' 	=> 'afl_epin_refund', 
				);*/
				if (has_action('eps_affiliate_system_menus')) {
					do_action('eps_affiliate_system_menus',$menu);
				}
			}
	}
$eps_afl_ghelp_menu = new Eps_Affiliates_epin_Menu;

