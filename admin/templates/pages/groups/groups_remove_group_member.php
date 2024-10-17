<?php

if(!perch_member_logged_in()){
	exit;
}

$groupID = (int) $_POST['groupID'];
$contactID = (int) $_POST['contactID'];

if(!hello_church_group_owner($groupID)){
	perch_member_log_out();
	header("location:/");
}

process_remove_group_member($groupID, $contactID);

header("location:/groups/edit-group?id=".$groupID);

?>