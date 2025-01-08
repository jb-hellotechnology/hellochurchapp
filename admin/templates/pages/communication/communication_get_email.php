<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

$emailContent = hello_church_get_email_content(perch_get('emailID'));

echo $emailContent;