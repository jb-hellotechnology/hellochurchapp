<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../../vendor/autoload.php';
require '../../../secrets.php';

$template = file_get_contents('../../../email_template.html');

if(!perch_member_logged_in()){
	header("location:/");
}

$email = hello_church_get_email($_POST['id']);
$recipient = $_POST['recipients'];

$church = hello_church_church(true);
$senderPostalAddress = "$church[churchName], $church[churchAddress1], $church[churchCity], $church[churchCountry]";

$subject = $email['emailSubject'];
$email = json_decode($email['emailContent'], true);

echo 'test';	

?>