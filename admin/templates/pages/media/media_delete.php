<?php

if(!perch_member_logged_in()){
	exit;
}

$Session = PerchMembers_Session::fetch();

$audioID = $_POST['fileID'];

process_delete_audio($audioID);

$redirect = '/media?msg=audio_deleted';

header("location:$redirect");


?>