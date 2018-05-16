<?php
/*
Template Name: Inbox
*/
$emails = get_emails_for_user(array('unread', 'read'));
include(__DIR__ . '/inc/templates/email-list.php');
?>