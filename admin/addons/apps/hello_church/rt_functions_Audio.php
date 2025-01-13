<?php
	
	/** CHECK IF SIGNED IN USER IS OWNER OF FILE **/
	function hello_church_audio_owner($audioID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchAudios = new HelloChurch_Audios($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchAudios->check_audio_owner($Session->get('churchID'), $audioID);
		
		return $owner;
		
	}
	
	/** UPLOAD AUDIO FILE **/
	function process_audio_upload($audioName, $audioDate, $audioDescription, $audioSpeaker, $audioSeries, $audioBible, $audioFile){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        $HelloChurchAudios = new HelloChurch_Audios($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$HelloChurchAudios->add_audio($Session->get('memberID'), $Session->get('churchID'), $audioName, $audioDate, $audioDescription, $audioSpeaker, $audioSeries, $audioBible, $audioFile);
	    
    }
    
    /** DISPLAY LIST OF AUDIO FILES **/
    function hello_church_audio(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchAudios = new HelloChurch_Audios($API);
		$HelloChurchSeriess = new HelloChurch_Seriess($API);
		$HelloChurchSpeakers = new HelloChurch_Speakers($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$files = $HelloChurchAudios->audios($Session->get('churchID'));
		
		if($files){
			$html .= '<ul class="list audio">';
			
			foreach($files as $file){
				$series = $HelloChurchSeriess->series($file['audioSeries']);
				$speaker = $HelloChurchSpeakers->speaker($file['audioSpeaker']);
				$html .= '<li>
							<div class="heading">
								<span class="material-symbols-outlined">record_voice_over</span>
								<p><a href="/media/edit-audio?id='.$file['audioID'].'">'.$file['audioName'].'</a></p>
							</div>
							<div class="functions">
								<form method="post" action="/process/download-audio">
									<input type="submit" class="button secondary small" value="Download" />
									<input type="hidden" name="audioID" value="'.$file['audioID'].'" />
								</form>
								<form method="post" action="/process/delete-audio">
									<button type="submit" class="button danger small border"><span class="material-symbols-outlined">delete</span></button>
									<input type="hidden" name="fileID" value="'.$file['audioID'].'" />
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
	
	/** EXPORT AUDIO FILE PUBLICLY FOR RSS FEED **/
	function hello_church_audio_file($audioID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchAudios = new HelloChurch_Audios($API);
		
		$file = $HelloChurchAudios->audio($audioID);
        
        $fileName = "../../../../hc_uploads/".$file['churchID']."/".$file['audioFileLocation']."/".$file['audioFileName'];
        
        header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename='.basename($fileName));
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($fileName));
	    ob_clean();
	    flush();
	    readfile($fileName);
		
	}
	
	/** DELETE AUDIO FILE **/
	function process_delete_audio($audioID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchAudios = new HelloChurch_Audios($API);
        
        $file = $HelloChurchAudios->audio($audioID);
        
        unlink("../../../../hc_uploads/".$file['churchID']."/".$file['audioFileLocation']."/".$file['audioFileName']);
		
		$HelloChurchAudios->delete_audio($audioID);
	    
    }
    
    /** DOWNLOAD AUDIO FILE **/
    function process_download_audio($fileID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchAudios = new HelloChurch_Audios($API);
        
        $file = $HelloChurchAudios->audio($fileID);
        
        $fileName = "../../../../hc_uploads/".$file['churchID']."/".$file['audioFileLocation']."/".$file['audioFileName']; 
		
		if (file_exists($fileName))
		{
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.basename($fileName));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($fileName));
		    ob_clean();
		    flush();
		    readfile($fileName);
		    exit;
		}
		else
		{
		    echo "File does not exist";
		}
	    
    }
	
?>