<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_member_add_tag('onboarded');
perch_layout('header');
?>
<main class="flow">
	<h1>Get Started 🚀</h1>
	<p><strong>Please add a few details about your church or organisation using the form below.</strong></p>
	<section>
		<header>
			<h2>Get Started</h2>
		</header>
		<?php ?>
	</section>
</main>
<?php perch_layout('footer'); ?>