<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!perch_member_logged_in()){
	exit;
}
echo $_GET['contacts'];
$contacts = $_GET['contacts'];

process_delete_contacts($contacts);

?>