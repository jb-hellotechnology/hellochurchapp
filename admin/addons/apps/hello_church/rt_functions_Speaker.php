<?php
	
	/** GET LIST OF SPEAKERS **/
	function hello_church_speakers(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchSpeakers = new HelloChurch_Speakers($API);
        
        $speakers = $HelloChurchSpeakers->speakers($churchID);
        
		echo '<article>
				<ul class="list">';
        
        foreach($speakers as $speaker){
	        echo '<li>
	        		<div class="heading">
	        			<span class="material-symbols-outlined">record_voice_over</span>
				        <h3>'.$speaker['speakerName'].'</h3>
						<p></p>
					</div>
					<div class="functions">
						<a href="/settings/speakers/edit-speaker?id='.$speaker['speakerID'].'" class="button secondary small">View</a>
					</div>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    /** CHECK IF SIGNED IN USER IS OWNER OF SPEAKER **/
    function hello_church_speaker_owner($speakerID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchSpeakers = new HelloChurch_Speakers($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchSpeakers->check_owner($Session->get('churchID'), $speakerID);
		
		return $owner;
		
	}
	
?>