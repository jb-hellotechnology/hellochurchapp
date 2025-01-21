<?php

class HelloChurch_Families extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_families';
	protected $pk        = 'familyID';
	protected $singular_classname = 'HelloChurch_Family';

	protected $default_sort_column = 'familyName';

	public $static_fields = array('familyID', 'churchID', 'memberID', 'familyName', 'familyDescription');
    
    public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i><li><ul><ol>');
		}
		
		return $clean;
	    
    }
    
	public function family($familyID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_families WHERE familyID='".$familyID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}
    
    public function families($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_families WHERE churchID='".$churchID."' ORDER BY familyName ASC";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function check_owner($churchID, $familyID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_families WHERE churchID='".$churchID."' AND familyID='".$familyID."'";
	    $results = $this->db->get_row($sql);
	    
	    if($results){
		    return true;
	    }else{
		    return false;
	    }
	    
    }
    
    public function family_byName($familyID, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_roles WHERE churchID='".$churchID."' AND familyName='".$familyID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }

}
