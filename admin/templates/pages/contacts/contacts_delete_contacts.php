<?php

if(!perch_member_logged_in()){
	exit;
}

$contacts = strip_tags(addslashes($_POST['contacts']));

process_delete_contacts($contacts);

?>