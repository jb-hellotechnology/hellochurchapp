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
	
	<section>
		<header>
			<h2>Delete Account</h2>
		</header>
		<article class="flow">
			<p><strong>Be careful.</strong> Deleting your account is permanent. All account information will be deleted. This cannot be reversed.</p>
			<?php
			if(!$subscriptionCancel){
				echo '<p>Please cancel your subscription before deleting your account.</p>';;
			}
			?>
		</article>
		<footer>
			<?php
			if($subscriptionCancel){
				echo '<a class="button danger" href="/delete-account/">Delete Account</a>';
			}else{
				echo '<a class="button primary" href="/settings/subscription">Manage Subscription</a>';
			}
			?>
		</footer>
	</section>

</main>

<?php perch_layout('footer'); ?>