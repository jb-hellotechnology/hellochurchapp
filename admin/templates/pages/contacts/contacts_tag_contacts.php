<?php

if(!perch_member_logged_in()){
	exit;
}

$contacts = $_POST['contacts'];
$tag = strip_tags(addslashes(trim($_POST['tag'])));

process_tag_contacts($contacts, $tag);

?>