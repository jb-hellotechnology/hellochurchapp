<?php
	
	/** CREATE LIST OF GROUPS **/
	function hello_church_groups(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchGroups = new HelloChurch_Groups($API);
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$churchID = perch_member_get('churchID');
		
		$html = '';
		
		if($churchID){

			$groups = $HelloChurchGroups->groups($churchID);
			
			if($groups){
				
				$html .= '<p class="section-heading">All Groups</p>';
				
				$html .= '
					<ul class="list">';
				
				foreach($groups as $group){
					$description = strip_tags($group['groupDescription']);
					$html .= '<li>
								<div class="heading">
									<span class="material-symbols-outlined">groups</span>
									<h3><a href="/groups/edit-group?id='.$group['groupID'].'">'.$group['groupName'].'</a></h3>
									<p>'.$description.'</p>
								</div>
								<div class="functions">
									<a href="/groups/edit-group?id='.$group['groupID'].'" class="button secondary small">View</a>
								</div>
							</li>';
				}
				
				$html .= '
					</ul>';
				
			}else{
				$html .= '<p class="alert warning">No groups.</p>';
			}
			
			
		}else{
			$html .= '<article class="flow"><p class="alert warning">No church defined - please contact support.</p></article>';
		}
		
		echo $html;
		
	}
	
	/** CHECKED SIGNED IN USER IS OWNER OF GROUP **/
	function hello_church_group_owner($groupID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchGroups = new HelloChurch_Groups($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchGroups->check_owner(perch_member_get('churchID'), $groupID);
		return $owner;
		
	}
	
	/** SEARCH GROUP MEMBERS **/
	function process_search_members($q, $groupID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$contacts = $HelloChurchContacts->search_members(perch_member_get('churchID'), $q);
		
		foreach($contacts as $contact){
			echo '
			<form method="post" action="/process/add-group-member">
				<label>'.$contact['contactFirstName'].' '.$contact['contactLastName'].'</label>
				<input type="hidden" name="groupID" value="'.$groupID.'" />
				<input type="hidden" name="contactID" value="'.$contact['contactID'].'" />
				<input type="submit" class="button primary small" value="Add Member" />
			</form>';
		}
	    
    }
    
    /** LIST OF GROUP MEMBERS **/
    function process_search_members_list($groupID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchGroups = new HelloChurch_Groups($API);
		
		$contacts = $HelloChurchGroups->group_members($groupID);
		
		return $contacts;
	    
    }
    
    /** ADD CONTACT TO GROUP **/
    function process_add_group_member($groupID, $contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchGroups = new HelloChurch_Groups($API);
        $HelloChurchChurches = new HelloChurch_Churches($API);
        
        $Session = PerchMembers_Session::fetch();

	    $HelloChurchGroups->add_group_member($Session->get('memberID'), perch_member_get('churchID'), $groupID, $contactID);
	    
    }
    
    /** REMOVE CONTACT FROM GROUP **/
    function process_remove_group_member($groupID, $contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchGroups = new HelloChurch_Groups($API);

	    $HelloChurchGroups->remove_group_member($groupID, $contactID);
	    
    }
	
	/** LIST GROUP MEMBERS **/
	function hello_church_group_members($groupID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchGroups = new HelloChurch_Groups($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$group = $HelloChurchGroups->group_members($groupID);
		
		if(count($group)>0){
		
			$html = '<ul class="cards">';
			
			foreach($group as $member){
				$html .= '
				<li class="flow">
					<span class="material-symbols-outlined">person</span>
					<h3><a href="/contacts/edit-contact?id='.$member['contactID'].'">'.$member['contactFirstName'].' '.$member['contactLastName'].'</a></h3>
					<form method="post" action="/process/remove-group-member">
						<input type="hidden" name="groupID" value="'.$member['groupID'].'" />
						<input type="hidden" name="contactID" value="'.$member['contactID'].'" />
						<input type="hidden" name="primary" value="'.$Session->get('memberID').'" />
						<button class="button border danger small"><span class="material-symbols-outlined">person_remove</span></button>
					</form>
				</li>';
			}
			
			$html .= '</ul>';
		
		}else{
			$html = '<p class="alert">No group members defined.</p>';
		}
		
		echo $html;
	    
    }
    
    /** GET GROUP FIELD **/
    function hello_church_group_get($id, $field){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchGroups = new HelloChurch_Groups($API);
		
		$group = $HelloChurchGroups->find($id);
		return $group->$field();
		
	}
	
	/** GET GROUP DATA **/
	function hello_church_group($id){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchGroups = new HelloChurch_Groups($API);
		
		$group = $HelloChurchGroups->find($id);
		return $group;
		
	}
	
	/** LIST GROUPS **/
	function hello_church_contact_groups($contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchGroups = new HelloChurch_Groups($API);

        $groups = $HelloChurchGroups->by_contactID($contactID);

		echo '<article>
				<ul class="list">';
        
        foreach($groups as $group){
	        $description = substr(strip_tags($group['groupDescription']), 0, 50);
	        echo '<li>
	        		<div class="heading">
		        		<span class="material-symbols-outlined">groups</span>
				        <h3><a href="/groups/edit-group?id='.$group['groupID'].'">'.$group['groupName'].'</a></h3>
						<p>'.$description.'</p>
					</div>
					<div class="functions">
						<a href="/groups/edit-group?id='.$group['groupID'].'" class="button secondary small">View</a>
					</div>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    /** CREATE LIST OF GROUPS FOR TAGIFY FIELD **/
    function hello_church_groups_tagify(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $memberID = $Session->get('memberID');
	    $churchID = perch_member_get('churchID');

        $HelloChurchGroups = new HelloChurch_Groups($API);
        
        $groups = $HelloChurchGroups->groups($churchID);
        
		$html = '[';
        
        foreach($groups as $group){
	        $html .=  "{id: ".$group['groupID'].", value:'".$group['groupName']."'}, ";
        }
        
        if(strlen($html)>1){
        	$html = substr($html, 0 , -2);
        }

        $html .= ']';
        
        return $html;
	    
    }
	
?>