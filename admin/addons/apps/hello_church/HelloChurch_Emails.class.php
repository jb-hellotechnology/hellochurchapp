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

	protected $default_sort_column = 'audioID';

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
	
	public function emails($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT * FROM perch3_hellochurch_emails WHERE churchID='".$churchID."' ORDER BY emailID DESC";    
		
	    $results = $this->db->get_rows($sql);

	    return $results;
	    
    }
    
    public function check_owner($memberID, $emailID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_emails WHERE memberID='".$memberID."' AND emailID='".$emailID."'";
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

}
