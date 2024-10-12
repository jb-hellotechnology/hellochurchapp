<?php
	if ($CurrentUser->logged_in()) {
	    $this->register_app('hello_church', 'Hello Church', 2, 'Manage Hello Church data', '0.0.1');
	}

	spl_autoload_register(function($class_name){
    	if (strpos($class_name, 'HelloChurch')===0) {
    		include(PERCH_PATH.'/addons/apps/hello_church/'.$class_name.'.class.php');
    		return true;
    	}
    	return false;
    });