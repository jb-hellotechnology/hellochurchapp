<?php
	session_start();
	session_destroy();
	perch_member_log_out();
	header("location:/");
?>