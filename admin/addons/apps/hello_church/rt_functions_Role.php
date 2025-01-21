<?php
	
	/** GET LIST OF ROLES **/
	function hello_church_roles(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = perch_member_get('churchID');

        $HelloChurchRoles = new HelloChurch_Roles($API);
        
        $roles = $HelloChurchRoles->roles($churchID);
        
		echo '<article>
				<ul class="list">';
        
        foreach($roles as $role){
	        $description = strip_tags($role['roleDescription']);
	        echo '<li>
	        		<div class="heading">
		        		<span class="material-symbols-outlined">badge</span>
				        <h3><a href="/settings/roles/edit-role?id='.$role['roleID'].'">'.$role['roleName'].'</a></h3>
				        <p>'.$description.'</p>
			        </div>
			        <div class="functions">
						<a href="/settings/roles/edit-role?id='.$role['roleID'].'" class="button secondary small">View</a>
					</div>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    /** CREATE LIST OF ROLES FOR TAGIFY FIELD **/
    function hello_church_roles_tagify(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = perch_member_get('churchID');

        $HelloChurchRoles = new HelloChurch_Roles($API);
        
        $roles = $HelloChurchRoles->roles($churchID);
        
		$html = '[';
        
        foreach($roles as $role){
	        $html .=  "'".addslashes($role['roleName'])."', ";
        }
        
        if(strlen($html)>1){
        	$html = substr($html, 0 , -2);
        }

        $html .= ']';
        
        return $html;
	    
    }
    
    /** CHECK SIGNED IN USER IS OWNER OF ROLE **/
    function hello_church_role_owner($roleID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchRoles = new HelloChurch_Roles($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchRoles->check_owner(perch_member_get('churchID'), $roleID);
		
		return $owner;
		
	}
	
	/** CREATE INTERFACE FOR ADDING USERS TO ROLES FOR EVENT **/
	function hello_church_event_roles($id){
		
		$API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = perch_member_get('churchID');

        $HelloChurchEvents = new HelloChurch_Events($API);
        $HelloChurchRoles = new HelloChurch_Roles($API);
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
        $roles = $HelloChurchEvents->event_roles($id);
        $roles = json_decode($roles['roles'], true);
        
        $i = 0;
        
        foreach($roles as $role){
	        $roleData = $HelloChurchRoles->role_byName($role['value'], $churchID);
	        $html = '
	        <section>
	        	<header>
	        		<h2>'.$role['value'].'</h2>
	        		<div id="role_'.$i.'">
	        			<input type="text" name="q" class="q" placeholder="Add Contact" value="" onkeyup="searchRoleContacts(\'role_'.$i.'\');" autocomplete="off" data-event-id="'.perch_get('id').'" data-event-date="'.perch_get('date').'" data-event-role="'.$roleData['roleID'].'" />
						<div class="results">
							
						</div>
	        		</div>
	        	</header>
	        	<article>
	        	<ul class="list">';

				$contacts = $HelloChurchEvents->event_contact_roles($id, perch_get('date'), $roleData['roleID']);
				foreach($contacts as $contact){
					$contactData = $HelloChurchContacts->contact($contact['contactID']);
					$html .= '<li>
								<div class="heading">
									<span class="material-symbols-outlined">person</span>
									<h3><a href="/contacts/edit-contact?id='.$contactData['contactID'].'">'.$contactData['contactFirstName']." ".$contactData['contactLastName'];
							if($roleData['roleType']=='Family'){
								$html .= ' &plus; Family';
							}
					$html .= '</a></h3>
							<p></p>
							</div>
							<div class="functions">
								<form action="/process/remove-role-contact" method="post">
									<input type="submit" class="button border danger small" value="Remove" />
									<input type="hidden" name="roleContactID" value="'.$contact['roleContactID'].'" />
									<input type="hidden" name="eventID" value="'.perch_get('id').'" />
									<input type="hidden" name="date" value="'.perch_get('date').'" />
								</form>
							</div>
						</li>';
				}

	        $html .= '</ul>
	        	</article>
	        </section>';
	        $i++;
	        echo $html;
        }
        
	}
	
	/** SEARCH ROLE MEMBERS **/
	function process_search_role_members($q, $eventID, $eventDate, $roleID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$contacts = $HelloChurchContacts->search_role_members(perch_member_get('churchID'), $q, $eventID, $eventDate);
		
		foreach($contacts as $contact){
			echo '
			<form method="post" action="/process/add-role-contact">
				<input type="hidden" name="eventID" value="'.$eventID.'" />
				<input type="hidden" name="eventDate" value="'.$eventDate.'" />
				<input type="hidden" name="contactID" value="'.$contact['contactID'].'" />
				<input type="hidden" name="roleID" value="'.$roleID.'" />
				<button class="button primary small" value="Add Member">'.$contact['contactFirstName'].' '.$contact['contactLastName'].'</button>
			</form>';
		}
	    
    }
    
    /** ADD CONTACT TO ROLE **/
    function process_add_role_contact($eventID, $eventDate, $contactID, $roleID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchEvents = new HelloChurch_Events($API);
        
        $Session = PerchMembers_Session::fetch();

	    $HelloChurchEvents->add_role_contact($Session->get('memberID'), perch_member_get('churchID'), $eventID, $eventDate, $contactID, $roleID);
	    
    }
    
    /** REMOVE CONTACT FROM ROLE **/
    function process_remove_role_contact($eventID, $eventDate, $roleContactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchEvents = new HelloChurch_Events($API);
        
        $Session = PerchMembers_Session::fetch();

	    $HelloChurchEvents->remove_role_contact($roleContactID);
	    
    }
	
?>