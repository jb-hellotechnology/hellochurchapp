<?php
	
require '../../../vendor/autoload.php';
require_once '../../../secrets.php';

\Stripe\Stripe::setApiKey($stripePK);

// Replace this endpoint secret with your endpoint's unique secret
// If you are testing with the CLI, find the secret by running 'stripe listen'
// If you are using an endpoint defined with the API or dashboard, look in your webhook settings
// at https://dashboard.stripe.com/webhooks
$endpoint_secret = 'whsec_12345';

$payload = @file_get_contents('php://input');
$event = null;
try {
  $event = \Stripe\Event::constructFrom(
    json_decode($payload, true)
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  echo 'Webhook error while parsing basic request.';
  http_response_code(400);
  exit();
}

// Handle the event
switch ($event->type) {
  case 'checkout.session.completed':
  	$checkout = $event->data->object;
  	$reference = $event->data->object->client_reference_id;
  	$customer_id = $event->data->object->customer;
  	church_update_stripe_id($reference, $customer_id);
    break;
  case 'customer.subscription.updated':
    $subscription = $event->data->object;
    $customer_id = $event->data->object->customer;
    $subscription_id = $event->data->object->id;
    $payment_method = $event->data->object->default_payment_method;
    $current_period_end = $event->data->object->current_period_end;
    $cancel = $event->data->object->cancel_at;
    $plan_id = $event->data->object->items->data[0]->plan->id;
    $cost = $event->data->object->items->data[0]->plan->amount_decimal;
    church_update_subscription_details(
	    $customer_id,
	    $subscription_id,
	    $payment_method,
	    $current_period_end,
	    $cancel,
	    $plan_id,
	    $cost
    );
    break;
  case 'customer.subscription.created':
    $subscription = $event->data->object;
    $customer_id = $event->data->object->customer;
    $subscription_id = $event->data->object->id;
    $payment_method = $event->data->object->default_payment_method;
    $current_period_end = $event->data->object->current_period_end;
    $cancel = $event->data->object->cancel_at;
    $plan_id = $event->data->object->items->data[0]->plan->id;
    $cost = $event->data->object->items->data[0]->plan->amount_decimal;
    print_r($event);
    church_update_subscription_details(
	    $customer_id,
	    $subscription_id,
	    $payment_method,
	    $current_period_end,
	    $cancel,
	    $plan_id,
	    $cost
    );
    break;
  default:
    // Unexpected event type
    echo 'Received unknown event type';
}
?>