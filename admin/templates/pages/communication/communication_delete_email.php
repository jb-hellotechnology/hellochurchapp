<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require '../../../vendor/autoload.php';
require '../../../secrets.php';

$template = file_get_contents('../../../email_template.html');

if(!perch_member_logged_in()){
	header("location:/");
}

hello_church_delete_email(perch_get('id'));

header("location:/communication?msg=deleted");

?>