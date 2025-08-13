<?php

class HelloChurch_Contact extends PerchAPI_Base
{
    protected $table  = 'hellochurch_contacts';
    protected $pk     = 'contactID';

    public $static_fields = array('contactID', 'churchID', 'memberID', 'contactFirstName', 'contactPreferredName', 'contactLastName', 'contactEmail', 'contactPhone', 'contactOrganisation', 'contactAddress1', 'contactAddress2', 'contactCity', 'contactCounty', 'contactPostCode', 'contactCountry', 'contactAcceptEmail', 'contactEmailSecondary', 'contactAcceptSMS', 'contactFamilyID', 'contactTags', 'contactProperites', 'contactMagicLink', 'contactMagicLinkExpires', 'contactLat', 'contactLng', 'contactConfirmedCorrect');
    
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
    
    public function update_groups($contactID, $data){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$groupID = $data['groupID'];
		$memberID = $data['memberID'];
		$churchID = $data['churchID'];
		$tags = json_decode($data['contactTags'], true);
		
		$sql = "DELETE FROM perch3_hellochurch_groups_members WHERE contactID='".$contactID."' AND churchID='".$churchID."' AND method='auto'";
		$results = $this->db->execute($sql);
		
		foreach($tags as $tag){

			// FIND MEMBERS WITH TAG
			$sql = "SELECT * FROM perch3_hellochurch_groups_tags WHERE tag='".$tag['value']."' AND churchID='".$churchID."'";
			$results = $this->db->get_rows($sql);
			
			// ADD MEMBERS TO GROUP
			foreach($results as $group){
				$sql = "INSERT INTO perch3_hellochurch_groups_members (memberID, churchID, groupID, contactID, method) VALUES ('".$memberID."', '".$churchID."', '".$group['groupID']."', '".$contactID."', 'auto')";
				$results = $this->db->execute($sql);
			}
			
		}
		   
	}
    
    public function delete_tags($contactID, $data){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "DELETE FROM perch3_hellochurch_contacts_tags WHERE contactID='".$contactID."'";
	    $results = $this->db->execute($sql);
	    
    }
	
	public function delete_roles($contactID, $data){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "DELETE FROM perch3_hellochurch_roles_contacts WHERE contactID='".$contactID."'";
		$results = $this->db->execute($sql);
		
	}
    
    public function export($contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts WHERE contactID='".$contactID."'";
	    $results = $this->db->get_rows($sql);
	    // Start the output buffer.
		ob_start();
		
		// Set PHP headers for CSV output.
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=church_planner_contact.csv');
		
		// Create the headers.
		$header_args = array( 'ID', 'Church', 'Member', 'First Name', 'Preferred Name', 'Last Name', 'Organisation', 'Address 1', 'Address 2', 'City', 'County', 'Post Code', 'Country', 'Email', 'Secondary Email', 'Phone', 'Accepts Emai', 'Accepts SMS', 'Tags', 'Additional Data' );
		
		// Clean up output buffer before writing anything to CSV file.
		ob_end_clean();
		
		// Create a file pointer with PHP.
		$output = fopen( 'php://output', 'w' );
		
		// Write headers to CSV file.
		fputcsv( $output, $header_args );
		
		// Loop through the prepared data to output it to CSV file.
		foreach( $results as $data_item ){
		    fputcsv( $output, $data_item );
		}
		
		// Close the file pointer with PHP with the updated output.
		fclose( $output );
		exit;
	    
    }

}
