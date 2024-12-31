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

?>
<main>
	
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
		<?php perch_member_form('password.html'); ?>
	</section>
	
	<section>
		<header>
			<h2>Delete Account</h2>
		</header>
		<article class="flow">
			<p>Be careful. Deleting your account is permanent. All account information will be deleted. This cannot be reversed.</p>
		</article>
		<footer>
			<?php
			if($cancel OR !$customer_id){
				echo '<a class="button danger" href="/delete-account/">Delete Account</a>';
			}else{
				echo '<p>Please cancel your subscription before deleting your account.</p>';;
			}
			?>
		</footer>
	</section>

</main>

<?php perch_layout('footer'); ?>