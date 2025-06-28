<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

$sessionID = $_POST['sessionID'];
$session = $_POST['session'];

process_save_session($sessionID, $session);