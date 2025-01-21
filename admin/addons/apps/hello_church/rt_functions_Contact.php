<?php
	
	/** GET CONTACT TAG OPTIONS FOR BULK APPLY **/
	function hello_church_contact_tag_options($tag){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchChurches = new HelloChurch_Churches($API);
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$church = $HelloChurchChurches->church(perch_member_get('churchID'));
		$HelloChurchContacts->tag_options($church['churchID'], $tag);
	    
    }
    
    /** BULK DELETE **/
    function process_delete_contacts($data){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		foreach($data as $contactID){
			$owner = $HelloChurchContacts->check_owner(perch_member_get('churchID'), $contactID);
			if($owner){
				$contact = $HelloChurchContacts->find($contactID);
				$contact->delete_tags($contact->id(), $data);
		        $contact->delete(); 
			}
		}
	    
    }
    
    /** BULK APPLY TAGS **/
    function process_tag_contacts($contacts, $tag){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();

		foreach($contacts as $contactID){
			$owner = $HelloChurchContacts->check_owner(perch_member_get('churchID'), $contactID);
			if($owner){
				$contact = $HelloChurchContacts->find($contactID);
				$HelloChurchContacts->bulk_update_tags($contactID, $tag);
			}
		}
	    
    }
    
    /** RETURN CONTACT PREFERENCES **/
    function contact_preferences($email, $sms){
		
		$html = '<ul class="preferences">';
		
		if($email=='Yes'){
			$html .= '<li class="on">Email</li>';
		}else{
			$html .= '<li class="off">Email</li>';
		}
		
		if($sms=='Yes'){
			$html .= '<li class="on"><span>SMS</li>';
		}else{
			$html .= '<li class="off">SMS</li>';
		}
		
		$html .= '</ul>';
		
		return $html;
		
	}
    
    /** RETURN CONTACT TAGS **/
    function contact_tags($tags){
		
		$tags = json_decode($tags, true);
		
		if($tags){
		
			if(count($tags)>0){
				$html = '<ul class="pills">';
			}
	
			foreach($tags as $tag){
				$html .= '<li><span class="material-symbols-outlined">check_circle</span>'.$tag['value'].'</li>';
			}
			
			if(count($tags)>0){
				$html .= '</ul>';
			}
		
		}
		
		return $html;
		
	}
    
    /** LIST CONTACT NOTES **/
    function hello_church_contact_notes($contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchContactNotes = new HelloChurch_Contact_Notes($API);
        
        $notes = $HelloChurchContactNotes->by_contactID($contactID);

		echo '<article>
				<ul class="list notes">';
        
        foreach($notes as $note){
	        $parts = explode(" ", $note['timestamp']);
	        $parts = explode("-", $parts[0]);
	        $date = "$parts[2]/$parts[1]/$parts[0]";
	        echo '<li>
	        		<div class="heading">
		        		<span class="material-symbols-outlined">edit_note</span>
			        	<h3><a href="/contacts/edit-note?id='.$contactID.'&noteID='.$note['noteID'].'">'.$note['subject'].'</a></h3>
						<p class="mono">'.$date.'</p>
					</div>
					<div class="functions">
						<a href="/contacts/edit-note?id='.$contactID.'&noteID='.$note['noteID'].'" class="button secondary small">View</a>
					</div>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    /** GET CONTACT FIELD BASED ON ID **/
    function hello_church_contact_get($id, $field){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		
		$contact = $HelloChurchContacts->find($id);
		return $contact->$field();
		
	}
	
	/** RETURN CONTACT DATA **/
	function hello_church_contact($id){
		
		$API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();

        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
        $contact = $HelloChurchContacts->find($id);
        
        return $contact;
		
	}
	
	/** COUNT CONTACTS **/
	function hello_church_contacts_count(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = perch_member_get('churchID');

        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
        $contacts = $HelloChurchContacts->all_contacts($churchID);
        
		return count($contacts);
	    
    }
	
	/** CREATE LIST OF CONTACTS FOR TAGIFY FIELD **/
	function hello_church_contacts_tagify(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = perch_member_get('churchID');

        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
        $contacts = $HelloChurchContacts->all_contacts_email($churchID);
        
		$html = '[';
        
        foreach($contacts as $contact){
	        $html .=  "{id: ".$contact['contactID'].", value:'".addslashes($contact['contactFirstName'])." ".addslashes($contact['contactLastName'])."'}, ";
        }
        
        if(strlen($html)>1){
        	$html = substr($html, 0 , -2);
        }

        $html .= ']';
        
        return $html;
	    
    }
	
	/** CREATE TABLE OF CHURCH CONTACTS **/
	function hello_church_contacts($tag, $q, $page){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$churchID = perch_member_get('churchID');
		
		$html = '';
		
		if($churchID){
			
			$totalContacts = $HelloChurchContacts->totalContacts($Session->get('memberID'), $churchID, $tag, $q);
			$pages = ceil($totalContacts/25);
			$contacts = $HelloChurchContacts->contacts($Session->get('memberID'), $churchID, $tag, $q, $page);
			
			if($contacts){
				
				if($q=='' AND $tag==''){
					$html .= '<p class="section-heading">All contacts</p>';
				}elseif($q<>'' AND $tag==''){
					$html .= '<p class="section-heading">All contacts matching <strong>'.$q.'</strong></p>';
				}elseif($q=='' AND $tag<>''){
					$html .= '<p class="section-heading">All contacts by tag <strong>'.ucwords(urldecode($tag)).'</strong></p>';
				}else{
					$html .= '<p class="section-heading">All contacts by tag <strong>'.$tag.'</strong> and matching <strong>'.$q.'</strong></p>';
				}
				
				$html .= '
					<div class="grid contacts flow">
						<div class="row heading">
							<div class="th">
								<h3>Name</h3>
							</div>
							<div class="th">
								<h3>Address</h3>
							</div>
							<div class="th">
								<h3>Contact</h3>
							</div>
							<div class="th">
								<h3>Tags</h3>
							</div>
							<div class="th">
								<h3>View</h3>
							</div>
							<div class="th">
							
							</div>
						</div>';
				
				foreach($contacts as $contact){
					
					$tags = contact_tags($contact['contactTags']);
					$preferences = contact_preferences($contact['contactAcceptEmail'], $contact['contactAcceptSMS']);
					
					$html .= '
						<div class="row">
							<div class="td">
								<a href="/contacts/edit-contact?id='.$contact['contactID'].'"><span class="material-symbols-outlined">person</span>'.$contact['contactFirstName'].' '.$contact['contactLastName'].'</a>
							</div>
							<div class="td">';
							if($contact['contactAddress1']){
								$html .= '<p><span class="material-symbols-outlined">home</span>'.$contact['contactAddress1'].'</p>';
							}
							$html .= '
							</div>
							<div class="td">';
							if($contact['contactPhone']){
								$html .= '<p><span class="material-symbols-outlined">phone</span>'.$contact['contactPhone'].'</p>';
							}
							if($contact['contactEmail']){
								$html .= '<p><span class="material-symbols-outlined">email</span>'.$contact['contactEmail'].'</p>';
							}
							$html .= '
							</div>
							<div class="td">
								'.$tags.'
							</div>
							<div class="td">
								<a href="/contacts/edit-contact?id='.$contact['contactID'].'" class="button secondary small">View</a>
							</div>
							<div class="td">
								<input type="checkbox" class="contact_select" name="select_'.$contact['contactID'].'" data-contact="'.$contact['contactID'].'" />
							</div>
						</div>';
				}
				
				$html .= '
						
					</div>';
				
			}elseif($q<>'' AND $tag<>''){
				$html .= '<p class="alert error"><span class="material-symbols-outlined">error</span>No contacts found, try removing the tag or changing your search query</p>';
			}elseif($q<>''){
				$html .= '<p class="alert error"><span class="material-symbols-outlined">error</span>No contacts matching this search query</p>';
			}else{
				$html .= '<p class="alert error"><span class="material-symbols-outlined">error</span>No contacts</p>';
			}
			
			if($pages>0){
				$pagination = '<div class="pagination"><label for="page">Page</label><select name="page" onchange="this.form.submit();" id="page">';
				$i = 1;
				while($i<=$pages){
					$pagination .= '<option value="'.$i.'"';
					if($page==$i){
						$pagination .= ' SELECTED';
					}
					$pagination .= '>'.$i.'</option>';
					$i++;
				}
				$pagination .= '</select></div>';
			}
			
			
		}else{
			$html .= '<article class="flow"><p class="alert warning">No church defined - please contact support.</p></article>';
		}
		
		$html .= '<footer>
					'.$pagination.'
					<a class="button primary" href="/contacts/add-contact">Add a Contact</a>
				</footer>';
		
		echo $html;
		
	}
	
	/** CREATE TABLE OF RECENTLY ADDED CONTACTS **/
	function hello_church_recent_contacts(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$churchID = perch_member_get('churchID');
		
		$html = '';
		
		if($churchID){
			
			$contacts = $HelloChurchContacts->recent_contacts($Session->get('memberID'), $churchID);
			
			if($contacts){
				
				$html .= '
					<div class="grid contacts flow">
						<div class="row heading">
							<div class="th">
								<h3>Name</h3>
							</div>
							<div class="th">
								<h3>Address</h3>
							</div>
							<div class="th">
								<h3>Contact</h3>
							</div>
							<div class="th">
								<h3>Tags</h3>
							</div>
							<div class="th">
								<h3>View</h3>
							</div>
							<div class="th">
							</div>
						</div>';
				
				foreach($contacts as $contact){
					
					$tags = contact_tags($contact['contactTags']);
					$preferences = contact_preferences($contact['contactAcceptEmail'], $contact['contactAcceptSMS']);
					
					$html .= '
						<div class="row">
							<div class="td">
								<a href="/contacts/edit-contact?id='.$contact['contactID'].'"><span class="material-symbols-outlined">person</span>'.$contact['contactFirstName'].' '.$contact['contactLastName'].'</a>
								
							</div>
							<div class="td">';
							if($contact['contactAddress1']){
								$html .= '<p><span class="material-symbols-outlined">home</span>'.$contact['contactAddress1'].'</p>';
							}
							$html .= '
							</div>
							<div class="td">';
							if($contact['contactPhone']){
								$html .= '<p><span class="material-symbols-outlined">phone</span>'.$contact['contactPhone'].'</p>';
							}
							if($contact['contactEmail']){
								$html .= '<p><span class="material-symbols-outlined">email</span>'.$contact['contactEmail'].'</p>';
							}
							$html .= '
							</div>
							<div class="td">
								'.$tags.'
							</div>
							<div class="td">
								<a href="/contacts/edit-contact?id='.$contact['contactID'].'" class="button secondary small">View</a>
							</div>
							<div class="td">
								
							</div>
						</div>';
				}
				
				$html .= '
						
					</div>';
				
			}
			
			
		}else{
			$html .= '<article class="flow"><p class="alert warning">No church defined - please contact support.</p></article>';
		}
		
		$html .= '<footer>
					'.$pagination.'
					<a class="button secondary" href="/contacts/add-contact">Add a Contact</a>
				</footer>';
		
		echo $html;
		
	}
	
	/** CHECK SIGNED IN USER IS OWNER OF CONTACT **/
	function hello_church_member_owner($contactID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchContacts->check_owner(perch_member_get('churchID'), $contactID);
		
		return $owner;
		
	}
	
	/** SEND MAGIC SIGN IN LINK **/
    function send_link($church, $email){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchChurches = new HelloChurch_Churches($API);
        $HelloChurchContacts = new HelloChurch_Contacts($API);

		$church = $HelloChurchChurches->church_by_slug($church);
		$valid = $HelloChurchContacts->by_church_by_email($church['churchID'], $email);
		
		if($valid){
			echo 'success';
			$HelloChurchContacts->send_magic_link($church['churchID'], $email);
		}else{
			echo 'error';
		}
	    
    }
    
    /** SIGN IN VIA MAGIC LINK **/
    function sign_in_magic($password, $email){
	    
	    session_start();
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchContacts = new HelloChurch_Contacts($API);

		$check = $HelloChurchContacts->validate_magic($password, $email);
		
		if($check['churchID']){
			$_SESSION['hellochurch_active_session'] = true;
			$_SESSION['hellochurch_active_church'] = $check['churchID'];
			$_SESSION['hellochurch_active_contact'] = $check['contactID'];
		}else{
			$_SESSION['hellochurch_active_session'] = false;
		}
	    
    }
    
    /** CHECK IF SIGNED IN **/
    function signed_in(){
	    
	    session_start();
	    
	    if($_SESSION['hellochurch_active_session']==true && $_SESSION['hellochurch_active_church']>0 && $_SESSION['hellochurch_active_contact']>0){
		    return true;
	    }else{
		    return false;
	    }
	    
    }
    
?>