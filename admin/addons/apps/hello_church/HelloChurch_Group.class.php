<?php

class HelloChurch_Group extends PerchAPI_Base
{
    protected $table  = 'hellochurch_groups';
    protected $pk     = 'groupID';

    public $static_fields = array('groupID', 'churchID', 'memberID', 'groupName', 'groupDescription', 'groupAutoAdd', 'groupProperites');

	public function update_tags($data){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$groupID = $data['groupID'];
		
		$sql = "DELETE FROM perch3_hellochurch_groups_tags WHERE groupID='".$groupID."'";
	    $results = $this->db->execute($sql);
	    $tags = json_decode($data['groupAutoAdd'], true);
	    
	    if($tags){
		    foreach($tags as $tag){
			    if($tag<>''){
				    $sql = "INSERT INTO perch3_hellochurch_groups_tags (memberID, churchID, groupID, tag) VALUES 
				    ('".$data['memberID']."', '".$data['churchID']."', '".$groupID."', '".strtolower($tag['value'])."')";
					$results = $this->db->execute($sql);
				}
		    }
		}
		
	}
	
	public function update_members($groupID, $data){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		if(!$groupID){
			$groupID = $data['groupID'];
		}
		$memberID = $data['memberID'];
		$churchID = $data['churchID'];
		$tags = json_decode($data['groupAutoAdd'], true);
		
		$sql = "DELETE FROM perch3_hellochurch_groups_members WHERE groupID='".$groupID."' AND churchID='".$churchID."' AND method='auto'";
		$results = $this->db->execute($sql);
		
		foreach($tags as $tag){
			
			// FIND MEMBERS WITH TAG
			$sql = "SELECT * FROM perch3_hellochurch_contacts_tags WHERE tag='".$tag['value']."' AND churchID='".$churchID."'";
			$results = $this->db->get_rows($sql);
			
			// ADD MEMBERS TO GROUP
			foreach($results as $contact){
				
				$sql = "SELECT * FROM perch3_hellochurch_groups_members WHERE groupID='".$groupID."' AND contactID='".$contact['contactID']."'";
				$member = $this->db->get_row($sql);
				
				if(!$member){
				
					$sql = "INSERT INTO perch3_hellochurch_groups_members (memberID, churchID, groupID, contactID, method) VALUES ('".$memberID."', '".$churchID."', '".$groupID."', '".$contact['contactID']."', 'auto')";
					$results = $this->db->execute($sql);
				
				}
			}
			
		}
		
	}
	
	public function is_group_member($groupID, $contactID){
		
		$sql = "SELECT * FROM perch3_hellochurch_groups_members WHERE groupID='".$groupID."' AND contactID='".$contactID."'";
		$results = $this->db->get_rows($sql);
		
		if(count($results)>0){
			return true;
		}else{
			return false;
		}
		
	}

}
