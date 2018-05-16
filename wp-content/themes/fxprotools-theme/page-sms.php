<?php 
/*
Template Name: SMS
*/
$smsPage = true;

$emails = get_sms_for_user(array('unread', 'read'));
include(__DIR__ . '/inc/templates/email-list.php');
get_footer(); ?>