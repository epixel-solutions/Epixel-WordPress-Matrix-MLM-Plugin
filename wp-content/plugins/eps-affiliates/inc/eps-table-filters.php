<?php
/**
 * ---------------------------------------------------------------
 * @author < pratheesh@epixelsolutions.com >
 * @category   Data table filters
 * @package    Eps-affiliates
 *
 *
 * In this creates some actions for adding the style of filters
 * for data tables
 * ---------------------------------------------------------------
*/


/*
 * ---------------------------------------------------------------
 * Filter with user name
 * ---------------------------------------------------------------
*/
	add_action('eps_table_filter_user','eps_table_filter_user_callback');
	function eps_table_filter_user_callback () {
		$value  	= isset($_GET['filter-user']) ? $_GET['filter-user'] : '';
		$html_tag = '';
		$html_tag .= '<div class="alignleft actions">';
		$html_tag .= '<input type="text" name="filter-user" id="filter-user" class="auto_complete" placeholder="user name" value="'.$value.'" data-path="users_auto_complete" autocomplete="off">';
		$html_tag .= '</div>';
		echo $html_tag;
	}
/*
 * ---------------------------------------------------------------
 * Filter with sponsor name
 * ---------------------------------------------------------------
*/
	add_action('eps_table_filter_sponsor','eps_table_filter_sponsor_callback');
	function eps_table_filter_sponsor_callback () {
		$value  	= isset($_GET['filter-sponsor']) ? $_GET['filter-sponsor'] : '';
		$html_tag = '';
		$html_tag .= '<div class="alignleft actions ">';
		$html_tag .= '<input type="text" name="filter-sponsor" id="filter-sponsor" class="auto_complete" value="'.$value.'" placeholder="sponsor name" data-path="users_auto_complete" autocomplete="off">';
		$html_tag .= '</div>';
		echo $html_tag;
	}
/*
 * ---------------------------------------------------------------
 * Filter with parent name
 * ---------------------------------------------------------------
*/
	add_action('eps_table_filter_parent','eps_table_filter_parent_callback');
	function eps_table_filter_parent_callback () {
		$value  	= isset($_GET['filter-parent']) ? $_GET['filter-parent'] : '';
		$html_tag = '';
		$html_tag .= '<div class="alignleft actions ">';
		$html_tag .= '<input type="text" name="filter-parent" id="filter-parent" class="auto_complete" value="'.$value.'" placeholder="Parent name" data-path="users_auto_complete" autocomplete="off">';
		$html_tag .= '</div>';
		echo $html_tag;
	}

/*
 * ---------------------------------------------------------------
 * Filter Button
 * ---------------------------------------------------------------
*/
	add_action('eps_table_filter_button','eps_table_filter_button_callback',1);
	function eps_table_filter_button_callback ($value = '') {
		$value    = !empty($value) ? $value : 'Filter';
		$html_tag = '';
		$html_tag .= '<input type="submit" id="eps-table-filter-button" class="button eps-table-filter-button" value="'.$value.'">';
		echo $html_tag;
	}
/*
 * ---------------------------------------------------------------
 * Clear button
 * ---------------------------------------------------------------
*/
	add_action('eps_table_filter_reset','eps_table_filter_reset_callback',1);
	function eps_table_filter_reset_callback ($value = '') {
		$value    = !empty($value) ? $value : 'Reset';

		$html_tag = '';
		$html_tag .= sprintf('<a class="btn m-b-xs btn-sm  btn-addon button" href="?page=%s">'.$value.'</a>',$_GET['page']);
		echo $html_tag;
	}