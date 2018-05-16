<?php
/*
Template Name: Trash
*/
$emails = get_emails_for_user(array('trash'));
include(__DIR__ . '/inc/templates/email-list.php');
?>