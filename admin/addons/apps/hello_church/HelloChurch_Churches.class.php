<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Churches extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_churches';
	protected $pk        = 'churchID';
	protected $singular_classname = 'HelloChurch_Church';

	protected $default_sort_column = 'churchName';

	public $static_fields = array('churchID', 'memberID', 'churchName', 'churchSlug', 'churchAddress1', 'churchAddress2', 'churchCity', 'churchCounty', 'churchPostCode', 'churchCountry', 'churchPhone', 'churchEmail', 'churchWebsite', 'churchFacebook', 'churchInstagram', 'churchX', 'churchTikTok', 'churchExpires', 'churchCustomerID', 'churchSubscriptionID', 'churchPaymentMethod', 'churchPeriodEnd', 'churchCancel', 'churchPlanID', 'churchCost', 'churchProperties');

    public $dynamic_fields_column = 'churchProperties';
    
    public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i><li><ul><ol>');
		}
		
		return $clean;
	    
    }
    
    public function church($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_churches WHERE churchID='".$churchID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
	}
	
	public function church_by_slug($slug){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_churches WHERE churchSlug='".$slug."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
	}
    
    public function church_valid($data){
	    
	    return true;
	    
    }
    
    function find_by_reference($reference){
		
		$sql = 'SELECT * FROM '.$this->table.' WHERE churchProperties LIKE "%\"reference\":'.$reference.'%"';
		$data = $this->db->get_rows($sql);
		return $data;
		
	}
	
	function find_by_customer_id($customer_id){
		
		$sql = 'SELECT * FROM '.$this->table.' WHERE churchCustomerID="'.$customer_id.'"';
		$data = $this->db->get_row($sql);
		return $data;
		
	}
    
    function current_period_end($id){
		$sql = 'SELECT * FROM '.$this->table.' WHERE churchID="'.$id.'"';
		$data = $this->db->get_row($sql);
		return $data['churchPeriodEnd'];
	}
	
	function get_stripe_data($id, $field){
		$sql = 'SELECT '.$field.' FROM '.$this->table.' WHERE churchID="'.$id.'"';
		$data = $this->db->get_row($sql);
		return $data[$field];
	}
	
	function public_search($q){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_churches WHERE churchName LIKE '%".$q."%'";
	    $results = $this->db->get_rows($sql);
   
	    if(count($results)>0){
		    $html = '<label>Click to Select Your Church:</label><ul>';
		    foreach($results as $result){
			    $html .= '<li><a href="#" data-slug="'.$result['churchSlug'].'">'.$result['churchName'].'</a></li>';
		    }
		    $html .= '</ul>';
	    }else{
		    $html = '<p class="error">No Results</p>';
	    }
	    
	    return $html;
	    
	}
	
	function delete_data($churchID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "DELETE FROM perch3_hellochurch_admins WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_audio WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_churches WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_contacts WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_contacts_tags WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_contact_notes WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_emails WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_events WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_event_plans WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_families WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_files WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_folders WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_groups WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_groups_members WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_groups_tags WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_podcasts WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_roles WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_roles_contacts WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_series WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_speakers WHERE churchID=$churchID";
		$this->db->execute($sql);
		
		$sql = "DELETE FROM perch3_hellochurch_venues WHERE churchID=$churchID";
		$this->db->execute($sql);
		
	}
	
	function no_subscriptions($id){
		
		$sql = "SELECT * FROM perch3_hellochurch_churches WHERE memberID=$id";
		$results = $this->db->get_rows($sql);
		if($results){
			return false;
		}else{
			return true;
		}
		
	}

}
