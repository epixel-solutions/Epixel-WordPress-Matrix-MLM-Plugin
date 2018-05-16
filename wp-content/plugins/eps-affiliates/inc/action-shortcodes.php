<?php
/*
 * -----------------------------------------------------------------
 * Here comes the shorcodes for our functionalities
 * -----------------------------------------------------------------
*/
 
/* ----- Network Menus: matrix -------------*/
//genealogy tree
add_shortcode('afl_eps_matrix_genealogy_tree', 
							'afl_genealogy_tree_callback' );
//downline members
add_shortcode('afl_eps_matrix_downlines', 
							'afl_downline_members_callback');
//refered members
add_shortcode('afl_eps_matrix_reffered_downlines', 
							'afl_refered_members_callback');
//holding tank
add_shortcode('afl_eps_matrix_holding_tank', 
							'afl_network_holding_tank_shortcode');
//holding tank toggle placement
add_shortcode('afl_eps_matrix_holding_tank_genealogy_toggle_placement', 
							'afl_holding_tank_genealogy_toggle_placement_form');
//Direct uplines
add_shortcode('afl_eps_matrix_direct_uplines_shortcode', 
							'afl_eps_matrix_direct_uplines_shortcode_callback');

/* ----- Network Menus: unilevel -------------*/
//genealogy tree
add_shortcode('afl_eps_unilevel_genealogy_tree', 
							'afl_unilevel_genealogy_tree_callback' );
//downline members
add_shortcode('afl_eps_unilevel_downlines', 
							'afl_unilevel_downline_members_callback');
//refered members
add_shortcode('afl_eps_unilevel_reffered_downlines', 
							'afl_unilevel_refered_members_callback');
//holding tank
add_shortcode('afl_eps_unilevel_holding_tank', 
							'afl_unilevel_network_holding_tank_callback');
//holding tank toggle placement
add_shortcode('afl_unilevel_holding_tank_genealogy_toggle_placement', 
							'afl_unilevel_holding_tank_genealogy_toggle_placement_callback');
//Direct uplines
add_shortcode('afl_eps_unilevel_direct_uplines_shortcode', 
							'afl_eps_unilevel_direct_uplines_shortcode_callback');


//ewallet
add_shortcode('afl_ewallet_summary',
						  'afl_ewallet_summary_callback');
//ewallet
add_shortcode('afl_ewallet_transactions',
						  'afl_ewallet_all_transactions_callback');
//sponsro info
add_shortcode('afl_sponsor_info', 
						  'afl_sponsor_info');
//team info
add_shortcode('afl_team_info', 
							'afl_team_info');
//genealogy info
add_shortcode('afl_genealogy_info', 
							'afl_genealogy_info');
//business profit report
add_shortcode('afl_business_profit_report_shortcode',
							'afl_system_business_profit_report_');

//
add_shortcode('afl_ewallet_all_earnings_summary_blocks_shortcode',
							 'afl_ewallet_all_earnings_summary_blocks_shortcode_callback');

//bonus summary widgets
add_shortcode('afl_bonus_summary_widgets',
							 'afl_bonus_summary_widgets_callback');

//bonus summary  table details
add_shortcode('afl_bonus_summary_and_incentives',
							 'afl_bonus_summary_and_incentives_callback');






//set transaction password
add_shortcode( 'set_transaction_password',
    									 'afl_user_payment_autherization_form' );
//withdrawselect payment method
add_shortcode( 'select_payment_method_form',
    									 'afl_user_payment_method_form' );
// Hyper Wallet User Details Table
add_shortcode( 'hyper_wallet_acc_detail',
    									 'show_hyper_wallet' );
// Hyper Wallet User Details Table
add_shortcode( 'hyper_wallet_acc_form',
    									 'afl_user_payment_conf_method_hyperwallet_form' );
//withdraw fund form
add_shortcode( 'withdraw_fund_form',
    									 'afl_ewallet_withdraw_fund_form' );


//rank overview

add_shortcode( 'afl_rank_performance_overview_shortcode',
    									 'afl_rank_performance_overview_shortcode_callback' );\
//incentive
add_shortcode( 'afl_incentive_history_report',
    									 'afl_incentive_history_report_table' );

add_shortcode( 'afl_holding_payout_transactions',
    									 'afl_system_business_holding_payouts_callback' );

add_shortcode( 'afl_ewallet_all_earnings_holding_summary_blocks_shortcode',
    									 'afl_ewallet_all_earnings_holding_summary_blocks_template_callback' );

add_shortcode( 'afl_ewallet_user_holding_transactions',
    									 'afl_ewallet_holding_transactions_callback' );


add_shortcode( 'afl_ewallet_user_holding_summary',
    									 'afl_ewallet_holding_summary_callback' );

