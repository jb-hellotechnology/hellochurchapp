<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

process_delete_contacts($_POST['contacts']);

?>