<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

$options = hello_church_get_populate_select(perch_get('type'));

echo $options;