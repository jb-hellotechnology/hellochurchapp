<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Events extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_events';
	protected $pk        = 'eventID';
	protected $singular_classname = 'HelloChurch_Event';

	protected $default_sort_column = 'start';

	public $static_fields = array('eventID', 'churchID', 'memberID', 'eventName', 'eventDescription', 'allDay', 'start', 'end', 'repeatEvent', 'repeatEnd', 'roles');
	
	public function events($churchID){
		
		$sql = "SELECT * FROM perch3_hellochurch_events WHERE churchID='".$churchID."' ORDER BY start ASC";
	    $results = $this->db->get_rows($sql);
	    
	    return $results;
	    
	}
	
	public function check_owner($memberID, $eventID){

		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_events WHERE memberID='".$memberID."' AND eventID='".$eventID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
	}
	
	public function event($eventID){
		
		$sql = "SELECT * FROM perch3_hellochurch_events WHERE eventID='".$eventID."'";
	    $results = $this->db->get_row($sql);
	    
	    return $results;
	    
	}
	
	public function event_roles($eventID){
		
		$sql = "SELECT roles FROM perch3_hellochurch_events WHERE eventID='".$eventID."'";
	    $results = $this->db->get_row($sql);
	    
	    return $results;
	    
	}
	
	public function add_role_contact($memberID, $churchID, $eventID, $eventDate, $contactID, $roleID){
		
		$sql = "INSERT INTO perch3_hellochurch_roles_contacts (memberID, churchID, eventID, eventDate, contactID, roleID) VALUES 
		('$memberID', '$churchID', '$eventID', '$eventDate', '$contactID', '$roleID')";
		$result = $this->db->execute($sql); 
		
	}
	
	public function event_contact_roles($eventID, $eventDate, $roleID){
		
		$date = explode(" ", $eventDate);
		
		$sql = "SELECT * FROM perch3_hellochurch_roles_contacts WHERE eventID='".$eventID."' AND eventDate='".$date[0]."' AND roleID='".$roleID."'";
	    $results = $this->db->get_rows($sql);
	    
	    return $results;
	    
	}
	
	public function remove_role_contact($roleContactID){
		
		$sql = "DELETE FROM perch3_hellochurch_roles_contacts WHERE roleContactID='$roleContactID'";
		echo $sql;
		$result = $this->db->execute($sql); 
		
	}

}
