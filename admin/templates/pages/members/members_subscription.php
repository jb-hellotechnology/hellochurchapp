<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require '../../../vendor/autoload.php';
include('../../../secrets.php');
perch_layout('header');

$customerID = stripe_data('churchCustomerID');

$stripe = new \Stripe\StripeClient($stripeSK);

$stripeData = $stripe->customerSessions->create([
  'customer' 	=> $customerID,  
  'components' 	=> ['buy_button' => ['enabled' => true]]
]);
?>
<main class="wide center full">
	<h2>Start Your Free Trial</h2>
	<script async
	  src="https://js.stripe.com/v3/buy-button.js">
	</script>
	
	<stripe-buy-button
	  buy-button-id="buy_btn_1QbhPHLRT7b4Pi1GVosP2yh6"
	  publishable-key="<?= $stripePK ?>"
	  customer-session-client-secret="<?= $stripeData->client_secret ?>">
	>
	</stripe-buy-button>
</main>
<?php
perch_layout('footer');
?>