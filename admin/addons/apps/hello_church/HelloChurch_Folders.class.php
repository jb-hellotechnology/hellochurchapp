<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Folders extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_folders';
	protected $pk        = 'folderID';
	protected $singular_classname = 'HelloChurch_Folder';

	protected $default_sort_column = 'folderID';

	public $static_fields = array('folderID', 'churchID', 'memberID', 'folderName', 'folderParent');
    
    public function role_valid($data){
	    
	    return true;
	    
    }
    
	public function folder($folderID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_folders WHERE folderID='".$folderID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}
	
	public function update_file($data){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "UPDATE perch3_hellochurch_files SET fileName='".$data['fileName']."', folderID='".$data['folderID']."' WHERE fileID='".$data['fileID']."'";
		$this->db->execute($sql);
		
	}
    
    public function folders($churchID, $folderParent){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT * FROM perch3_hellochurch_folders WHERE churchID='".$churchID."' AND folderParent='".$folderParent."' ORDER BY folderName ASC";    
		
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function folders_exclude($churchID, $folderParent){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

		$sql = "SELECT * FROM perch3_hellochurch_folders WHERE churchID='".$churchID."' AND folderID<>'".$folderParent."' AND folderParent<>'".$folderParent."' ORDER BY folderName ASC";    		
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function check_owner($memberID, $folderID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_folders WHERE memberID='".$memberID."' AND folderID='".$folderID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
    }
    
    public function check_file_owner($churchID, $fileID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_files WHERE churchID='".$churchID."' AND fileID='".$fileID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
    }
    
    public function folder_byName($folderName, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_folders WHERE churchID='".$churchID."' AND folderName='".$folderName."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }
    
    public function add_file($churchID, $memberID, $folderID, $contactID, $groupID, $eventID, $eventDate, $fileLocation){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    if($folderID==''){
		    $folderID = 0;
	    }
	    
	    if($contactID==''){
		    $contactID = 0;
	    }
	    
	    if($groupID==''){
		    $groupID = 0;
	    }
	    
	    if($eventID==''){
		    $eventID = 0;
	    }
	    
	    if($eventDate=='' OR $eventDate=='0000-00-00'){
		    $eventDate = date('Y-m-d');
	    }
	    
	    $fileName = explode("/", $fileLocation);
		$fileName = pathinfo($fileName[1], PATHINFO_FILENAME);
		
		$sql = "INSERT INTO perch3_hellochurch_files (churchID, memberID, folderID, contactID, groupID, eventID, eventDate, fileLocation, fileName) VALUES 
		('".$churchID."', '".$memberID."', '".$folderID."', '".$contactID."', '".$groupID."', '".$eventID."', '".$eventDate."', '".$fileLocation."', '".$fileName."')";
	    $results = $this->db->execute($sql);
	    
    }
    
    public function delete_file($fileID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "DELETE FROM perch3_hellochurch_files WHERE fileID=".$fileID;
	    $results = $this->db->execute($sql);
	    
    }
    
    public function get_file($fileID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_files WHERE fileID='".$fileID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }
    
    public function files($churchID, $folderParent, $contactParent, $groupParent, $eventParent, $eventDate){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    if($contactParent>0){
	    	$sql = "SELECT * FROM perch3_hellochurch_files WHERE churchID='".$churchID."' AND contactID='".$contactParent."' ORDER BY fileCreated DESC";    
	    }elseif($groupParent>0){
		    $sql = "SELECT * FROM perch3_hellochurch_files WHERE churchID='".$churchID."' AND groupID='".$groupParent."' ORDER BY fileCreated DESC";   
	    }elseif($eventParent>0){
		    $sql = "SELECT * FROM perch3_hellochurch_files WHERE churchID='".$churchID."' AND eventID='".$eventParent."' AND eventDate='".$eventDate."'  ORDER BY fileCreated DESC";   
	    }else{
		    $sql = "SELECT * FROM perch3_hellochurch_files WHERE churchID='".$churchID."' AND folderID='".$folderParent."' ORDER BY fileCreated DESC";   
	    }
		
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }

}
