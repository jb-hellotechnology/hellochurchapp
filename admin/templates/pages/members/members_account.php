<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

// perch_members_refresh_session_data();

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

$customer_id = stripe_data('memberCustomerID');

if(!$customer_id){
	header("location:/subscription/");
}

$cost = stripe_data('memberCost');
$current_period = stripe_data('memberPeriodEnd');
$cancel = stripe_data('memberCancel');

$stripe = new \Stripe\StripeClient('sk_test_Un6FttdKkdEITbzaVOFU2wrJ');
	
$portal = $stripe->billingPortal->sessions->create([
	'customer' => $customer_id,
	'return_url' => 'https://app.hellochurch.tech/account/',
]);

$payment_date = gmdate("d/m/Y", $current_period);

if($cancel){
	$cancel_date = gmdate("d/m/Y", $cancel);
}

$cost = $cost/100;

?>
<main>
	
	<h1>Account</h1>
	
	<section>
		<header>
			<h2>Manage Subscription</h2>
		</header>
		<article class="flow">
			<?php
				if($cancel){
			?>
			<p><strong>Your subscription is due to be cancelled on <?= $cancel_date ?>.</strong></p>
			<?php
				}else{
			?>
			<p><strong>Your subscription is active.</strong></p>
			<p>You are paying &pound;<?= $cost ?>/month.</p>
			<p>Your next payment will be take on <?= $payment_date ?>.</p>
			<?php
				}
			?>
		</article>
		<footer>
			<a class="button primary" href="<?= $portal->url ?>">Manage Subscription</a>
		</footer>
	</section>
	
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
			if($cancel){
				echo '<a class="button danger" href="/delete-account/">Delete Account</a>';
			}else{
				echo '<p>Please cancel your subscription before deleting your account.</p>';;
			}
			?>
		</footer>
	</section>

</main>

<?php perch_layout('footer'); ?>