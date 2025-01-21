<?php

class HelloChurch_Contact_Notes extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_contact_notes';
	protected $pk        = 'noteID';
	protected $singular_classname = 'HelloChurch_Contact_Note';

	protected $default_sort_column = 'timestamp';

	public $static_fields = array('noteID', 'memberID', 'churchID', 'contactID', 'timestamp', 'note');
	
	public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i><li><ul><ol>');
		}
		
		return $clean;
	    
    }
	
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
	
	public function check_owner($churchID, $noteID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contact_notes WHERE churchID='".$churchID."' AND noteID='".$noteID."'";
	    $results = $this->db->get_rows($sql);
	    
	    if($results){
		    return true;
	    }else{
		    return false;
	    }
	    
    }
    
}
