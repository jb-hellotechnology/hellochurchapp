<?php
include('../../../secrets.php');
if(perch_member_logged_in()){
	header('location:/subscription');
}
perch_layout('header');
PerchSystem::set_var('hcaptchaSiteKey', $hcaptchaSiteKey);
?>
<main class="narrow flow">
	<h1>Get Started ⌨️</h1>
	<ul class="checks">
		<li>
			<span class="material-symbols-outlined">check_circle</span>
			<div><strong>30 Day Free Trial</strong><br />Plenty of time to try Hello Church</div>
		</li>
		<li>
			<span class="material-symbols-outlined">check_circle</span>
			<div><strong>Helpful Support</strong><br />Our friendly team are here to help</div>
		</li>
		<li>
			<span class="material-symbols-outlined">check_circle</span>
			<div><strong>Useful Tools</strong><br />To help you communicate effectively</div>
		</li>
	</ul>
	<section>
		<header>
			<h2>Register</h2>
		</header>
		<?php perch_member_form('register.html'); ?>
	</section>
	<p>Have an account? <a href="/">Sign in</a>.</p>
</main>
<?php
perch_layout('footer');
?>