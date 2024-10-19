<?php

if(!perch_member_logged_in()){
	exit;
}

$eventID = (int) $_POST['eventID'];
$eventDate = $_POST['date'];
$roleContactID = (int) $_POST['roleContactID'];
echo 1;
process_remove_role_contact($eventID, $eventDate, $roleContactID);

header("location:/calendar/edit-event?id=".$eventID."&date=".$eventDate."&msg=contact_deleted");

?>