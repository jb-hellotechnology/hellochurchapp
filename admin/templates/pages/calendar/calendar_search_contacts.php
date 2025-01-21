<?php

if(!perch_member_logged_in()){
	exit;
}

process_search_role_members(strip_tags(addslashes($_POST['q'])), strip_tags(addslashes($_POST['eventID'])), strip_tags(addslashes($_POST['date'])), strip_tags(addslashes($_POST['roleID'])));

?>