<?php

if(!perch_member_logged_in()){
	exit;
}

$contacts = $_GET['contacts'];

process_delete_contacts($contacts);

?>