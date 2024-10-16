<?php

if(!perch_member_logged_in()){
	exit;
}

$primary = (int) $_POST['primary'];
$id = (int) $_POST['contactID'];

if(!hello_church_member_owner($id)){
	perch_member_log_out();
	header("location:/");
}

process_add_family_member($primary, $id);

header("location:/contacts/edit-contact?id=".$primary);

?>