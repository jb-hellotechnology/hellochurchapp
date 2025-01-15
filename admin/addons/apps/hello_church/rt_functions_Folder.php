<?php
	
	/** FOLDER STRUCTURE FOR FILE FORM SELECT FIELD **/
	function hello_church_folder_structure(){
	
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
	
		$folderString = " |0,";
		
		$folders = $HelloChurchFolders->folders($Session->get('churchID'), $folderParent);
		
		foreach($folders as $folder){
			$folderString .= "- $folder[folderName]|$folder[folderID],";
			
			$subFolders = $HelloChurchFolders->folders($Session->get('churchID'), $folder['folderID']);
			foreach($subFolders as $subFolder){
				$folderString .= "-- $subFolder[folderName]|$subFolder[folderID],";
				$subFolders_2 = $HelloChurchFolders->folders($Session->get('churchID'), $subFolder['folderID']);	
				foreach($subFolders_2 as $subFolder_2){	
					$folderString .= "-- $subFolder_2[folderName]|$subFolder_2[folderID],";
				}
			}
		}
		
		return substr($folderString,0,-1);
		
	}
	
	/** LIST FOLDERS **/
	function hello_church_folders($folderParent){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$folders = $HelloChurchFolders->folders($Session->get('churchID'), $folderParent);
		
		$html = '<ul class="folders">';
		
		foreach($folders as $folder){
			$html .= '<li><a href="/documents?id='.$folder['folderID'].'"><span class="material-symbols-outlined">folder</span><h3>'.$folder['folderName'].'</h3></a></li>';
		}
		
		$html .= '</ul>';
		
		echo $html;
		
	}
	
	/** GET FOLDER DATA **/
	function hello_church_folder($folderID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$folder = $HelloChurchFolders->folder($folderID);
		
		return $folder;
		
	}
	
	/** CHECK IF SIGNED IN USER IS OWNER OF FOLDER **/
	function hello_church_folder_owner($folderID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchFolders->check_owner($Session->get('churchID'), $folderID);
		
		return $owner;
		
	}
	
	/** CHECK IF FOLDER HAS CHILDREN **/
	function folder_has_children($folderID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$folders = $HelloChurchFolders->folders($Session->get('churchID'), $folderID);	
		
		if(count($folders)>0){
			return true;
		}else{
			return false;
		}
		
	}
	
	/** UPLOAD FILE **/
	function process_file_upload($folderID, $contactID, $groupID, $eventID, $eventDate, $fileName){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchFolders = new HelloChurch_Folders($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$HelloChurchFolders->add_file($Session->get('churchID'), $Session->get('memberID'), $folderID, $contactID, $groupID, $eventID, $eventDate, $fileName);
	    
    }
    
    /** DELETE FILE **/
    function process_delete_file($fileID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchFolders = new HelloChurch_Folders($API);
        
        $file = $HelloChurchFolders->get_file($fileID);
        $fileName = explode("/", $file['fileLocation']);
        
        unlink("../../../../hc_uploads/".$file['churchID']."/".$file['fileLocation']);
        rmdir("../../../../hc_uploads/".$file['churchID']."/".$fileName[0]);
		
		$HelloChurchFolders->delete_file($fileID);
	    
    }
    
    /** DOWNLOAD FILE **/
    function process_download_file($fileID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchFolders = new HelloChurch_Folders($API);
        
        $file = $HelloChurchFolders->get_file($fileID);
        
        $fileLocation = "../../../../hc_uploads/".$file['churchID']."/".$file['fileLocation']; 
		
		if (file_exists($fileLocation))
		{
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.basename($fileLocation));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($fileLocation));
		    ob_clean();
		    flush();
		    readfile($fileLocation);
		    exit;
		}
		else
		{
		    echo "File does not exist";
		}
	    
    }
    
    /** LIST FILES **/
    function hello_church_files($folderParent, $contactParent, $groupParent, $eventParent, $eventDate){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);

		$HelloChurchContacts = new HelloChurch_Contacts($API);
		$HelloChurchGroups = new HelloChurch_Groups($API);
		$HelloChurchEvents = new HelloChurch_Events($API);
		
		$Session = PerchMembers_Session::fetch();
		
		if($contactParent>0){
			$files = $HelloChurchFolders->files($Session->get('churchID'), 0, $contactParent, 0, 0, 0);
		}elseif($groupParent>0){
			$files = $HelloChurchFolders->files($Session->get('churchID'), 0, 0, $groupParent, 0, 0);
		}elseif($eventParent>0){
			$files = $HelloChurchFolders->files($Session->get('churchID'), 0, 0, 0, $eventParent, $eventDate);
		}else{
			$files = $HelloChurchFolders->files($Session->get('churchID'), $folderParent, 0, 0, $eventParent, $eventDate);
		}
		
		$html = '<p class="section-heading">Files:</p>';
		
		if($files){
			$html .= '<ul class="list">';
			
			foreach($files as $file){
				$documentFileType = strtolower(pathinfo($file['fileName'],PATHINFO_EXTENSION));
				if($documentFileType=='doc' OR $documentFileType=='docx' OR $documentFileType=='pages'){
					$icon = 'description';
				}elseif($documentFileType=='xls' OR $documentFileType=='xlsx' OR $documentFileType=='numbers'){
					$icon = 'table';
				}elseif($documentFileType=='ppt' OR $documentFileType=='pptx' OR $documentFileType=='key'){
					$icon = 'slideshow';
				}elseif($documentFileType=='csv'){
					$icon = 'csv';
				}elseif($documentFileType=='txt'){
					$icon = 'article';
				}elseif($documentFileType=='pdf'){
					$icon = 'picture_as_pdf';
				}else{
					$icon = 'draft';
				}
				
				$html .= '<li>
							<div class="heading">
								<span class="material-symbols-outlined">'.$icon.'</span>
								<p><a href="/documents/edit-file?id='.$file['fileID'].'">'.$file['fileName'].'</a>';
								if($file['contactID']){
									$contact = $HelloChurchContacts->contact($file['contactID']);
									if($contact){
										$html .= '<br /><small><a href="/contacts/edit-contact?id='.$file['contactID'].'">'.$contact['contactFirstName'].' '.$contact['contactLastName'].'</a></small>';
									}
								}elseif($file['groupID']){
									$group = $HelloChurchGroups->group($file['groupID']);
									if($group){
										$html .= '<br /><small><a href="/groups/edit-group?id='.$file['groupID'].'">'.$group['groupName'].'</a></small>';
									}
								}elseif($file['eventID']){
									$event = $HelloChurchEvents->event($file['eventID']);
									if($event){
										$dateParts = explode("-", $file['eventDate']);
										$date = "$dateParts[2]/$dateParts[1]/$dateParts[0]";
										$html .= '<br /><small><a href="/calendar/edit-event?id='.$event['editID'].'&date='.$event['eventDate'].'">'.$event['eventName'].' &bull; '.$date.'</a></small>';
									}
								}
							$html .= '</p>
							</div>
							<div class="functions">
								<form method="post" action="/process/download-file">
									<input type="submit" class="button secondary small" value="Download" />
									<input type="hidden" name="id" value="'.$folderParent.'" />
									<input type="hidden" name="fileID" value="'.$file['fileID'].'" />
								</form>
								<form method="post" action="/process/delete-file">
									<button type="submit" class="button danger small border"><span class="material-symbols-outlined">delete</span></button>
									<input type="hidden" name="id" value="'.$folderParent.'" />
									<input type="hidden" name="fileID" value="'.$file['fileID'].'" />
								</form>
							</div>
						</li>';
			}
			
			$html .= '</ul>';
		}else{
			$html .= "<p>No files uploaded.</p>";
		}
		
		echo $html;
		
	}
	
	/** CHECK SIGNED IN USER IS OWNER OF FILE **/
	function hello_church_file_owner($fileID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchFolders->check_file_owner($Session->get('churchID'), $fileID);
		
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	/** GET FILE DATA **/
	function hello_church_file($fileID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$file = $HelloChurchFolders->get_file($fileID);
		
		return $file;
		
	}
	
?>