<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Roles extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_roles';
	protected $pk        = 'roleID';
	protected $singular_classname = 'HelloChurch_Role';

	protected $default_sort_column = 'roleName';

	public $static_fields = array('roleID', 'churchID', 'memberID', 'roleName', 'roleDescription', 'roleType');
    
    public function role_valid($data){
	    
	    return true;
	    
    }
    
	public function role($roleID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_roles WHERE roleID='".$roleID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}
    
    public function roles($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_roles WHERE churchID='".$churchID."' ORDER BY roleName ASC";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function check_owner($churchID, $roleID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_roles WHERE churchID='".$churchID."' AND roleID='".$roleID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
    }
    
    public function role_byName($roleID, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_roles WHERE churchID='".$churchID."' AND roleName='".$roleID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }

}
