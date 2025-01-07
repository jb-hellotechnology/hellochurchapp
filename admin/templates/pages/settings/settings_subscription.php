<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require '../../../vendor/autoload.php';
require '../../../secrets.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

$customer_id = stripe_data('churchCustomerID');

if($customer_id){

	$cost = stripe_data('churchCost');
	$current_period = stripe_data('churchPeriodEnd');
	$cancel = stripe_data('churchCancel');
	$price = stripe_data('churchPlanID');
	
	$stripe = new \Stripe\StripeClient($stripeSK);
	
	if($_SERVER['REMOTE_ADDR']=="::1"){
		$return_url = 'http://hcapp:8888/settings/subscription';
	}else{
		$return_url = 'https://app.hellochurch.tech/settings/subscription';
	}
		
	$portal = $stripe->billingPortal->sessions->create([
		'customer' => $customer_id,
		'return_url' => $return_url,
	]);
	
	$payment_date = gmdate("d/m/Y", $current_period);
	
	if($cancel){
		$cancel_date = gmdate("d/m/Y", $cancel);
	}
	
	$cost = $cost/100;
	
	$price_data = $stripe->prices->retrieve($price, []);
	$currency = strtoupper($price_data->currency);

}
?>
<main class="flow">
	<h1>Subscription Settings</h1>
	<?php
		if(perch_get('success')==1){
	?>
	<p class="alert success">Success! Your subscription is active. Why not <a href="/contacts/add-contact">add your first contact</a>?</p>
	<?php
		}
	?>
	<section>
		<header>
			<h2>Manage Subscription</h2>
		</header>
		<article class="flow">
			<?php
				if(!$customer_id){
			?>
			<p><strong>Please setup your subscription.</p>
			<?php		
				}elseif($cancel){
			?>
			<p><strong>Your subscription is due to be cancelled on <?= $cancel_date ?>.</strong></p>
			<?php
				}elseif($current_period <= time()){
			?>
			<p><strong>Your subscription is inactive.</strong></p>
			<p><strong>Please click the button below to visit the payment portal.</strong></p>
			<?php
				}else{
			?>
			<p><strong>Your subscription is active.</strong></p>
			<p>You are paying <?= $cost ?><?= $currency ?> per month.</p>
			<p>Your next payment will be taken on <?= $payment_date ?>.</p>
			<?php
				}
			?>
		</article>
		<footer>
			<?php
				if($customer_id){
			?>
			<a class="button primary" href="<?= $portal->url ?>">Manage Subscription</a>
			<?php
				}else{
			?>
			<a class="button secondary" href="https://buy.stripe.com/test_7sI8z3gad6oDcjC7ss">Setup Subscription</a>
			<?php
				}
			?>
		</footer>
	</section>
</main>
<?php perch_layout('footer'); ?>