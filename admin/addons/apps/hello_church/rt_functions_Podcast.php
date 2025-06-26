<?php
	
	/** CREATE PODCAST FEED */
	function podcast_feed($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchAudios = new HelloChurch_Audios($API);
	    
		$audios = $HelloChurchAudios->audiosPodcast($churchID);
		
		foreach($audios as $audio){
			
			$filename = '../../../../hc_uploads/'.$churchID.'/'.$audio['audioFileLocation'].'/'.$audio['audioFileName'];
			$filesize = filesize($filename);
			
			$pubDate= date("D, d M Y H:i:s T", strtotime($audio['audioDate']));
		
		    echo '
		    <item>
				<title>'.$audio['audioName'].'</title>
				<description>
				'.$audio['audioDescription'].'
				</description>
				<enclosure 
				length="'.$filesize.'" 
				type="audio/mpeg" 
				url="http://app.churchplanner.co.uk/podcast/episode/'.$audio['audioID'].'/audio.mp3"
				/>
				<guid>hellochurch_'.$audio['audioID'].'</guid>
				<pubDate>'.$pubDate.'</pubDate>
			</item>';
		
		}
	    
    }
    
    /** GET PODCAST DATA **/
    function podcast(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchPodcasts = new HelloChurch_Podcasts($API);
	    
	    $Session = PerchMembers_Session::fetch();
	    
		$podcast = $HelloChurchPodcasts->podcast(perch_member_get('churchID'));
		
		return $podcast;
	    
    }
    
    /** GET PDOCAST DATA PUBLIC **/
    function podcast_public($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchPodcasts = new HelloChurch_Podcasts($API);
	    
		$podcast = $HelloChurchPodcasts->podcast($churchID);
		
		return $podcast;
	    
    }
    
?>