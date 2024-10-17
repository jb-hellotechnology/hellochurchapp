<?php

class HelloChurch_Contact extends PerchAPI_Base
{
    protected $table  = 'hellochurch_contacts';
    protected $pk     = 'contactID';

    public $static_fields = array('contactID', 'churchID', 'memberID', 'contactFirstName', 'contactLastName', 'contactEmail', 'contactPhone', 'contactAddress1', 'contactAddress2', 'contactCity', 'contactCounty', 'contactPostCode', 'contactCountry', 'contactAcceptEmail', 'contactAcceptSMS', 'contactTags', 'contactProperites');
    
    public function update_tags($contactID, $data){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "DELETE FROM perch3_hellochurch_contacts_tags WHERE contactID='".$contactID."'";
	    $results = $this->db->execute($sql);
	    $tags = json_decode($data['contactTags'], true);
	    
	    if($tags){
		    foreach($tags as $tag){
			    if($tag<>''){
				    $sql = "INSERT INTO perch3_hellochurch_contacts_tags (memberID, churchID, contactID, tag) VALUES 
				    ('".$data['memberID']."', '".$data['churchID']."', '".$contactID."', '".strtolower($tag['value'])."')";
					$results = $this->db->execute($sql);
				}
		    }
	    }else{
		    $tags = explode(",", $data['contactTags']);
		    foreach($tags as $tag){
			    if($tag<>''){
				    $sql = "INSERT INTO perch3_hellochurch_contacts_tags (memberID, churchID, contactID, tag) VALUES 
				    ('".$data['memberID']."', '".$data['churchID']."', '".$contactID."', '".strtolower($tag)."')";
					$results = $this->db->execute($sql);
				}
		    }
	    }
	     
    }
    
    public function delete_tags($contactID, $data){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "DELETE FROM perch3_hellochurch_contacts_tags WHERE contactID='".$contactID."'";
	    $results = $this->db->execute($sql);
	    
    }

}
