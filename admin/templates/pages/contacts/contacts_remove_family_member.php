<?php

if(!perch_member_logged_in()){
	exit;
}

$primary = (int) $_POST['primary'];
$identifier = (int) $_POST['identifier'];
$contactID = (int) $_POST['contactID'];

if(!hello_church_member_owner($contactID)){
	perch_member_log_out();
	header("location:/contacts");
}

process_remove_family_member($identifier, $contactID);

header("location:/contacts/edit-contact?id=".$primary);

?>