<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Contacts extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_contacts';
	protected $pk        = 'contactID';
	protected $singular_classname = 'HelloChurch_Contact';

	protected $default_sort_column = 'contactFirstName';

	public $static_fields = array('contactID', 'churchID', 'memberID', 'contactFirstName', 'contactLastName', 'contactEmail', 'contactPhone', 'contactAddress1', 'contactAddress2', 'contactCity', 'contactCounty', 'contactPostCode', 'contactCountry', 'contactAcceptEmail', 'contactAcceptSMS', 'contactTags', 'contactProperites');

    public $dynamic_fields_column = 'contactProperties';
    
    public function contact_valid($data){
	    
	    return true;
	    
    }
    
	public function contact($contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts WHERE contactID='".$contactID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}
    
    public function contacts($memberID, $churchID, $tag, $q, $page){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    if($tag<>''){
		    $tag_sql = 'AND contactTags LIKE "%\"'.urldecode($tag).'\"%"';
	    }
	    
	    if($q<>''){
		    $q_sql = 'AND (contactFirstName LIKE "%'.$q.'%" OR contactLastName LIKE "%'.$q.'%" OR contactFirstName LIKE "%'.$q.'%")';
	    }
	    
	    if($page==''){
			$page = 1;   
	    }
	    
	    $start = ($page-1)*25;
	    $end = 25;
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts WHERE memberID='".$memberID."' AND churchID='".$churchID."' $tag_sql $q_sql ORDER BY contactLastName ASC LIMIT $start,$end";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function recent_contacts($memberID, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts WHERE memberID='".$memberID."' AND churchID='".$churchID."' ORDER BY contactID DESC LIMIT 5";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function totalContacts($memberID, $churchID, $tag, $q){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    if($tag<>''){
		    $tag_sql = 'AND contactTags LIKE "%\"'.urldecode($tag).'\"%"';
	    }
	    
	    if($q<>''){
		    $q_sql = 'AND (contactFirstName LIKE "%'.$q.'%" OR contactLastName LIKE "%'.$q.'%" OR contactFirstName LIKE "%'.$q.'%")';
	    }
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts WHERE memberID='".$memberID."' AND churchID='".$churchID."' $tag_sql $q_sql";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
    }
    
    public function check_owner($memberID, $contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts WHERE memberID='".$memberID."' AND contactID='".$contactID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
    }
    
    public function tag_options($churchID, $pTag){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT DISTINCT tag FROM perch3_hellochurch_contacts_tags WHERE churchID='".$churchID."' ORDER BY tag ASC";
	    $results = $this->db->get_rows($sql);
	    
	    $html = '';
	    
	    foreach($results as $tag){
		    $html .= '<option value="'.urlencode(($tag['tag'])).'"'; 
		    if(urldecode($pTag)==$tag['tag']){$html.=' SELECTED';}
		    $html .='>'.ucwords($tag['tag']).'</option>';
	    }
	    
	    echo $html;
	    
    }
    
    public function bulk_update_tags($contactID, $tag){
		
		$API  = new PerchAPI(1.0, 'hello_church');	
		
		if($tag<>''){
			
			$sql = "SELECT * FROM perch3_hellochurch_contacts WHERE contactID='".$contactID."'";
		    $result = $this->db->get_row($sql);
		    
		    $memberID = $result['memberID'];
		    $churchID = $result['churchID'];
			
			$sql = "INSERT INTO perch3_hellochurch_contacts_tags (memberID, churchID, contactID, tag) VALUES 
				    ('".$memberID."', '".$churchID."', '".$contactID."', '".strtolower($tag)."')";
			$result = $this->db->execute($sql);
			
			$sql = "SELECT * FROM perch3_hellochurch_contacts_tags WHERE contactID='".$contactID."'";
		    $results = $this->db->get_rows($sql);
		    
		    $tagString = "[";
		    
		    foreach($results as $row){
			    $tagString .= '{"value":"'.$row['tag'].'"},';
		    }
		    
		    $tagString = substr($tagString, 0, -1);
		    
		    $tagString .= "]";
			
			$sql = "UPDATE perch3_hellochurch_contacts SET contactTags='".$tagString."' WHERE contactID='".$contactID."'";
			$result = $this->db->execute($sql);
		
		}
		   
	}
    
    public function export($memberID, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts WHERE churchID='".$churchID."' AND memberID='".$memberID."' ORDER BY contactLastName ASC";
	    $results = $this->db->get_rows($sql);
	    
	    // Start the output buffer.
		ob_start();
		
		// Set PHP headers for CSV output.
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=hello_church_contacts.csv');
		
		// Create the headers.
		$header_args = array( 'ID', 'Church', 'Member', 'First Name', 'Last Name', 'Address 1', 'Address 2', 'City', 'County', 'Post Code', 'Country', 'Email', 'Phone', 'Accepts Emai', 'Accepts SMS', 'Tags', 'Additional Data' );
		
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
	
	public function family_members($memberID, $contactID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts_families WHERE contactID='".$contactID."'";
	    $result = $this->db->get_row($sql);

	    if(!$result){
		    $identifier = rand(100000,999999);
		    $sql = "INSERT INTO perch3_hellochurch_contacts_families (memberID, contactID, identifier) VALUES (".$memberID.", ".$contactID.", ".$identifier.")";
			$results = $this->db->execute($sql);
	    }else{
		    $identifier = $result['identifier'];
	    }
	    
	    $sql = "SELECT perch3_hellochurch_contacts.*, perch3_hellochurch_contacts_families.* FROM perch3_hellochurch_contacts, perch3_hellochurch_contacts_families WHERE perch3_hellochurch_contacts_families.identifier='".$identifier."' AND perch3_hellochurch_contacts_families.contactID<>'".$contactID."' AND perch3_hellochurch_contacts_families.contactID=perch3_hellochurch_contacts.contactID";
	    $results = $this->db->get_rows($sql);
	    
	    return $results;
		
	}
	
	public function search_family_members($memberID, $q, $contactID){
		
		$API  = new PerchAPI(1.0, 'hello_church');

		$sql = 'SELECT c.*
		FROM perch3_hellochurch_contacts c
		LEFT JOIN perch3_hellochurch_contacts_families r ON c.contactID = r.contactID
		WHERE (c.contactLastName LIKE "%'.$q.'%" OR c.contactFirstName LIKE "%'.$q.'%")
		  AND r.contactID IS NULL 
		  AND c.contactID != '.$contactID;
	    $result = $this->db->get_rows($sql);
	    
	    return $result;
		
	}
	
	public function add_family_member($memberID, $primary, $contactID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_contacts_families WHERE contactID='".$primary."'";
	    $result = $this->db->get_row($sql);
	    
	    $sql = "INSERT INTO perch3_hellochurch_contacts_families (memberID, contactID, identifier) VALUES (".$memberID.", ".$contactID.", '".$result['identifier']."')";
		$results = $this->db->execute($sql);
		
	}
	
	public function remove_family_member($identifier, $contactID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "DELETE FROM perch3_hellochurch_contacts_families WHERE contactID='".$contactID."' AND identifier='".$identifier."'";
	    $result = $this->db->execute($sql);
		
	}
	
	public function search_members($memberID, $q){
		
		$API  = new PerchAPI(1.0, 'hello_church');

		$sql = 'SELECT * FROM perch3_hellochurch_contacts WHERE (contactLastName LIKE "%'.$q.'%" OR contactFirstName LIKE "%'.$q.'%") AND memberID="'.$memberID.'"';
	    $result = $this->db->get_rows($sql);
	    
	    return $result;
		
	}
}
