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
	<!-- static button start -->
	<link href="https://meetings.brevo.com/assets/styles/popup.css" rel="stylesheet" />
	<script src="https://meetings.brevo.com/assets/libs/popup.min.js" type="text/javascript"></script>
	<a href="" onclick="BrevoBookingPage.initStaticButton({ url: 'https://meet.brevo.com/hello-church-tech/borderless?l=hello-church-onboarding' });return false;" style="cursor: pointer; font-family: Inter; font-weight: 500; background-color: #1B1B1B; color: white; padding: 0.5rem 1rem; border: 0px; box-shadow: rgba(0, 0, 0, 0.15) 0px -2px 0px inset; border-radius: 16px; text-decoration: none; display: inline-block;">Book Now</a>
	<!-- static button end -->
</main>
<?php perch_layout('footer'); ?>