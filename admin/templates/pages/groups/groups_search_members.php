<?php

if(!perch_member_logged_in()){
	exit;
}

process_search_members(strip_tags(addslashes($_POST['q'])), strip_tags(addslashes($_POST['groupID'])));

?>