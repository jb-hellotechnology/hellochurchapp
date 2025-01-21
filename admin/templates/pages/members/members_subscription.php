<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require '../../vendor/autoload.php';
include('../../secrets.php');
perch_layout('header');

echo $brevoAPI;

$customerID = stripe_data('churchCustomerID');

$stripe = new \Stripe\StripeClient($stripeSK);

$stripeData = $stripe->customerSessions->create([
  'customer' 	=> $customerID,  
  'components' 	=> ['buy_button' => ['enabled' => true]]
]);

?>
<main class="wide center full">
	<h2>Start Your Free Trial</h2>
	<p>No Card Required &bull; 30 Days Free</p>
	<br />
	
	<label>Select Your Preferred Currency</label>
	<select id="currency">
		<option value="gbp" data-button="<?= $gbp ?>">GBP</option>
		<option value="usd" data-button="<?= $usd ?>">USD</option>
		<option value="eur" data-button="<?= $eur ?>">EUR</option>
		<option value="cad" data-button="<?= $cad ?>">CAD</option>
		<option value="aud" data-button="<?= $aud ?>">AUD</option>
		<option value="chf" data-button="<?= $chf ?>">CHF</option>
	</select>

	<br />
	<br />
		
	<script async
	  src="https://js.stripe.com/v3/buy-button.js">
	</script>
	
	<div class="currency gbp">
		<stripe-buy-button
		  buy-button-id="<?= $gbp ?>"
		  publishable-key="<?= $stripePK ?>"
		  customer-session-client-secret="<?= $stripeData->client_secret ?>">
		>
		</stripe-buy-button>
	</div>
	<div class="currency hide usd">
		<stripe-buy-button
		  buy-button-id="<?= $usd ?>"
		  publishable-key="<?= $stripePK ?>"
		  customer-session-client-secret="<?= $stripeData->client_secret ?>">
		>
		</stripe-buy-button>
	</div>
	<div class="currency hide eur">
		<stripe-buy-button
		  buy-button-id="<?= $eur ?>"
		  publishable-key="<?= $stripePK ?>"
		  customer-session-client-secret="<?= $stripeData->client_secret ?>">
		>
		</stripe-buy-button>
	</div>
	<div class="currency hide cad">
		<stripe-buy-button
		  buy-button-id="<?= $cad ?>"
		  publishable-key="<?= $stripePK ?>"
		  customer-session-client-secret="<?= $stripeData->client_secret ?>">
		>
		</stripe-buy-button>
	</div>
	<div class="currency hide aud">
		<stripe-buy-button
		  buy-button-id="<?= $aud ?>"
		  publishable-key="<?= $stripePK ?>"
		  customer-session-client-secret="<?= $stripeData->client_secret ?>">
		>
		</stripe-buy-button>
	</div>
	<div class="currency hide chf">
		<stripe-buy-button
		  buy-button-id="<?= $chf ?>"
		  publishable-key="<?= $stripePK ?>"
		  customer-session-client-secret="<?= $stripeData->client_secret ?>">
		>
		</stripe-buy-button>
	</div>
</main>
<?php
perch_layout('footer');
?>