<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

$planID = $_POST['planID'];
$date = $_POST['date'];
$time = $_POST['time'];
$plan = $_POST['plan'];

process_save_plan($planID, $date, $time, $plan);