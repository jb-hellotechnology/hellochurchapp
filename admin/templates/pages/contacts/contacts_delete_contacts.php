<?php

if(!perch_member_logged_in()){
	exit;
}

process_delete_contacts($_POST['contacts']);

?>