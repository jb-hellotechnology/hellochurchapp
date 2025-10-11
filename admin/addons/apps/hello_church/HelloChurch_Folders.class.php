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
    
    public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i><li><ul><ol>');
		}
		
		return $clean;
	    
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
    
    public function check_owner($churchID, $folderID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_folders WHERE churchID='".$churchID."' AND folderID='".$folderID."'";
	    $results = $this->db->get_rows($sql);

	    if($results){
		    return true;
	    }else{
		    return false;
	    }
	    
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
		
		$length = 128;
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$fileString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$fileString .= $characters[random_int(0, $charactersLength - 1)];
		}
		
		$sql = "INSERT INTO perch3_hellochurch_files (churchID, memberID, folderID, contactID, groupID, eventID, eventDate, fileLocation, fileName, fileString) VALUES 
		('".$churchID."', '".$memberID."', '".$folderID."', '".$contactID."', '".$groupID."', '".$eventID."', '".$eventDate."', '".$fileLocation."', '".$fileName."', '".$fileString."')";
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
	
	public function get_file_by_string($fileString){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_files WHERE fileString='".$fileString."'";
		$result = $this->db->get_row($sql);
		return $result;
		
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
    
    public function files_for_email($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT * FROM perch3_hellochurch_files WHERE churchID='".$churchID."' ORDER BY fileCreated DESC LIMIT 100";   
	    
	    $files = array();
		
	    $results = $this->db->get_rows($sql);
	    
	    foreach($results as $file){
		    
		    $thisFile = array('value' => $file['fileID'], 'text' => $file['fileName']);
		    $files[] = $thisFile;
		    
	    }
	    
	    $files = json_encode($files);
	    return $files;
	    
    }
    
    public function images_for_email($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT * 
		FROM perch3_hellochurch_files 
		WHERE churchID = '".$churchID."'
		  AND (
			LOWER(RIGHT(fileLocation, 3)) = 'jpg' OR
			LOWER(RIGHT(fileLocation, 4)) = 'jpeg' OR
			LOWER(RIGHT(fileLocation, 3)) = 'png'
		  )
		ORDER BY folderID, fileCreated DESC 
		LIMIT 100;";   
	    
	    $files = array();
		
	    $results = $this->db->get_rows($sql);
	    
	    foreach($results as $file){
			
			// get folders
			$sql2 = "SELECT * FROM perch3_hellochurch_folders WHERE churchID='".$churchID."'";
			$result2 = $this->db->get_rows($sql2);
			$folders = [];
			foreach($result2 as $row){
				$folders[$row['folderID']] = $row;
			}
			
			$path = $this->getFolderPath($file['folderID'], $folders);
			
			if($file['folderID']){
				$thisFile = array('value' => $file['fileID'], 'text' => $path." > ".$file['fileName']);
			}else{
				$thisFile = array('value' => $file['fileID'], 'text' => $file['fileName']);
			}
			
		    $files[] = $thisFile;
		    
	    }
	    
	    $files = json_encode($files);
	    return $files;
	    
    }
	
	function getFolderPath($folderID, $folders) {
		if (!isset($folders[$folderID])) {
			return '';
		}
	
		$folder = $folders[$folderID];
		// If this folder has a parent, recursively get its path
		if ($folder['folderParent']) {
			return $this->getFolderPath($folder['folderParent'], $folders) . ' > ' . $folder['folderName'];
		} else {
			// Root folder
			return $folder['folderName'];
		}
	}
	
	public function get_public_url($fileID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_files WHERE fileID='".$fileID."'";
		$result = $this->db->get_row($sql);
		return "https://app.churchplanner.co.uk/file/".$result['fileString'];
		
	}

}
