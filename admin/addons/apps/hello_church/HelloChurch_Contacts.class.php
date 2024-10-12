<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Contacts extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_contacts';
	protected $pk        = 'contactID';
	protected $singular_classname = 'HelloChurch_Contact';

	protected $default_sort_column = 'contactFirstName';

	public $static_fields = array('contactID', 'churchID', 'memberID', 'contactFirstName', 'contactLastName', 'contactEmail', 'contactPhone', 'contactAddress1', 'contactAddress2', 'contactCity', 'contactCounty', 'contactPostCode', 'contactCountry', 'contactAcceptEmail', 'contactAcceptSMS', 'contactTags', 'contactProperites');

    public $dynamic_fields_column = 'contactProperties';
    
    public function contact_valid($data){
	    
	    return true;
	    
    }
    
	public function contact($contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts WHERE contactID='".$contactID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}
    
    public function contacts($memberID, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts WHERE memberID='".$memberID."' AND churchID='".$churchID."' ORDER BY contactLastName ASC";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function check_owner($memberID, $contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts WHERE memberID='".$memberID."' AND contactID='".$contactID."'";
	    $results = $this->db->get_row($sql);
	    if(count($results)==1){
		    return true;
	    }else{
		    return false;
	    }
	    
    }

}
