<?php

class HelloChurch_Groups extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_groups';
	protected $pk        = 'groupID';
	protected $singular_classname = 'HelloChurch_Group';

	protected $default_sort_column = 'groupName';

	public $static_fields = array('groupID', 'churchID', 'memberID', 'groupName', 'groupDescription', 'groupAutoAdd', 'groupProperites');

    public $dynamic_fields_column = 'groupProperties';
    
    public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i><li><ul><ol>');
		}
		
		return $clean;
	    
    }
    
	public function group($groupID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_groups WHERE groupID='".$groupID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}
    
    public function groups($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_groups WHERE churchID='".$churchID."' ORDER BY groupName ASC";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function recent_groups($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_groups WHERE churchID='".$churchID."' ORDER BY groupID DESC LIMIT 3";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function check_owner($churchID, $groupID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_groups WHERE churchID='".$churchID."' AND groupID='".$groupID."'";
	    $results = $this->db->get_rows($sql);
	    if($results){
		    return true;
	    }else{
		    return false;
	    }
	    
    }
    
    public function remove_all_members($churchID, $groupID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "DELETE FROM perch3_hellochurch_groups_members WHERE churchID='".$churchID."' AND groupID='".$groupID."'";
	    $results = $this->db->execute($sql);
	    
    }
    
    public function add_group_member($memberID, $churchID, $groupID, $contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT * FROM perch3_hellochurch_groups_members WHERE groupID='".$groupID."' AND contactID='".$contactID."'";
	    $results = $this->db->get_rows($sql);
	    
	    if(count($results)==0){
		    $sql = "INSERT INTO perch3_hellochurch_groups_members (memberID, churchID, groupID, contactID, method) VALUES ('".$memberID."', '".$churchID."', '".$groupID."', '".$contactID."', 'manual')";
			$results = $this->db->execute($sql);
	    }
	    
    }
    
    public function remove_group_member($groupID, $contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "DELETE FROM perch3_hellochurch_groups_members WHERE contactID='".$contactID."' AND groupID='".$groupID."'";
	    $results = $this->db->execute($sql);
	    
    }
    
    public function group_members($groupID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT perch3_hellochurch_contacts.*, perch3_hellochurch_groups_members.* FROM perch3_hellochurch_contacts, perch3_hellochurch_groups_members WHERE perch3_hellochurch_groups_members.contactID=perch3_hellochurch_contacts.contactID AND perch3_hellochurch_groups_members.groupID='".$groupID."'";
	    $results = $this->db->get_rows($sql);
	    return $results;
		
	}
	
	public function by_contactID($contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT perch3_hellochurch_groups.*, perch3_hellochurch_groups_members.* FROM perch3_hellochurch_groups, perch3_hellochurch_groups_members WHERE perch3_hellochurch_groups_members.contactID='".$contactID."' AND perch3_hellochurch_groups.groupID=perch3_hellochurch_groups_members.groupID";
	    $results = $this->db->get_rows($sql);
	    return $results;

	}

}
