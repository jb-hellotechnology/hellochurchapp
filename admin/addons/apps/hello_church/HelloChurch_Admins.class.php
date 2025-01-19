<?php

class HelloChurch_Admins extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_admins';
	protected $pk        = 'adminID';
	protected $singular_classname = 'HelloChurch_Admin';

	protected $default_sort_column = 'adminEmail';

	public $static_fields = array('adminID', 'churchID', 'memberID', 'adminEmail', 'adminCode');
    
    public function role_valid($data){
	    
	    return true;
	    
    }
    
	public function admin($adminID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_admins WHERE adminID='".$adminID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}
    
    public function admins($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_admins WHERE churchID='".$churchID."' ORDER BY adminEmail ASC";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function check_owner($churchID, $adminID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_admins WHERE churchID='".$churchID."' AND adminID='".$adminID."'";
	    $results = $this->db->get_rows($sql);
	    
	    if($results){
		    return true;
	    }else{
		    return false;
	    }
	    
    }
    
    public function has_admin_rights($memberID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_members WHERE memberID='".$memberID."'";
	    $result = $this->db->get_row($sql);

	    if($result){
		    
		    $sql2 = "SELECT * FROM perch3_hellochurch_admins WHERE adminEmail='".$result['memberEmail']."'";
		    $result2 = $this->db->get_rows($sql2);

		    if($result2){
			    return true;
		    }else{
			    return false;
		    }
		    
	    }

    }
    
    public function admin_options($memberID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $churches = array();
	    
	    $sql = "SELECT * FROM perch3_hellochurch_churches WHERE memberID='".$memberID."'";
	    $results = $this->db->get_rows($sql);
	    
	    foreach($results as $result){
		    $churches[] = $result;
		}

	    $sql = "SELECT * FROM perch3_members WHERE memberID='".$memberID."'";
	    $result = $this->db->get_row($sql);
	    
	    $sql2 = "SELECT * FROM perch3_hellochurch_admins WHERE adminEmail='".$result['memberEmail']."'";
	    $results = $this->db->get_rows($sql2);
	    
	    foreach($results as $result2){
			$sql3 = "SELECT * FROM perch3_hellochurch_churches WHERE churchID='".$result2['churchID']."'";
			$result3 = $this->db->get_row($sql3);    
			$churches[] = $result3;
	    }
	    
	    return $churches;
	    
    }
    
    public function confirm($key, $churchID, $memberID){
	    
		$API  = new PerchAPI(1.0, 'hello_church');	    
		
		$sql = "SELECT * FROM perch3_members WHERE memberID='".$memberID."'";
	    $result = $this->db->get_row($sql);
	    
	    $sql2 = "SELECT * FROM perch3_hellochurch_admins WHERE adminEmail='".$result['memberEmail']."' AND churchID='".$churchID."' AND adminCode='".$key."'";
	    $result2 = $this->db->get_row($sql2);
	    if($result2){
		    return true;
	    }else{
		    return false;
	    }
	    
    }
    
    public function admin_type($churchID, $memberID){
	    
	    $sql = "SELECT * FROM perch3_hellochurch_churches WHERE memberID='".$memberID."' AND churchID='".$churchID."'";
	    $result = $this->db->get_row($sql);
	    
	    if($result){
		    return 'owner';
		    exit;
	    }
	    
	    $sql = "SELECT * FROM perch3_members WHERE memberID='".$memberID."'";
	    $result = $this->db->get_row($sql);
	    
	    $sql = "SELECT * FROM perch3_hellochurch_admins WHERE adminEmail='".$result['memberEmail']."' AND churchID='".$churchID."'";
	    $result = $this->db->get_row($sql);
	    
	    if($result){
		    return 'admin';
		    exit;
	    }
	    
    }

}
