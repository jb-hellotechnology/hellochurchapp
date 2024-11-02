<?php

if(!perch_member_logged_in()){
	exit;
}

$Session = PerchMembers_Session::fetch();

$id = $_POST['id'];
$fileID = $_POST['fileID'];

process_delete_file($fileID);

$redirect = '/documents?msg=file_deleted';

if($id){
	$redirect .= '&id='.$id;
}

header("location:$redirect");


?>