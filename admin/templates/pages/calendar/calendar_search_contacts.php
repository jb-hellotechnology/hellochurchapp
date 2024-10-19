<?php

if(!perch_member_logged_in()){
	exit;
}

process_search_role_members(perch_get('q'), perch_get('eventID'), perch_get('date'), perch_get('roleID'));

?>