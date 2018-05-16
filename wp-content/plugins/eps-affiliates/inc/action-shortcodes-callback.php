<?php
/*
 * -----------------------------------------------------------------
 * Get the sponsor info
 * -----------------------------------------------------------------
*/
	function afl_sponsor_info() {
		afl_get_template('shortcode-templates/sponsor-info-template.php');
	}
/*
 * -----------------------------------------------------------------
 * Get the team info
 * -----------------------------------------------------------------
*/
	function afl_team_info() {
		afl_get_template('shortcode-templates/team-info-template.php');
	}
/*
 * -----------------------------------------------------------------
 * Get the team info
 * -----------------------------------------------------------------
*/
	function afl_genealogy_info() {
		afl_get_template('shortcode-templates/genealogy-info-template.php');
	}
/*
 * -----------------------------------------------------------------
 * Network holding tank
 * -----------------------------------------------------------------
*/
	function afl_network_holding_tank_shortcode() {
		afl_get_template('plan/matrix/holding-tank.php');
	}
/*
 * -----------------------------------------------------------------
 *
 * -----------------------------------------------------------------
*/
	function afl_ewallet_all_earnings_summary_blocks_shortcode_callback () {
		do_action('afl_ewallet_all_earnings_summary_blocks_template');
	}
/*
 * -----------------------------------------------------------------
 *
 * -----------------------------------------------------------------
*/
	function afl_ewallet_all_earnings_holding_summary_blocks_shortcode () {
		do_action('afl_ewallet_all_earnings_holding_summary_blocks_template');
	}

/*
 * -----------------------------------------------------------------
 * Bonus summary widget blocks
 * -----------------------------------------------------------------
*/
	function afl_bonus_summary_widgets_callback () {
		afl_get_template('plan/matrix/bonus-summary-widgets-template.php');
	}
/*
 * ----------------------------------------------------------------
 * Bonus and incentives details
 * ----------------------------------------------------------------
*/
	function afl_bonus_summary_and_incentives_callback () {
		return _bonus_nd_incentives_table();
	}
/*
 * ----------------------------------------------------------------
 *
 * ----------------------------------------------------------------
*/
	function afl_eps_matrix_direct_uplines_shortcode_callback () {
		afl_network_direct_uplines_callback();
	}
/*
 * ----------------------------------------------------------------
 *
 * ----------------------------------------------------------------
*/
	function afl_eps_unilevel_direct_uplines_shortcode_callback () {
		afl_unilevel_network_direct_uplines_callback();
	}