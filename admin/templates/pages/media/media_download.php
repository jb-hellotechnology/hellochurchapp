<?php

if(!perch_member_logged_in()){
	exit;
}

$Session = PerchMembers_Session::fetch();

$audioID = $_POST['audioID'];

process_download_audio($audioID);

?>