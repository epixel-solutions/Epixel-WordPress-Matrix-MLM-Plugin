<?php
/*
Template Name: Coaching
*/
get_header();

$product_id = 50; 
$product = wc_get_product( $product_id );
//url action, we get the state of the url query string, to perform
$action = isset($_GET['_action']) ? $_GET['_action']:'';
//holds data to pass to template
$data = array();

$view = new Apyc_View;
$theme_js = $view->get_assets_js_theme();
$template = $view->get_view_templates();
$data['view_theme_js'] = $theme_js;
$data['view_template'] = $template;
$data['obj_view'] = $view;

$data['title'] = _('Coaching / Webinars');
$data['sub_heading'] = _('Check Below For Your Coaching Webinars');
$data['schedule_private_coaching'] = _('Schedule Private Coaching');
$data['schedule_private_coaching_url'] = 'product/1-on-1-coaching/';
$data['tab_upcoming_session'] = _('Upcoming Sessions');
$data['tab_history_session'] = _('Past Sessions');
$data['tab_private_coaching'] = _('Private Coaching');

if ( apyc_has_active_user_subscription() || current_user_can('administrator')  ) : 
	get_template_part('inc/templates/nav-products'); 
	
	switch($action){
		default:
			$view->view_theme($template . 'coaching/main.php', $data);
		break;
	}
else: 
	get_template_part('inc/templates/no-access'); 
endif; 

get_footer(); 
?>