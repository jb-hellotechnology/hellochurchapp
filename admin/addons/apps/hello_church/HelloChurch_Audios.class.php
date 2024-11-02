<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Audios extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_audio';
	protected $pk        = 'audioID';
	protected $singular_classname = 'HelloChurch_Audio';

	protected $default_sort_column = 'audioID';

	public $static_fields = array('audioID', 'churchID', 'memberID', 'audioName', 'audioDate', 'audioDescription', 'audioSeries', 'audioSpeaker', 'audioBible', 'audioFile');
    
    public function role_valid($data){
	    
	    return true;
	    
    }
    
    public function add_audio($memberID, $churchID, $audioName, $audioDate, $audioDescription, $audioSpeaker, $audioSeries, $audioBible, $audioFile){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "INSERT INTO perch3_hellochurch_audio (memberID, churchID, audioName, audioDate, audioDescription, audioSpeaker, audioSeries, audioBible, audioFile) VALUES 
		('$memberID', '$churchID', '$audioName', '$audioDate', '$audioDescription', '$audioSpeaker', '$audioSeries', '$audioBible', '$audioFile')";
	    $results = $this->db->execute($sql);
	    
    }
    
    public function delete_audio($audioID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "DELETE FROM perch3_hellochurch_audio WHERE audioID=".$audioID;
	    $results = $this->db->execute($sql);
	    
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
    
    public function check_owner($memberID, $audioID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_audio WHERE memberID='".$memberID."' AND audioID='".$audioID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
    }
    
    public function audio_byName($audio, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_audio WHERE churchID='".$churchID."' AND audioName='".$audio."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }

}
