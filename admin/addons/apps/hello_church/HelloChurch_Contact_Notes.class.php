<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Contact_Notes extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_contact_notes';
	protected $pk        = 'noteID';
	protected $singular_classname = 'HelloChurch_Contact_Note';

	protected $default_sort_column = 'timestamp';

	public $static_fields = array('noteID', 'memberID', 'churchID', 'contactID', 'timestamp', 'note');
	
	public function by_contactID($contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contact_notes WHERE contactID='".$contactID."'";
	    $results = $this->db->get_rows($sql);
	    return $results;

	}
	
	public function by_noteID($noteID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contact_notes WHERE noteID='".$noteID."'";
	    $result = $this->db->get_row($sql);
	    return $result;

	}
	
	public function check_owner($memberID, $noteID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contact_notes WHERE memberID='".$memberID."' AND noteID='".$noteID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
    }
    
}
