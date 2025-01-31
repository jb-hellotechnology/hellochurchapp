<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

$subscriptionCancel = stripe_data('churchCancel');

?>
<main class="flow">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Account</h1>
	
	<section>
		<header>
			<h2>Update Profile</h2>
		</header>
		<?php perch_member_form('profile.html'); ?>
	</section>
	
	<section>
		<header>
			<h2>Change Password</h2>
		</header>
		<article class="flow">
			<p>Need to update your password? Click the button.</p>
		</article>
		<footer>
			<a class="button primary" href="/account/password">Change Password</a>
		</footer>
	</section>
	
	<?php
	if(hello_church_no_subscriptions(perch_member_get('id'))){
	?>
	<section>
		<header>
			<h2>Delete Account</h2>
		</header>
		<article class="flow">
			<p><strong>Be careful.</strong> Deleting your account is permanent. All account information will be deleted. This cannot be reversed.</p>
		</article>
		<footer>
			<a class="button danger" href="/delete-account/">Delete Account</a>
		</footer>
	</section>
	<?php
	}else{
	?>
	<div class="panel">
		<p><strong>Delete Account</strong></p>
		<p>Before deleting your account your must cancel all <a href="/settings/subscription">existing subscriptions</a> and delete the church profiles associated with your account.</p>
	</div>
	<?php
	}
	?>
</main>

<?php perch_layout('footer'); ?>