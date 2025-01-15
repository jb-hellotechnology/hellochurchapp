<?php

class HelloChurch_Audios extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_audio';
	protected $pk        = 'audioID';
	protected $singular_classname = 'HelloChurch_Audio';

	protected $default_sort_column = 'audioID';

	public $static_fields = array('audioID', 'churchID', 'memberID', 'audioName', 'audioDate', 'audioDescription', 'audioSeries', 'audioSpeaker', 'audioBible', 'audioFileLocation', 'audioFileName', 'audioPublish');
    
    public function add_audio($memberID, $churchID, $audioName, $audioDate, $audioDescription, $audioSpeaker, $audioSeries, $audioBible, $audioFile){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $audioFileName = explode("/", $audioFile);
	    $audioFileLocation = $audioFileName[0];
		$audioFileName = $audioFileName[1];
		
		$sql = "INSERT INTO perch3_hellochurch_audio (memberID, churchID, audioName, audioDate, audioDescription, audioSpeaker, audioSeries, audioBible, audioFileLocation, audioFileName) VALUES 
		('$memberID', '$churchID', '$audioName', '$audioDate', '$audioDescription', '$audioSpeaker', '$audioSeries', '$audioBible', '$audioFileLocation', '$audioFileName')";
	    $this->db->execute($sql);
	    
    }
    
    public function delete_audio($audioID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "DELETE FROM perch3_hellochurch_audio WHERE audioID=".$audioID;
	    $this->db->execute($sql);
	    
    }
    
	public function audio($audioID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_audio WHERE audioID='".$audioID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}
    
    public function audios($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_audio WHERE churchID='".$churchID."' ORDER BY audioID DESC";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function audiosPodcast($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_audio WHERE churchID='".$churchID."' AND audioPublish='Yes' ORDER BY audioID DESC";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function check_audio_owner($churchID, $audioID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_audio WHERE churchID='".$churchID."' AND audioID='".$audioID."'";
	    $results = $this->db->get_rows($sql);
	    if($results){
		    return true;
	    }else{
		    return false;
	    }
	    
    }
    
    public function audio_byName($audio, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_audio WHERE churchID='".$churchID."' AND audioName='".$audio."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }

}
