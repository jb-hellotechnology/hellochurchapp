<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main>
	<h1>Get Started</h1>
	<p>Things to do here...</p>
</main>
<?php perch_layout('footer'); ?>