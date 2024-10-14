<?php

if(!perch_member_logged_in()){
	exit;
}

process_tag_contacts($_POST['contacts'], $_POST['tag']);

?>