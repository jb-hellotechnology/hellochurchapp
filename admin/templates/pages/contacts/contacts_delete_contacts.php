<?php

if(!perch_member_logged_in()){
	exit;
}

$contacts = $_POST['contacts'];

process_delete_contacts($contacts);

?>