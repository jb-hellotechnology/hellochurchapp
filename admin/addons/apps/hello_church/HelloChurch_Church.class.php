<?php

class HelloChurch_Church extends PerchAPI_Base
{
    protected $table  = 'hellochurch_churches';
    protected $pk     = 'churchID';

    public $static_fields = array('churchID', 'memberID', 'churchName', 'churchSlug', 'churchAddress1', 'churchAddress2', 'churchCity', 'churchCounty', 'churchPostCode', 'churchCountry', 'churchPhone', 'churchEmail', 'churchWebsite', 'churchFacebook', 'churchInstagram', 'churchX', 'churchTikTok', 'churchExpires', 'churchCustomerID', 'churchSubscriptionID', 'churchPaymentMethod', 'churchPeriodEnd', 'churchCancel', 'churchPlanID', 'churchCost', 'churchProperites');
    
    public function update_stripe_id($stripeID){
		
		$API = new PerchAPI(1.0, 'hello_church');
		
		$out['churchCustomerID'] = $stripeID;
		
		$this->update($out);
		
	} 
	
	public function update_subscription_details(
					$subscription_id,
				    $payment_method,
				    $current_period_end,
				    $cancel,
				    $plan_id,
				    $cost
	){
		
		$API = new PerchAPI(1.0, 'hello_church');
		
		$out['churchSubscriptionID'] = $subscription_id;
		$out['churchPaymentMethod'] = $payment_method;
		$out['churchPeriodEnd'] = $current_period_end;
		$out['churchCancel'] = $cancel;
		$out['churchPlanID'] = $plan_id;
		$out['churchCost'] = $cost;
		
		$this->update($out);
		
	} 
	
	public function current_period_end(){

		$API = new PerchAPI(1.0, 'hello_church');
		echo $this->period();
		
	}

}
