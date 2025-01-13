<?php
	
	/** GET LIST OF SERIES **/
	function hello_church_series(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchSeriess = new HelloChurch_Seriess($API);
        
        $seriess = $HelloChurchSeriess->seriess($churchID);

		echo '<article>
				<ul class="list">';
        
        foreach($seriess as $series){
	        $description = strip_tags($series['seriesDescription']);
	        echo '<li>
	        		<div class="heading">
	        			<span class="material-symbols-outlined">book</span>
				        <h3>'.$series['seriesName'].'</h3>
						<p>'.$description.'</p>
					</div>
					<div class="functions">
						<a href="/settings/series/edit-series?id='.$series['seriesID'].'" class="button secondary small">View</a>
					</div>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
  
	/** CHECK IF SIGNED IN USER IS OWNER OF SERIES **/
  	function hello_church_series_owner($seriesID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchSeriess = new HelloChurch_Seriess($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchSeriess->check_owner($Session->get('churchID'), $seriesID);
		
		return $owner;
		
	}
	  
?>