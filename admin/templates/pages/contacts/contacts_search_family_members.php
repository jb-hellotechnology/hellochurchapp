<?php

if(!perch_member_logged_in()){
	exit;
}

process_search_family_members(strip_tags(addslashes($_POST['q'])), strip_tags(addslashes($_POST['memberID'])));

?>