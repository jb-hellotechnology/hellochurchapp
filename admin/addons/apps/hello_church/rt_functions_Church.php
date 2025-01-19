<?php
	
	/** GET CHURCH DETAILS **/
	function hello_church_church_public($churchID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$church = $HelloChurchChurches->church($churchID);
		
		return $church;
		
	}
	
	/** GET CHURCH BY SLUG **/
	function church_by_slug($slug){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$church = $HelloChurchChurches->church_by_slug($slug);
		
		return $church;
		
	}
	
	/** GET CHURCH DATA AND RETURN IT OR RETURN TRUE/FALSE DEPENDING ON INPUT **/
	function hello_church_church($return){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$church = $HelloChurchChurches->church(perch_member_get('churchID'));
		
		if($return){
			return $church;
		}else{
			if($church){
				return true;
			}else{
				return false;
			}
		}
		
	}
	
	/** CHURCH NAME SEARCH BASED ON TEXT INPUT **/
	function search_church($q){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchChurches = new HelloChurch_Churches($API);
	    
		$results = $HelloChurchChurches->public_search($q);
		
		echo $results;
	    
    }
    	
	/** SUBSCRIPTION FUNCTIONS **/
	
	/** GET CHURCH SUBSCRIPTION PERIOD END **/
	function church_subscription_period(){
	    $API  = new PerchAPI(1.0, 'hello_church');
		$Session = PerchMembers_Session::fetch();
		$Churches = new HelloChurch_Churches($API);
		return $Churches->current_period_end(perch_member_get('churchID'));
    }
    
    /** RETURN STRIPE DATA BASED ON INPUT **/
    function stripe_data($field){
	    $API  = new PerchAPI(1.0, 'hello_church');
		$Session = PerchMembers_Session::fetch();
		$Churches = new HelloChurch_Churches($API);
		return $Churches->get_stripe_data(perch_member_get('churchID'), $field);
    }

	/** UPDATE STRIPE CUSTOMER ID AND REFRESH SESSION DATA **/
	function church_update_stripe_id($reference, $church_id){
		$API  = new PerchAPI(1.0, 'hello_church');
		$Session = PerchMembers_Session::fetch();
		$Churches = new HelloChurch_Churches($API);
		$Church = new HelloChurch_Church($API);

		if(is_object($Churches)){
			$Church = $Churches->find_by_reference($reference);
			$Church = $Churches->find($Church[0]['churchID']);
        	if(is_object($Church)) {
				$Church->update_stripe_id($church_id);
				$PerchMembers_Auth = new PerchMembers_Auth($API);
                $PerchMembers_Auth->refresh_session_data($Member);
			}
		}
	}
	
	/** UPDATE STRIPE SUBSCRIPTION ID AND REFRESH SESSION DATA **/
	function church_update_subscription_id($id){
		$API  = new PerchAPI(1.0, 'hello_church');
		$Session = PerchMembers_Session::fetch();
		$Churches = new HelloChurch_Churches($API);
		$Church = new HelloChurch_Church($API);

		if(is_object($Churches)){
			$Church = $Churches->find(perch_member_get('churchID'));
        	if(is_object($Church)) {
				$Church->update_subscription_id(perch_member_get('churchID'), $id);
				$PerchMembers_Auth = new PerchMembers_Auth($API);
                $PerchMembers_Auth->refresh_session_data($Member);
			}
		}
	}
	
	/** UPDATE STRIPE SUBSCRIPTION DETAILS **/
	function church_update_subscription_details(
	    $customer_id,
	    $subscription_id,
	    $payment_method,
	    $current_period_end,
	    $cancel,
	    $plan_id,
	    $cost
    ){
	    $API  = new PerchAPI(1.0, 'hello_church');
		$Session = PerchMembers_Session::fetch();
		$Churches = new HelloChurch_Churches($API);
		$Church = new HelloChurch_Church($API);

		if(is_object($Churches)){
			$Church = $Churches->find_by_customer_id($customer_id);
			$Church = $Churches->find($Church['churchID']);
        	if(is_object($Church)) {
				$Church->update_subscription_details(
					$subscription_id,
				    $payment_method,
				    $current_period_end,
				    $cancel,
				    $plan_id,
				    $cost
				);
                $PerchMembers_Auth = new PerchMembers_Auth($API);
                $PerchMembers_Auth->refresh_session_data($Member);
			}
		}
    }
    
    /** UPDATE CURRENT SESSION CHURCH ID **/
    function hello_church_update_church_session($churchID){
	    $API  = new PerchAPI(1.0, 'hello_church');
	    $PerchMembers_Auth = new PerchMembers_Auth($API);
        $PerchMembers_Auth->update_church_session($churchID);
	    
    }
    
?>