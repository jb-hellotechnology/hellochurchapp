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
	<p class="alert error"><span class="material-symbols-outlined">error</span> Sign in failed</p>
	<p><a class="button primary" href="/profile/">Try Again</a></p>
</main>
<?php perch_layout('footer_public'); ?>