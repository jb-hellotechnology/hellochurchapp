<?php
	
	/** CHECK IF SIGNED IN USER IS OWNER OF NOTE **/
	function hello_church_note_owner($noteID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contact_Notes($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchContacts->check_owner($Session->get('churchID'), $noteID);
		
		return $owner;
		
	}
	
?>