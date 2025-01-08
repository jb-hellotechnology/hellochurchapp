<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Emails extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_emails';
	protected $pk        = 'emailID';
	protected $singular_classname = 'HelloChurch_Email';

	protected $default_sort_column = 'emailID';

	public $static_fields = array('emailID', 'churchID', 'memberID', 'emailSubject', 'emailTo', 'emailContent', 'emailStatus', 'emailSent');
    
    public function role_valid($data){
	    
	    return true;
	    
    }

	public function email($emailID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT * FROM perch3_hellochurch_emails WHERE emailID='".$emailID."' ORDER BY emailID DESC";    
		
	    $results = $this->db->get_row($sql);

	    return $results;
	    
    }
	
	public function emails($churchID, $status){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT * FROM perch3_hellochurch_emails WHERE churchID='".$churchID."' AND emailStatus='".$status."' ORDER BY emailID DESC";    
		
	    $results = $this->db->get_rows($sql);

	    return $results;
	    
    }
    
    public function check_owner($churchID, $emailID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_emails WHERE churchID='".$churchID."' AND emailID='".$emailID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
    }
    
    public function save_email($memberID, $churchID, $emailID, $email){
		
		$sql = "UPDATE perch3_hellochurch_emails SET emailContent='".addslashes($email)."' WHERE emailID='".$emailID."'";
		$result = $this->db->execute($sql);
		
	}
	
	public function get_email($memberID, $churchID, $emailID){
		
		$sql = "SELECT * FROM perch3_hellochurch_emails WHERE emailID='".$emailID."'";
	    $result = $this->db->get_row($sql);
	    
	    return $result['emailContent'];
		
	}
	
	public function save_email_result($emailID, $result){
		
		$timestamp = date('Y-m-d H:i:s');
		
		$sql = "UPDATE perch3_hellochurch_emails SET emailResult='".$result."', emailSent='".$timestamp."', emailStatus='Sent' WHERE emailID='".$emailID."'";
		$result = $this->db->execute($sql);
		
	}
	
	public function log_email_contact($emailID,$contactID){
		
		$timestamp = date('Y-m-d H:i:s');
		
		$sql = "INSERT INTO perch3_hellochurch_email_contact (emailID, contactID, timestamp) VALUES ('".$emailID."', '".$contactID."', '".$timestamp."')";
		echo $sql;
		$result = $this->db->execute($sql);
		
	}
	
	public function recipients($emailID){
		
		$sql = "SELECT * FROM perch3_hellochurch_email_contact WHERE emailID='".$emailID."'";
	    $result = $this->db->get_rows($sql);
	    return $result;
		
	}
	
	public function contact_emails($contactID){
		
		$sql = "SELECT * FROM perch3_hellochurch_email_contact WHERE contactID='".$contactID."'";
	    $result = $this->db->get_rows($sql);
	    return $result;
	    
	}

}
