<?php

if(!perch_member_logged_in()){
	exit;
}

process_tag_contacts(perch_get('contacts'), perch_get('tag'));

?>