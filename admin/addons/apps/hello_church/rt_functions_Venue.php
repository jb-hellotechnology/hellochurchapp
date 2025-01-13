<?php

	/** GET VENUE DATA **/
	function hello_church_venues(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchVenues = new HelloChurch_Venues($API);
        
        $venues = $HelloChurchVenues->venues($churchID);
        
		echo '<article>
				<ul class="list">';
        
        foreach($venues as $venue){
	        $description = strip_tags($venue['venueDescription']);
	        echo '<li>
	        		<div class="heading">
		        		<span class="material-symbols-outlined">home_work</span>
				        <h3>'.$venue['venueName'].'</h3>
						<p>'.$description.'</p>
					</div>
					<div class="functions">
						<a href="/settings/venues/edit-venue?id='.$venue['venueID'].'" class="button secondary small">View</a>
					</div>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    /** CREATE LIST OF VENUES FOR TAGIFY FIELD **/
    function hello_church_venues_tagify(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchVenues = new HelloChurch_Venues($API);
        
        $venues = $HelloChurchVenues->venues($churchID);
        
		$html = '[';
        
        foreach($venues as $venue){
	        $html .=  "'".$venue['venueName']."', ";
        }
        
        if(strlen($html)>1){
        	$html = substr($html, 0 , -2);
        }

        $html .= ']';
        
        return $html;
	    
    }
    
    /** CHECKED SIGNED IN USER IS OWNER OF VENUE **/
    function hello_church_venue_owner($venueID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchVenues = new HelloChurch_Venues($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchVenues->check_owner($Session->get('churchID'), $venueID);

		return $owner;
		
	}
    
?>