<?php

class HelloChurch_Speakers extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_speakers';
	protected $pk        = 'speakerID';
	protected $singular_classname = 'HelloChurch_Speaker';

	protected $default_sort_column = 'speakerName';

	public $static_fields = array('speakerID', 'churchID', 'memberID', 'speakerName');
    
    public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i><li><ul><ol>');
		}
		
		return $clean;
	    
    }
    
	public function speaker($speakerID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_speakers WHERE speakerID='".$speakerID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}
    
    public function speakers($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_speakers WHERE churchID='".$churchID."' ORDER BY speakerName ASC";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function check_owner($churchID, $speakerID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_speakers WHERE churchID='".$churchID."' AND speakerID='".$speakerID."'";
	    $results = $this->db->get_rows($sql);
	    
	    if($results){
		    return true;
	    }else{
		    return false;
	    }
	    
    }
    
    public function speaker_byName($speaker, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_speakers WHERE churchID='".$churchID."' AND speakerName='".$speaker."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }

}
