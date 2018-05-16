<?php
$smsPage = true;
$emails = get_sms_for_user(array('trash'));
include(__DIR__ . '/inc/templates/email-list.php');
?>