<?php

if(!perch_member_logged_in()){
	exit;
}

$Session = PerchMembers_Session::fetch();

$target_dir = "../../../../hc_uploads/".$Session->get('churchID')."/";

$audioName = strip_tags($_POST['audioName']);
$audioDate = strip_tags($_POST['audioDate']);
$audioDescription = strip_tags($_POST['audioDescription']);
$audioSpeaker = strip_tags($_POST['audioSpeaker']);
$audioSeries = strip_tags($_POST['audioSeries']);
$audioBible = strip_tags($_POST['audioBible']);

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
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["file"]["size"] > 500000000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($documentFileType != "mp3") {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
	process_audio_upload($audioName, $audioDate, $audioDescription, $audioSpeaker, $audioSeries, $audioBible, $_FILES["file"]["name"]);
    echo "Success";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}


?>