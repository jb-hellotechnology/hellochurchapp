<?php

if(!perch_member_logged_in()){
	exit;
}

process_add_family_member($_POST['primary'], $_POST['contactID']);

header("location:/contacts/edit-contact?id=".$_POST['primary']);

?>