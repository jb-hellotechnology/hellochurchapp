<?php

if(!perch_member_logged_in()){
	exit;
}

$contacts = strip_tags(addslashes($_POST['contacts']));
print_r($contacts);
//process_delete_contacts($contacts);

?>