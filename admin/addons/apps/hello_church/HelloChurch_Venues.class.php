<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Venues extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_venues';
	protected $pk        = 'venueID';
	protected $singular_classname = 'HelloChurch_Venue';

	protected $default_sort_column = 'venueName';

	public $static_fields = array('venueID', 'churchID', 'memberID', 'venueName', 'venueDescription');
    
    public function venue_valid($data){
	    
	    return true;
	    
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
    
    public function check_owner($memberID, $venueID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_roles WHERE memberID='".$memberID."' AND venueID='".$venueID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
    }
    
    public function venue_byName($venueID, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_roles WHERE churchID='".$churchID."' AND venueName='".$venueID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }

}
