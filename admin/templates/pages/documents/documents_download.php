<?php

if(!perch_member_logged_in()){
	exit;
}

$Session = PerchMembers_Session::fetch();

$id = $_POST['id'];
$fileID = $_POST['fileID'];

process_download_file($fileID);

?>