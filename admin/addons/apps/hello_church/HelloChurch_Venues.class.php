<?php

class HelloChurch_Venues extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_venues';
	protected $pk        = 'venueID';
	protected $singular_classname = 'HelloChurch_Venue';

	protected $default_sort_column = 'venueName';

	public $static_fields = array('venueID', 'churchID', 'memberID', 'venueName', 'venueDescription');
    
    public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i>');
		}
		
		return $clean;
	    
    }
    
	public function venue($venueID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_venues WHERE venueID='".$venueID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}
    
    public function venues($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_venues WHERE churchID='".$churchID."' ORDER BY venueName ASC";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function check_owner($churchID, $venueID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_venues WHERE churchID='".$churchID."' AND venueID='".$venueID."'";
	    $results = $this->db->get_row($sql);

	    if($results){
		    return true;
	    }else{
		    return false;
	    }
	    
    }
    
    public function venue_byName($venueID, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_roles WHERE churchID='".$churchID."' AND venueName='".$venueID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }

}
