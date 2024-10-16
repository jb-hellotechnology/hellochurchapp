<?php

if(!perch_member_logged_in()){
	exit;
}

process_remove_family_member($_POST['identifier'], $_POST['contactID']);

header("location:/contacts/edit-contact?id=".$_POST['primary']);

?>