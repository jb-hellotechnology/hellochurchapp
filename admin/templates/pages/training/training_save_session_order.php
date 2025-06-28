<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

$sessionID = $_POST['sessionID'];
$order = $_POST['order'];

process_save_session_order($sessionID, $order);