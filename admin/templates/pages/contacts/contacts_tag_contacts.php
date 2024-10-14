<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

process_tag_contacts($_POST['contacts'], $_POST['tag']);

?>