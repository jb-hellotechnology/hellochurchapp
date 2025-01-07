<?php

require '../../../vendor/autoload.php';
include('../../../secrets.php');

perch_layout('header_public');

sign_in_magic(perch_get('p'), perch_get('e'));

if(signed_in()){
	header("location:/profile");
	exit;
}
?>
<main class="flow public narrow">
	<h1>Error</h1>
	<p class="alert error">Sign in failed. Please <a href="/profile">try again</a>.</p>
</main>
<?php perch_layout('footer_public'); ?>