<?php

if(!perch_member_logged_in()){
	exit;
}

$eventID = (int) $_POST['eventID'];
$eventTimeStamp = $_POST['eventDate'];
$eventDateParts = explode(" ", $eventTimeStamp);
$eventDate = $eventDateParts[0];
$contactID = (int) $_POST['contactID'];
$roleID = (int) $_POST['roleID'];

process_add_role_contact($eventID, $eventDate, $contactID, $roleID);

header("location:/calendar/edit-event?id=".$eventID."&date=".$eventTimeStamp."&msg=contact_added");

?>