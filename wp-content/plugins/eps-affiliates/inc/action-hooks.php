<?php
/* --------- All the action hooks ------------------------*/
// add_action('init', 'common_scripts_load');
// function common_scripts_load(){
// 	wp_register_script( 'common-js',  EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js');
// 	wp_enqueue_script( 'common-js' );

//   wp_localize_script( 'common-js', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
// }

/*
 * ----------------------------------------------------------
 *  Check eps-afl installed or not
 * ----------------------------------------------------------
*/
	// add_action( 'admin_init', 'eps_affiliate_check_if_installed' );
/*
 * ------------------------------------------------------------
 * Install features on install plugin
 * ------------------------------------------------------------
*/
	register_activation_hook( EPSAFFILIATE_PLUGIN_FILE, 'eps_affiliates_install'  );
/*
 * ------------------------------------------------------------
 * Disable features on un-install plugin
 * ------------------------------------------------------------
*/
	register_deactivation_hook(  EPSAFFILIATE_PLUGIN_FILE, 'check_depend_plugin_uninstall');

	//before un install need to uninstall depend plugins
	function check_depend_plugin_uninstall () {
		if ( is_plugin_active( 'eps-affiliates-epin/eps-affiliates-epin.php' ) ) {
        wp_die('Sorry, You need to uninstall <b>Eps affliates epin</b> Before perform this operation. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }else {
    	eps_affiliates_uninstall();
    }
	}
/*
 * ------------------------------------------------------------
 * Admin notices
 * ------------------------------------------------------------
*/
add_action( 'admin_notices', 'eps_affiliates_admin_notices' );

function eps_affiliates_admin_notices () {
	/*
	 * -----------------------------------------------------------
	 * Check the root user has been set for the system
	 * if not display an error message with root user set 
	 * configuration  link
	 * -----------------------------------------------------------
	*/
		if (!afl_variable_get('root_user')) {
			$class = 'notice notice-error';
			$message = __( 'Root user ! Currently you are not choose a root user.You need to select a root user for the system', 'sample-text-domain' );
			printf( '<div class="%1$s"><p>%2$s<a href="%3$s"> Goto settings</a></p></div>', esc_attr( $class ), esc_html( $message ) ,admin_url('admin.php?page=affiliate-eps-genealogy-configurations' ));

		}
	/*
	 * -----------------------------------------------------------
	 * Check the permission give once, if not an error notification
	 * message will be display with the permission configuration 
	 * link
	 * -----------------------------------------------------------
	*/
		if (!afl_variable_get('eps_afl_is_configur_permissions')) {
			$class = 'notice notice-error';
			$message = __( 'Roles and Permission : Please give the appropriate permission to each user based on their role', 'sample-text-domain' );
			printf( '<div class="%1$s"><p>%2$s<a href="%3$s"> Goto settings</a></p></div>', esc_attr( $class ), esc_html( $message ) ,admin_url('admin.php?page=affiliate-eps-role-config-settings' ));
		}
}
/*
 * ------------------------------------------------------------
 * Set the content of the page eps_affiliates
 * ------------------------------------------------------------
*/
	add_shortcode('eps_affiliates', 'afl_eps_afiliate_dashboard_shortcode');
/*
 * ------------------------------------------------------------
 * Create widget
 * ------------------------------------------------------------
*/
	add_action( 'widgets_init', 'eps_affiliates_dashboard_menu_widget' );
/*
 * ------------------------------------------------------------
 *
 * ------------------------------------------------------------
*/
	add_action( 'eps_account_content', 'eps_account_content' );

/*
 * ------------------------------------------------------------
 * Replace the menu icons using css
 * ------------------------------------------------------------
*/
	add_action( 'admin_head', 'replace_afl_eps_custom_pages_icons' );
/*
 * ------------------------------------------------------------
 * Users autocomplete initialization
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_users_auto_complete', 'users_auto_complete_callback');
 add_action('wp_ajax_nopriv_users_auto_complete', 'users_auto_complete_callback');

/*
 * ------------------------------------------------------------
 * Users autocomplete initialization
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_unilevel_users_auto_complete', 'unilevel_users_auto_complete_callback');
 add_action('wp_ajax_nopriv_unilevel_users_auto_complete', 'unilevel_users_auto_complete_callback');
 /*
 * ------------------------------------------------------------
 * Users autocomplete initialization
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_sys_users_auto_complete', '_system_user_autocomplete');
 add_action('wp_ajax_nopriv_sys_users_auto_complete', '_system_user_autocomplete');

 /*
 * ------------------------------------------------------------
 * customers autocomplete initialization
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_customers_auto_complete', 'customers_auto_complete_callback');
 add_action('wp_ajax_nopriv_customers_auto_complete', 'customers_auto_complete_callback');

/*
 * ------------------------------------------------------------
 * Auto complete under a particular user
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_member_users_autocomplete', 'member_users_auto_complete_callback');
 add_action('wp_ajax_nopriv_member_users_autocomplete', 'member_users_auto_complete_callback');

/*
 * ------------------------------------------------------------
 * Auto complete under a particular user downlines
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_member_downlines_autocomplete', 'member_downlines_auto_complete_callback');
 add_action('wp_ajax_nopriv_member_downlines_autocomplete', 'member_downlines_auto_complete_callback');
/*
 * ------------------------------------------------------------
 * Get the admin bar
 * ------------------------------------------------------------
*/
	add_filter('woocommerce_disable_admin_bar', '_wc_disable_admin_bar', 10, 1);

	function _wc_disable_admin_bar($prevent_admin_access) {

	    return false;
	}

/*
 * -------------------------------------------------------------
 * Get the dashboard
 * -------------------------------------------------------------
*/
	add_filter('woocommerce_prevent_admin_access', '_wc_prevent_admin_access', 10, 1);

	function _wc_prevent_admin_access($prevent_admin_access) {
	    if (!current_user_can('eps_system_member')) {
	        return $prevent_admin_access;
	    }
	    return false;
	}
/*
 * ------------------------------------------------------------
 * Users downline users datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_downlines_data_table', 'afl_user_downlines_data_table_callback');
 add_action('wp_ajax_nopriv_afl_user_downlines_data_table', 'afl_user_downlines_data_table_callback');
/*
 * ------------------------------------------------------------
 * Users refered downline users datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_refered_downlines_data_table', 				'afl_user_refered_downlines_data_table_callback');
 add_action('wp_ajax_nopriv_afl_user_refered_downlines_data_table', 'afl_user_refered_downlines_data_table_callback');

/*
 * ------------------------------------------------------------
 * Genealogy tree expand
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_user_expand_genealogy', 'afl_expand_user_genealogy_tree');
 	add_action('wp_ajax_nopriv_afl_user_expand_genealogy', 'afl_expand_user_genealogy_tree');
/*
 * ------------------------------------------------------------
 * Genealogy tree expand unilevel
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_unilevel_user_expand_genealogy', 'afl_unilevel_expand_user_genealogy_tree');
 	add_action('wp_ajax_nopriv_afl_unilevel_user_expand_genealogy', 'afl_unilevel_expand_user_genealogy_tree');
/*
 * ------------------------------------------------------------
 * Genealogy tree expand toggle genealogy
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_user_expand_toggle_genealogy', 'afl_user_expand_toggle_genealogy');
 	add_action('wp_ajax_nopriv_afl_user_expand_toggle_genealogy', 'afl_user_expand_toggle_genealogy');
/*
 * ------------------------------------------------------------
 * Unilevel Genealogy tree expand toggle genealogy
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_unilevel_user_expand_toggle_genealogy', 'afl_unilevel_user_expand_toggle_genealogy');
 	add_action('wp_ajax_nopriv_afl_unilevel_user_expand_toggle_genealogy', 'afl_unilevel_user_expand_toggle_genealogy');
/*
 * ------------------------------------------------------------
 * E wallet summary datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_ewallet_summary_data_table', 'afl_user_ewallet_summary_data_table_callback');
 add_action('wp_ajax_nopriv_afl_user_ewallet_summary_data_table', 'afl_user_ewallet_summary_data_table_callback');
/*
 * ------------------------------------------------------------
 * E wallet all transaction datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_ewallet_all_transaction_data_table', 'afl_user_ewallet_all_transaction_data_table');
 add_action('wp_ajax_nopriv_afl_user_ewallet_all_transaction_data_table', 'afl_user_ewallet_all_transaction_data_table');

/*
 * ------------------------------------------------------------
 * E wallet all income datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_ewallet_income_data_table', 'afl_user_ewallet_income_data_table');
 add_action('wp_ajax_nopriv_afl_user_ewallet_income_data_table', 'afl_user_ewallet_income_data_table');

/*
 * ------------------------------------------------------------
 * E wallet  expense report datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_ewallet_expense_data_table', 'afl_user_ewallet_expense_report_data_table');
 add_action('wp_ajax_nopriv_afl_user_ewallet_expense_data_table', 'afl_user_ewallet_expense_report_data_table');

/**
 * ------------------------------------------------------------
 * business wallet summary datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_admin_business_summary_data_table', 'afl_admin_bwallet_summary_data_table_callback');
 add_action('wp_ajax_nopriv_afl_admin_business_summary_data_table', 'afl_admin_bwallet_summary_data_table_callback');
 /*
 * ------------------------------------------------------------
 * business all Transaction datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_admin_business_all_transaction_data_table', 'afl_admin_business_trans_datatable_callback');
 add_action('wp_ajax_nopriv_afl_admin_business_all_transaction_data_table', 'afl_admin_business_trans_datatable_callback');
/*
 * ------------------------------------------------------------
 * business income report datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_admin_business_income_history_data_table', 'afl_admin_business_income_datatable_callback');
 add_action('wp_ajax_nopriv_afl_admin_business_income_history_data_table', 'afl_admin_business_income_datatable_callback');
 add_action('wp_ajax_nopriv_afl_admin_business_all_transaction_data_table', 'afl_admin_business_trans_datatable_callback');
/*
 * ------------------------------------------------------------
 * business expense report datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_admin_business_expense_history_data_table', 'afl_admin_business_expense_datatable_callback');
 add_action('wp_ajax_nopriv_afl_admin_business_expense_history_data_table', 'afl_admin_business_expense_datatable_callback');
/*
 * ------------------------------------------------------------
 * get availble free spaces under a user
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_get_available_free_space', 'afl_get_available_free_space_callback');
 add_action('wp_ajax_nopriv_afl_get_available_free_space', 'afl_get_available_free_space_callback');
/*
 * ------------------------------------------------------------
 * Place user under a user
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_place_user_from_tank', 'afl_place_user_from_tank_callback');
 add_action('wp_ajax_nopriv_afl_place_user_from_tank', 'afl_place_user_from_tank_callback');
/*
 * ------------------------------------------------------------
 * Autoplace a user under a sponsor
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_auto_place_user_ajax', 'afl_auto_place_user_ajax_callback');
 add_action('wp_ajax_nopriv_afl_auto_place_user_ajax', 'afl_auto_place_user_ajax_callback');

 /*
 * ------------------------------------------------------------
 * Toggle holding tank genealogy user left
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_holding_genealogy_toggle_left', 
 						'afl_user_holding_genealogy_toggle_left_callback'
 						);
 add_action('wp_ajax_nopriv_afl_user_holding_genealogy_toggle_left', 
 						'afl_user_holding_genealogy_toggle_left_callback'
 						);

/*
 * ------------------------------------------------------------
 * Toggle holding tank genealogy user right
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_holding_genealogy_toggle_right', 
 						'afl_user_holding_genealogy_toggle_right_callback'
 						);
 add_action('wp_ajax_nopriv_afl_user_holding_genealogy_toggle_right', 
 						'afl_user_holding_genealogy_toggle_right_callback'
 						);

 /*
 * ------------------------------------------------------------
 * Approve a withdrawal request
 *
 * Get an as input to the action, The payout/withdrawal Request is approve
 * 
 * ------------------------------------------------------------
*/
 add_filter('eps_affiliates_withdrawal_approve', 'eps_affiliates_withdrawal_approve_callback', 10, 1);
/*
 * ------------------------------------------------------------
 * Approve a withdrawal request
 *
 * Get an as input to the action, The payout/withdrawal Request is approve
 * 
 * ------------------------------------------------------------
*/
 add_filter('eps_affiliates_withdrawal_reject', 'eps_affiliates_withdrawal_reject_callback', 10, 1);
 /*
 * ------------------------------------------------------------
 * Approve a withdrawal request
 *
 * Get an as input to the action, The payout/withdrawal Request is approve
 * 
 * ------------------------------------------------------------
*/
 add_filter('eps_affiliates_payout_paid', 'eps_affiliates_payout_paid_callback', 10, 1);
 /*
 * ------------------------------------------------------------
 * Approve a withdrawal request
 *
 * Get an as input to the action, The payout/withdrawal Request is approve
 * 
 * ------------------------------------------------------------
*/
 add_filter('eps_affliate_user_cancel_withdraw', 'eps_affliate_user_cancel_withdraw_callback', 10, 1);

/*
 * ------------------------------------------------------------
 * SEt cookie values for ajax callbackz  
 * ------------------------------------------------------------
*/
	// add_action('init', 'eps_affiliates_set_cookies');
	function eps_affiliates_set_cookies() {
		//dashboard widget dowlines count visible value
    if (!isset($_COOKIE['eps_afl_widget_downlines_visible_value'])) {
       setcookie('eps_afl_widget_downlines_visible_value', 'y', strtotime('+1 day'));
    }
		//dashboard widget ewallet amount visible value
		if (!isset($_COOKIE['eps_afl_widget_ewallet_visible_value'])) {
       setcookie('eps_afl_widget_ewallet_visible_value', 'y', strtotime('+1 day'));
    }
    //dashboard widget ewallet income visible value
		if (!isset($_COOKIE['eps_afl_widget_ewallet_income_visible_value'])) {
       setcookie('eps_afl_widget_ewallet_income_visible_value', 'y', strtotime('+1 day'));
    }
    //dashboard widget ewallet expense visible value
		if (!isset($_COOKIE['eps_afl_widget_ewallet_expense_visible_value'])) {
       setcookie('eps_afl_widget_ewallet_expense_visible_value', 'y', strtotime('+1 day'));
    }
    //dashboard widget ewallet transaction chart visible value
		if (!isset($_COOKIE['eps_afl_widget_ewallet_transactions_chart_visible_value'])) {
       setcookie('eps_afl_widget_ewallet_transactions_chart_visible_value', 'y', strtotime('+1 day'));
    }
    //dashboard widget bwallet transaction chart visible value
		if (!isset($_COOKIE['eps_afl_widget_bwallet_transactions_chart_visible_value'])) {
       setcookie('eps_afl_widget_bwallet_transactions_chart_visible_value', 'y', strtotime('+1 day'));
    }
    //dashboard widget bwallet report chart visible value
		if (!isset($_COOKIE['eps_afl_widget_bwallet_report_chart_visible_value'])) {
       setcookie('eps_afl_widget_bwallet_report_chart_visible_value', 'y', strtotime('+1 day'));
    }
	}
/*
 * ------------------------------------------------------------
 *
 * ------------------------------------------------------------
*/
 add_action( 'admin_print_styles', '_load_common_styles');
 //our custom styles only applied to the following pages only
 function _load_common_styles () {
 	$page = isset($_GET['page']) ?$_GET['page'] : '';
 	$style_applied_pages 	= eps_affiliates_style_applied_pages();

 	if ($page) {
 		if (in_array($page, $style_applied_pages)) {
 			// wp_enqueue_style( 'simple-line-icons', EPSAFFILIATE_PLUGIN_ASSETS.'plugins/simple-line-icons/css/simple-line-icons.css');
			// wp_enqueue_style( 'app', EPSAFFILIATE_PLUGIN_ASSETS.'css/app.css');
			// wp_enqueue_style( 'developer', EPSAFFILIATE_PLUGIN_ASSETS.'css/developer.css');
 		}
 	}
 }


/*
 * ------------------------------------------------------------
 * Check user account is blocked or not
 * ------------------------------------------------------------
*/
 add_filter('authenticate', 'eps_affiliates_user_authentication', 9999);
 function eps_affiliates_user_authentication($user) {
	  $status = get_class($user);

	  if($status == 'WP_User') {
			$node 	= afl_genealogy_node($user->data->ID);
      $locking_data = !empty($node->status) ? $node->status : 1;
      if( !$locking_data ) {
          $message = Apply_Filters('account_lock_message', SPrintF('<strong>%s</strong> %s', 'Error:', 'Your account has been blocked.Could not access. '), $user);
          return new \WP_Error('authentication_failed', $message);
      } else {
        return $user;
      }
	  }
	  return $user;
  }

/*
 * ------------------------------------------------------------
 * Style Applied pages
 * ------------------------------------------------------------
*/
	function eps_affiliates_style_applied_pages ($pages = array()) {
		$pages = array();
		$pages = array(
			//dashboard
			'eps-dashboard',
			//network
			'affiliate-eps-user-network',
			'affiliate-eps-downline-members',
			'affiliate-eps-genealogy-tree',
			'affiliate-eps-holding-tank',
			'affiliate-eps-refered-members',
			'affiliate-eps-add-new-customer',
			'affiliate-eps-my-customers',
			'affiliate-eps-user-holding-toggle-placement',
			'affiliate-eps-user-holding-genealogy-toggle-placement',
			'affiliate-eps-direct-uplines-tree',

			//unilevel network
			'affiliate-eps-unilevel-user-network',
			'affiliate-eps-unilevel-downline-members',
			'affiliate-eps-unilevel-genealogy-tree',
			'affiliate-eps-unilevel-holding-tank',
			'affiliate-eps-unilevel-refered-members',
			'affiliate-eps-unilevel-add-new-customer',
			'affiliate-eps-unilevel-my-customers',
			'affiliate-eps-user-unilevel-holding-genealogy-toggle-placement',
			'affiliate-eps-unilevel-direct-uplines-tree',

			//e-wallet
			'affiliate-eps-e-wallet-summary',
			'affiliate-eps-e-wallet',
			'affiliate-eps-ewallet-all-transactions',
			'affiliate-eps-ewallet-income-report',
			'affiliate-eps-ewallet-withdraw-report',
			'affiliate-eps-ewallet-withdraw-fund',
			'affiliate-eps-ewallet-my-withdrawal',
			'affiliate-eps-payment_method',
			'user-payment-configuration',

			//system configurations
			'affiliate-eps-business-system-members',
			'affiliate-eps-system-configurations',
			'affiliate-eps-compensation-plan-configurations',
			'affiliate-eps-rank-configurations',
			'affiliate-eps-role-config-settings',
			'affiliate-eps-genealogy-configurations',
			'affiliate-eps-payout-configurations',
			'affiliate-eps-pool-bonus-configurations',
			'affiliate-eps-payment-method-configurations',
			'affiliate-eps-variable-configurations',
			'affiliate-eps-advanced-queue-configurations',
			'affiliate-eps-recent-log-messages',

			'affiliate-eps-features-and-configurations',

			// Business transaction
			'affiliate-eps-business',
			'affiliate-eps-business-summary',
			'affiliate-eps-business-income-history',
			'affiliate-eps-business-expense-report',
			'affiliate-eps-business-transaction',
			'afl_add_edit_business_system_members',

				//reports
			'affiliate-eps-reports',
			'affiliate-eps-team-purchases-reports',
			'affiliate-eps-payout',
			'affiliate-eps-payout-in-remittance',
			'affiliate-eps-bonus-summary-report',
			'affiliate-eps-incentive-history-report',
			//manage members
			'affiliate-eps-manage-members',
			'affiliate-eps-blocked-members',
			'affiliate-eps-find-members',


			'eps-test',
			'affiliate-eps-purchases',
			'affiliate-eps-my-purchases',
			'eps-generate-purchase',
			'eps-test-codes',
			'eps-fund-deposit',
			'affiliate-eps-business-profit',
			'affiliate-eps-remote-user-get',
			'affiliate-eps-shortcode-demo',
			'affiliate-eps-processing-queue',
		);

		return apply_filters('eps_affiliates_style_applied_pages',$pages);
	}
	
