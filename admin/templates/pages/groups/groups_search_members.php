<?php

if(!perch_member_logged_in()){
	exit;
}

process_search_members(perch_get('q'), perch_get('groupID'));

?>