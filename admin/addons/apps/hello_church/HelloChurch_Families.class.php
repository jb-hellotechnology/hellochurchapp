<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Families extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_families';
	protected $pk        = 'familyID';
	protected $singular_classname = 'HelloChurch_Family';

	protected $default_sort_column = 'familyName';

	public $static_fields = array('familyID', 'churchID', 'memberID', 'familyName', 'familyDescription');
    
    public function role_valid($data){
	    
	    return true;
	    
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
    
    public function check_owner($memberID, $familyID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_families WHERE memberID='".$memberID."' AND familyID='".$familyID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
    }
    
    public function family_byName($familyID, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_roles WHERE churchID='".$churchID."' AND familyName='".$familyID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }

}
