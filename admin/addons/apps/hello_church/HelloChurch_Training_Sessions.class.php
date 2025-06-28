<?php

class HelloChurch_Training_Sessions extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_training_sessions';
	protected $pk        = 'sessionID';
	protected $singular_classname = 'HelloChurch_Training_Session';

	protected $default_sort_column = 'sessionID';

	public $static_fields = array('sessionID', 'churchID', 'memberID', 'topicID', 'sessionName');
    
    public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i><li><ul><ol>');
		}
		
		return $clean;
	    
    }

	public function session($sessionID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT * FROM perch3_hellochurch_training_sessions WHERE sessionID='".$sessionID."'";    
		
	    $results = $this->db->get_row($sql);

	    return $results;
	    
    }
	
	public function public_session($churchID, $sessionID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_churches WHERE churchSlug='".$churchID."'";
		$result = $this->db->get_row($sql);
		
		$sql = "SELECT * FROM perch3_hellochurch_training_sessions WHERE churchID='".$result['churchID']."' AND uniqueID='".$sessionID."'";    
		$result = $this->db->get_row($sql);
	
		return $result;
		
	}
	
	public function sessions($topicID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT * FROM perch3_hellochurch_training_sessions WHERE topicID='".$topicID."' ORDER BY sessionOrder ASC";    
		
	    $results = $this->db->get_rows($sql);

	    return $results;
	    
    }
    
    public function check_owner($churchID, $sessionID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_training_sessions WHERE churchID='".$churchID."' AND sessionID='".$sessionID."'";
	    $results = $this->db->get_rows($sql);
	    if($results){
		    return true;
	    }else{
		    return false;
	    }
	    
    }
	
	public function save_session($memberID, $churchID, $sessionID, $session){
		
		$sql = "UPDATE perch3_hellochurch_training_sessions SET sessionContent='".addslashes($session)."' WHERE sessionID='".$sessionID."'";
		$result = $this->db->execute($sql);
		
	}
	
	public function save_session_order($sessionID, $sessionOrder){
		
		$sql = "UPDATE perch3_hellochurch_training_sessions SET sessionOrder='".$sessionOrder."' WHERE sessionID='".$sessionID."'";
		$result = $this->db->execute($sql);
		
	}
	
	public function get_session($memberID, $churchID, $sessionID){
		
		$sql = "SELECT * FROM perch3_hellochurch_training_sessions WHERE sessionID='".$sessionID."'";
		$result = $this->db->get_row($sql);
		
		return $result['sessionContent'];
		
	}
	
	public function training_sessions_for_email($churchID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_training_sessions WHERE churchID='".$churchID."' ORDER BY topicID ASC";    
		
		$sessions = array();
		
		$results = $this->db->get_rows($sql);
		
		foreach($results as $session){
			
			$sql2 = "SELECT * FROM perch3_hellochurch_training_topics WHERE topicID='".$session['topicID']."'";
			$results2 = $this->db->get_row($sql2);
			
			$thisSession = array('value' => $session['sessionID'], 'text' => $results2['topicName'].": ".$session['sessionName']);
			$sessions[] = $thisSession;
			
		}
		
		$sessions = json_encode($sessions);
		return $sessions;
		
	}

}
