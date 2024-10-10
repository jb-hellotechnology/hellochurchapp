<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main>
	<h1>Dashboard</h1>
</main>
<?php perch_layout('footer'); ?>