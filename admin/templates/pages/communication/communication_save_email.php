<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

$emailID = $_POST['emailID'];
$email = $_POST['email'];

process_save_email($emailID, $email);