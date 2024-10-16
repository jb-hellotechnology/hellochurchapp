<?php

if(!perch_member_logged_in()){
	exit;
}

process_search_family_members(perch_get('q'), perch_get('memberID'));

?>