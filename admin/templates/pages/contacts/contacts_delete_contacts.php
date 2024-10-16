<?php

if(!perch_member_logged_in()){
	exit;
}

process_delete_contacts(perch_get('contacts'));

?>