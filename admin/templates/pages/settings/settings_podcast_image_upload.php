<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

if(!perch_member_logged_in()){
	exit;
}

$Session = PerchMembers_Session::fetch();

$target_dir = "../../../../hc_uploads/".$Session->get('churchID')."/";

if(!is_dir($target_dir)){
	mkdir($target_dir, 0700);	
}

$time = time();

$target_dir = "../../../../hc_uploads/".$Session->get('churchID')."/podcast/";

if(!is_dir($target_dir)){
	mkdir($target_dir, 0700);	
}

$target_file = $target_dir . 'podcast.jpg';
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

// Check file size
if ($_FILES["file"]["size"] > 50000000) {
  //echo "Sorry, your file is too large. ";
  $uploadOk = 0;
  echo "Sorry, your file was not uploaded. Error Code: File size too large";
  exit;
}

// Allow certain file formats
if($documentFileType !== "jpg" && $documentFileType !== "jpeg") {
  //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
  echo "Sorry, your file was not uploaded. Error Code: Incorrect file type";
}

if($uploadOk == 1){
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		
		$source = imagecreatefromjpeg($target_file);
		list($width, $height) = getimagesize($target_file);
		
		// Define new dimensions (200x200 pixels)
		$newWidth = 1920;
		$newHeight = 1920;
		
		// Create a new image
		$thumb = imagecreatetruecolor($newWidth, $newHeight);
		
		// Resize
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		
		// Save the resized image
		imagejpeg($thumb, $target_file, 100);

	    echo "Success";
	} else {
	    echo "Sorry, your file was not uploaded. Error Code: Could not move file";
	}
}


?>