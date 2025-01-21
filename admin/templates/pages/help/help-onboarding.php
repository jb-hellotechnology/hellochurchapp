<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow full">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Onboarding</h1>
	<p>Need some advice? Want help importing your contacts? Book a session with Jack:
	<iframe frameborder="0" width="100%" height="720" src="https://meet.brevo.com/hello-church-tech/borderless?l=hello-church-onboarding"></iframe>
</main>
<?php perch_layout('footer'); ?>