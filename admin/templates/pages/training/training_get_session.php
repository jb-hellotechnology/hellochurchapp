<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

$sessionContent = hello_church_get_session_content(perch_get('sessionID'));

echo $sessionContent;