<?php

if(!perch_member_logged_in()){
	exit;
}

$contacts = $_POST['contacts'];
print_r($contacts);
process_delete_contacts($contacts);

?>