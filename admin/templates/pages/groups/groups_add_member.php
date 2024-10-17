<?php

if(!perch_member_logged_in()){
	exit;
}

$groupID = (int) $_POST['groupID'];
$id = (int) $_POST['contactID'];

process_add_group_member($groupID, $id);

header("location:/groups/edit-group?id=".$groupID);

?>