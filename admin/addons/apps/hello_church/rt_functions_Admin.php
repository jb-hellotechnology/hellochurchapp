<?php
	
	/** GET LIST OF SPEAKERS **/
	function hello_church_admins(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = perch_member_get('churchID');

        $HelloChurchAdmins = new HelloChurch_Admins($API);
        
        $admins = $HelloChurchAdmins->admins($churchID);
        
		echo '<article>
				<ul class="list">';
        
        foreach($admins as $admin){
	        echo '<li>
	        		<div class="heading">
	        			<span class="material-symbols-outlined">record_voice_over</span>
				        <h3><a href="/settings/admin/edit-admin?id='.$admin['adminID'].'">'.$admin['adminEmail'].'</a></h3>
						<p class="mono">KEY: '.$admin['adminCode'].'</p>
					</div>
					<div class="functions">
						<a href="/settings/admin/edit-admin?id='.$admin['adminID'].'" class="button secondary small">View</a>
					</div>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    /** CHECK IF SIGNED IN USER IS OWNER OF SPEAKER **/
    function hello_church_admin_owner($adminID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchAdmins = new HelloChurch_Admins($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchAdmins->check_owner(perch_member_get('churchID'), $adminID);
		
		return $owner;
		
	}
	
	function perch_member_has_admin_rights(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchAdmins = new HelloChurch_Admins($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$admin = $HelloChurchAdmins->has_admin_rights($Session->get('memberID'));
		
		return $admin;
		
	}
	
	function perch_member_admin_options(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchAdmins = new HelloChurch_Admins($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$options = $HelloChurchAdmins->admin_options($Session->get('memberID'));
		
		foreach($options as $church){
			echo '<li><a href="/switch?id='.$church['churchID'].'">'.$church['churchName'].'</a></li>';
		}
		
	}
	
	function admin_type(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchAdmins = new HelloChurch_Admins($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$type = $HelloChurchAdmins->admin_type(perch_member_get('churchID'), perch_member_get('memberID'));
		
		return $type;
		
	}
	
?>