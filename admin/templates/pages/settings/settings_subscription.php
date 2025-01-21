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

$adminType = admin_type();
?>
<main class="flow">
	<?php
		if($adminType=='owner'){
	?>
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Subscription Settings</h1>
	<?php
		if(perch_get('success')==1){
			
			// Configure API key authorization: api-key
			$config = Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key',  $brevoAPI);
			
			$apiInstance = new Brevo\Client\Api\ContactsApi(
			    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
			    // This is optional, `GuzzleHttp\Client` will be used as default.
			    new GuzzleHttp\Client(),
			    $config
			);
			$createContact = new \Brevo\Client\Model\CreateContact([
			     'email' => perch_member_get('email'),
			     'updateEnabled' => true,    
			     'listIds' =>[[5]]
			]); // \Brevo\Client\Model\CreateContact | Values to create a contact
			
			try {
			    $result = $apiInstance->createContact($createContact);
			    print_r($result);
			} catch (Exception $e) {
			    echo 'Exception when calling ContactsApi->createContact: ', $e->getMessage(), PHP_EOL;
			}
			
	?>
	<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Your subscription is active</p>
	<h2>What next?</h2>
	<ul>
		<li><a href="/contacts">Add a Contact?</a></li>
		<li><a href="/calendar">Manage your Calendar?</a></li>
		<li><a href="/dashboar">Something else?</a></li>
	</ul>
	<br />
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
	<?php
		}else{
			echo '<h1>Owner Access Only</h1><p>Profile administrators cannot access this page for security reasons.</p>';	
		}	
	?>
</main>
<?php perch_layout('footer'); ?>