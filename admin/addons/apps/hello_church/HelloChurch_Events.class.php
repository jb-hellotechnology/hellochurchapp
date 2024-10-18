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

	public $static_fields = array('eventID', 'churchID', 'memberID', 'eventName', 'eventDescription', 'allDay', 'start', 'end', 'repeatEvent', 'repeatEnd');
	
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

}
