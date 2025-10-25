<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

$roleID = $_POST['roleID'];
$order = $_POST['order'];

process_save_role_order($roleID, $order);