<?php

if(!perch_member_logged_in()){
	exit;
}

$Session = PerchMembers_Session::fetch();

$target_dir = "../../../../hc_uploads/".$Session->get('churchID')."/";

if(!is_dir($target_dir)){
	mkdir($target_dir, 0700);	
}

$time = time();

$target_dir = "../../../../hc_uploads/".$Session->get('churchID')."/".$time."/";

if(!is_dir($target_dir)){
	mkdir($target_dir, 0700);	
}

$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$documentFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["file"]["tmp_name"]);
  if($check !== false) {
    //echo "File is an image - " . $check["mime"] . ". ";
    $uploadOk = 1;
  } else {
    //echo "File is not an image. ";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  //echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["file"]["size"] > 50000000) {
  //echo "Sorry, your file is too large. ";
  $uploadOk = 0;
}

// Allow certain file formats
if($documentFileType !== "doc" && $documentFileType !== "docx" && $documentFileType !== "xls" && $documentFileType !== "xlsx" && $documentFileType !== "pdf" && $documentFileType !== "pages" && $documentFileType !== "numbers" && $documentFileType !== "key" && $documentFileType !== "ppt" && $documentFileType !== "pptx" && $documentFileType !== "odt" && $documentFileType !== "txt" && $documentFileType !== "csv" && $documentFileType !== "odp" && $documentFileType !== "zip" && $documentFileType !== "jpg" && $documentFileType !== "jpeg" && $documentFileType !== "png") {
  //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded. Error Code: 1";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
	process_file_upload($_POST['folderID'], $_POST['contactID'], $_POST['groupID'], $_POST['eventID'], $_POST['eventDate'], $time."/".$_FILES["file"]["name"]);
    echo "Success";
  } else {
    echo "Sorry, your file was not uploaded. Error Code: 2";
  }
}


?>