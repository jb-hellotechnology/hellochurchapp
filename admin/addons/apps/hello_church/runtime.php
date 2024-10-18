<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

    spl_autoload_register(function($class_name){
        if (strpos($class_name, 'HelloChurch')===0) {
            include(__DIR__.'/'.$class_name.'.class.php');
            return true;
        }
        return false;
    });
    
    spl_autoload_register(function($class_name){
        if (strpos($class_name, 'PerchMembers')===0) {
            include(__DIR__.'/'.$class_name.'.class.php');
            return true;
        }
        return false;
    });

    PerchSystem::register_template_handler('HelloChurch_Template');

	function hello_church_church(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$church = $HelloChurchChurches->church($Session->get('churchID'));
		if($church){
			return true;
		}else{
			return false;
		}
		
	}
	
	function hello_church_member_owner($contactID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchContacts->check_owner($Session->get('memberID'), $contactID);
		
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	function hello_church_note_owner($noteID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contact_Notes($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchContacts->check_owner($Session->get('memberID'), $noteID);
		
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
	}
	
	function hello_church_contact_get($id, $field){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		
		$contact = $HelloChurchContacts->find($id);
		return $contact->$field();
		
	}
	
	function hello_church_contacts($tag, $q, $page){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$churchID = $Session->get('churchID');
		
		$html = '';
		
		if($churchID){
			
			$totalContacts = $HelloChurchContacts->totalContacts($Session->get('memberID'), $churchID, $tag, $q);
			$pages = ceil($totalContacts/25);
			$contacts = $HelloChurchContacts->contacts($Session->get('memberID'), $churchID, $tag, $q, $page);
			
			if($contacts){
				
				if($q=='' AND $tag==''){
					$html .= '<p class="section-heading">All Contacts</p>';
				}elseif($q<>'' AND $tag==''){
					$html .= '<p class="section-heading">All Contacts Matching \''.$q.'\'</p>';
				}elseif($q=='' AND $tag<>''){
					$html .= '<p class="section-heading">All Contacts By Tag \''.ucwords(urldecode($tag)).'\'</p>';
				}else{
					$html .= '<p class="section-heading">All Contacts By Tag \''.$tag.'\' and Matching \''.$q.'\'</p>';
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
								<input type="checkbox" class="contact_select" name="select_'.$contact['contactID'].'" data-contact="'.$contact['contactID'].'" />
							</div>
						</div>';
				}
				
				$html .= '
						
					</div>';
				
			}elseif($q<>'' AND $tag<>''){
				$html .= '<p class="alert">No contacts found. Try removing the tag or changing your search query.</p>';
			}elseif($q<>''){
				$html .= '<p class="alert">No contacts matching this search query.</p>';
			}else{
				$html .= '<p class="alert warning">No contacts.</p>';
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
	
	function hello_church_recent_contacts(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$churchID = $Session->get('churchID');
		
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
	
	function contact_tags($tags){
		
		$tags = json_decode($tags, true);
		
		if(count($tags)>0){
			$html = '<ul class="pills">';
		}

		foreach($tags as $tag){
			$html .= '<li><span class="material-symbols-outlined">check_circle</span>'.$tag['value'].'</li>';
		}
		
		if(count($tags)>0){
			$html .= '</ul>';
		}
		
		return $html;
		
	}

    function hello_church_form($template, $return=false)
    {
        $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchChurches = new HelloChurch_Churches($API);
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        $HelloChurchContactNotes = new HelloChurch_Contact_Notes($API);
        $HelloChurchGroups = new HelloChurch_Groups($API);
        $HelloChurchEvents = new HelloChurch_Events($API);
        
        $Template = $API->get('Template');
        $Template->set(PerchUtil::file_path('hellochurch/forms/'.$template), 'forms');
		
		$Session = PerchMembers_Session::fetch();
		
		$data['churchID'] = $Session->get('churchID');
		$data['memberID'] = $Session->get('memberID');
		
		if($template == 'create_church.html'){

			$data['memberID'] = $Session->get('memberID');
			$data['email'] = perch_member_get('email');
		
		}elseif($template == 'update_church.html'){

			
		}elseif($template == 'create_contact.html'){
			
			
		}elseif($template == 'update_contact.html'){
			
			$data = $HelloChurchContacts->contact($_GET['id']);
			
		}elseif($template == 'delete_contact.html'){
			
			$data = $HelloChurchContacts->contact($_GET['id']);
			
		}elseif($template == 'export_contacts.html'){
			
			
		}elseif($template == 'add_note.html'){

			$data['contactID'] = $_GET['id'];
			
		}elseif($template == 'update_note.html'){
	
			$data = $HelloChurchContactNotes->by_noteID($_GET['noteID']);
			
		}elseif($template == 'delete_note.html'){
	
			$data['noteID'] = $_GET['noteID'];
			$data['id'] = $_GET['id'];
			
		}elseif($template == 'create_group.html'){

			
		}elseif($template == 'update_group.html'){

			$data = $HelloChurchGroups->group($_GET['id']);
			
		}elseif($template == 'delete_group.html'){
			
			$data['groupID'] = $_GET['id'];
			
		}elseif($template == 'create_event.html'){

			
		}elseif($template == 'update_event.html'){

			$data = $HelloChurchEvents->event($_GET['id']);
			
		}elseif($template == 'delete_event.html'){
			
			$data['eventID'] = $_GET['id'];
			
		}
		
        $html = $Template->render($data);
        $html = $Template->apply_runtime_post_processing($html, $data);

        if ($return) return $html;
        echo $html;
    }
    
    function hello_church_contact_tag_options($tag){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchChurches = new HelloChurch_Churches($API);
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$church = $HelloChurchChurches->church($Session->get('churchID'));
		$HelloChurchContacts->tag_options($church['churchID'], $tag);
	    
    }
    
    function process_delete_contacts($data){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$contacts = explode(",", $data);
		
		foreach($data as $contact){
			$owner = $HelloChurchContacts->check_owner($Session->get('memberID'), $contact);
			if($owner){
				$contact = $HelloChurchContacts->find($contact);
				$contact->delete_tags($contact->id(), $data);
		        $contact->delete(); 
			}
		}
	    
    }
    
    function process_tag_contacts($contacts, $tag){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();

		foreach($contacts as $contactID){
			$owner = $HelloChurchContacts->check_owner($Session->get('memberID'), $contactID);
			if($owner){
				$contact = $HelloChurchContacts->find($contactID);
				$HelloChurchContacts->bulk_update_tags($contactID, $tag);
			}
		}
	    
    }
    
    function hello_church_family_members($contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$family = $HelloChurchContacts->family_members($Session->get('memberID'), $contactID);
		
		if(count($family)>0){
		
			$html = '<ul class="cards">';
			
			foreach($family as $member){
				$html .= '
				<li class="flow">
					<span class="material-symbols-outlined">person</span>
					<h3><a href="/contacts/edit-contact?id='.$member['contactID'].'">'.$member['contactFirstName'].' '.$member['contactLastName'].'</a></h3>
					<p><a class="button secondary small" href="/contacts/edit-contact?id='.$member['contactID'].'">View</a></p>
					<form method="post" action="/process/remove-family-member">
						<input type="hidden" name="identifier" value="'.$member['identifier'].'" />
						<input type="hidden" name="contactID" value="'.$member['contactID'].'" />
						<input type="hidden" name="primary" value="'.$contactID.'" />
						<input type="submit" class="button border danger small" value="Unlink" />
					</form>
				</li>';
			}
			
			$html .= '</ul>';
		
		}else{
			$html = '<p class="alert">No family members defined.</p>';
		}
		
		echo $html;
	    
    }
    
    function process_search_family_members($q, $memberID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$contacts = $HelloChurchContacts->search_family_members($Session->get('memberID'), $q, $memberID);
		
		foreach($contacts as $contact){
			echo '
			<form method="post" action="/process/add-family-member">
				<label>'.$contact['contactFirstName'].' '.$contact['contactLastName'].'</label>
				<input type="hidden" name="primary" value="'.$memberID.'" />
				<input type="hidden" name="contactID" value="'.$contact['contactID'].'" />
				<input type="submit" class="button primary small" value="Add Member" />
			</form>';
		}
	    
    }
    
    function process_add_family_member($primary, $contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
        $Session = PerchMembers_Session::fetch();

	    $HelloChurchContacts->add_family_member($Session->get('memberID'), $primary, $contactID);
	    
    }
    
    function process_remove_family_member($identifier, $contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchContacts = new HelloChurch_Contacts($API);

	    $HelloChurchContacts->remove_family_member($identifier, $contactID);
	    
    }
    
    function hello_church_contact_notes($contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchContactNotes = new HelloChurch_Contact_Notes($API);
        
        $notes = $HelloChurchContactNotes->by_contactID($contactID);

		echo '<article>
				<ul class="list">';
        
        foreach($notes as $note){
	        $parts = explode(" ", $note['timestamp']);
	        $parts = explode("-", $parts[0]);
	        $date = "$parts[2]/$parts[1]/$parts[0]";
	        echo '<li>
			        <h3>'.$note['subject'].'</h3>
					<p>'.$date.'</p>
					<a href="/contacts/edit-note?id='.$contactID.'&noteID='.$note['noteID'].'" class="button secondary small">View</a>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    function hello_church_contact_groups($contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchGroups = new HelloChurch_Groups($API);

        $groups = $HelloChurchGroups->by_contactID($contactID);

		echo '<article>
				<ul class="list">';
        
        foreach($groups as $group){
	        $description = substr(strip_tags($group['groupDescription']), 0, 50);
	        echo '<li>
			        <h3>'.$group['groupName'].'</h3>
					<p>'.$description.'</p>
					<a href="/groups/edit-group?id='.$group['groupID'].'" class="button secondary small">View</a>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    function hello_church_form_handler($SubmittedForm) {
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $PerchMembers_Auth = new PerchMembers_Auth($API);
	    $HelloChurchChurch = new HelloChurch_Church($API);
	    $HelloChurchChurches = new HelloChurch_Churches($API); 
	    $HelloChurchContact = new HelloChurch_Contact($API);
	    $HelloChurchContacts = new HelloChurch_Contacts($API); 
	    $HelloChurchContactNote = new HelloChurch_Contact_Note($API);
	    $HelloChurchContactNotes = new HelloChurch_Contact_Notes($API); 
	    $HelloChurchGroup = new HelloChurch_Group($API);
	    $HelloChurchGroups = new HelloChurch_Groups($API);
	    $HelloChurchEvent = new HelloChurch_Event($API);
	    $HelloChurchEvents = new HelloChurch_Events($API);
	    
	    $Session = PerchMembers_Session::fetch();

        switch($SubmittedForm->formID) {
            case 'create_church':
	            $valid = $HelloChurchChurches->church_valid($SubmittedForm->data);
	            if(!$valid){
		            //$SubmittedForm->throw_error($valid['reason'], $valid['field']);
	            }else{
		            $data = $SubmittedForm->data;
		            $data['churchSlug'] = str_replace(" ", "-", $data['churchName']);
		            $data['churchProperties'] = '';
	            	$HelloChurchChurches->create($data);
	            } 
            break;
            case 'update_church':
	            $valid = $HelloChurchChurches->church_valid($SubmittedForm->data);
	            if(!$valid){
		            //$SubmittedForm->throw_error($valid['reason'], $valid['field']);
	            }else{
		            $church = $HelloChurchChurches->find($SubmittedForm->data['churchID']);
		            $church->update($SubmittedForm->data);
	            } 
            break;
			case 'create_contact':
	            $valid = $HelloChurchContacts->contact_valid($SubmittedForm->data);
	            if(!$valid){
		            //$SubmittedForm->throw_error($valid['reason'], $valid['field']);
	            }else{
		            $data = $SubmittedForm->data;
		            $data['contactProperties'] = '';
	            	$contact = $HelloChurchContacts->create($data);
	            	$contact->update_tags($contact->id(), $data);
	            	$contact->update_groups($contact->id(), $data);
	            } 
            break;
            case 'update_contact':
	            $valid = $HelloChurchContacts->contact_valid($SubmittedForm->data);
	            if(!$valid){
		            //$SubmittedForm->throw_error($valid['reason'], $valid['field']);
	            }else{
		            $contact = $HelloChurchContacts->find($SubmittedForm->data['contactID']);
		            $data = $SubmittedForm->data;
		            if(!$data['contactAcceptSMS']){
			            $data['contactAcceptSMS'] = '';
		            }
		            if(!$data['contactAcceptEmail']){
			            $data['contactAcceptEmail'] = '';
		            }
		            $contact->update($data);
		            $contact->update_tags($contact->id(), $data);
		            $contact->update_groups($contact->id(), $data);
	            } 
            break;
            case 'delete_contact':
		        $contact = $HelloChurchContacts->find($SubmittedForm->data['contactID']);
		        $contact->delete_tags($contact->id(), $data);
		        $contact->delete(); 
            break;
            case 'export_contacts':
		        $church = $HelloChurchChurches->church($Session->get('churchID'));
				$data = $HelloChurchContacts->export($Session->get('memberID'), $church['churchID']); 
            break;
            case 'import_contacts':
            	
            	$target_dir = '../../../uploads/';
            	$number = rand();
				$target_file = $target_dir."$number.csv";
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

				$allowed = array('csv');
				$filename = $_FILES['csv']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				if (!in_array($ext, $allowed)) {
				    $SubmittedForm->throw_error('required', 'csv');
				    $uploadOk = 0;
				}
							
				// Check if file already exists
				if (file_exists($target_file)) {
					$SubmittedForm->throw_error('required', 'csv');
					$uploadOk = 0;
				}
				
				// Check file size
				if ($_FILES["csv"]["size"] > 500000) {
					$SubmittedForm->throw_error('required', 'csv');
					$uploadOk = 0;
				}
				
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
					$SubmittedForm->throw_error('required', 'csv');
				} else {
					if (move_uploaded_file($_FILES["csv"]["tmp_name"], $target_file)) {

						$file = fopen($target_file,"r");
						
						$church = $HelloChurchChurches->church($Session->get('churchID'));

						while (($data = fgetcsv($file)) !== FALSE)
						{
							if($data[0]<>'' AND $data[0]<>'First Name'){
							    $data['contactProperties'] = '';
								
								$inputData = array();
								$inputData['churchID'] = 			$church['churchID'];
								$inputData['memberID'] = 			$Session->get('memberID');
								$inputData['contactFirstName'] =	$data[0];
								$inputData['contactLastName'] =	 	$data[1];
								$inputData['contactAddress1'] =		$data[2];
								$inputData['contactAddress2'] =		$data[3];
								$inputData['contactCity'] =			$data[4];
								$inputData['contactCounty'] =		$data[5];
								$inputData['contactPostCode'] =		$data[6];
								$inputData['contactCountry'] =		$data[7];
								$inputData['contactEmail'] =		$data[8];
								if($data[8]<>'' AND substr($data[8], 0, 1)<>'0'){
									$inputData['contactPhone'] =	'0'.$data[9];
								}else{
									$inputData['contactPhone'] =		$data[9];	
								}
								$inputData['contactAcceptEmail'] =	$data[10];
								$inputData['contactAcceptSMS'] =	$data[11];
								if($data[12]<>''){
									$tagString = '[';
									$tags = explode(",", $data[12]);
									foreach($tags as $tag){
										$tagString .= '{"value":"'.trim($tag).'"},';
									}
									$tagString = substr($tagString, 0, -1);
									$tagString .= ']';
								}
								$inputData['contactTags'] =			$tagString;
								$inputData['contactProperties'] = 	'';
								
				            	$contact = $HelloChurchContacts->create($inputData);
				            	$contact->update_tags($contact->id(), $inputData);
				            }
						}
											
						unlink($target_file);
					} else {
						$SubmittedForm->throw_error('required', 'csv');
					}
				}
            break;
            case 'create_note':
	            $data = $SubmittedForm->data;
		        $note = $HelloChurchContactNotes->create($data);
            break;
            case 'update_note':
	            $note = $HelloChurchContactNotes->find($SubmittedForm->data['noteID']);
		        $note->update($SubmittedForm->data);
            break;
            case 'delete_note':
	            $note = $HelloChurchContactNotes->find($SubmittedForm->data['noteID']);
		        $note->delete();
            break;
            case 'create_group':
	            $data = $SubmittedForm->data;
		        $group = $HelloChurchGroups->create($data);
		        $group->update_tags($data);
		        $group->update_members($group->id(), $data);
            break;
            case 'update_group':
	            $group = $HelloChurchGroups->find($SubmittedForm->data['groupID']);
		        $group->update($SubmittedForm->data);
		        $group->update_tags($SubmittedForm->data);
		        $group->update_members($group->id(), $SubmittedForm->data);
            break;
            case 'delete_group':
	            $group = $HelloChurchGroups->find($SubmittedForm->data['groupID']);
				$HelloChurchGroups->remove_all_members($Session->get('memberID'), $SubmittedForm->data['groupID']);
		        $group->delete();
            break;
            case 'create_event':
	            $data = $SubmittedForm->data;
		        $note = $HelloChurchEvents->create($data);
            break;
            case 'update_event':
	            $event = $HelloChurchEvents->find($SubmittedForm->data['eventID']);
		        $event->update($SubmittedForm->data);
            break;
            case 'delete_event':
	            $event = $HelloChurchEvents->find($SubmittedForm->data['eventID']);
		        $event->delete();
            break;
        }
    	
    	// access logged errors
	    $Perch = Perch::fetch();
	    $form_errors = $Perch->get_form_errors($SubmittedForm->formID);
    }
    
    function hello_church_groups(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchGroups = new HelloChurch_Groups($API);
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$churchID = $Session->get('churchID');
		
		$html = '';
		
		if($churchID){

			$groups = $HelloChurchGroups->groups($Session->get('memberID'), $churchID);
			
			if($groups){
				
				$html .= '<p class="section-heading">All Groups</p>';
				
				$html .= '
					<ul class="list">';
				
				foreach($groups as $group){
					$description = strip_tags($group['groupDescription']);
					$html .= '<li><h3>'.$group['groupName'].'</h3><p>'.$description.'</p> <a href="/groups/edit-group?id='.$group['groupID'].'" class="button secondary small">View</a></li>';
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
	
	function hello_church_group_owner($groupID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchGroups = new HelloChurch_Groups($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchGroups->check_owner($Session->get('memberID'), $groupID);
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	function process_search_members($q, $groupID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$contacts = $HelloChurchContacts->search_members($Session->get('memberID'), $q);
		
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
    
    function process_add_group_member($groupID, $contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchGroups = new HelloChurch_Groups($API);
        $HelloChurchChurches = new HelloChurch_Churches($API);
        
        $Session = PerchMembers_Session::fetch();
        
        $churchID = $Session->get('churchID');

	    $HelloChurchGroups->add_group_member($Session->get('memberID'), $churchID, $groupID, $contactID);
	    
    }
    
    function process_remove_group_member($groupID, $contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchGroups = new HelloChurch_Groups($API);

	    $HelloChurchGroups->remove_group_member($groupID, $contactID);
	    
    }
	
	function hello_church_group_members($groupID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchGroups = new HelloChurch_Groups($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$group = $HelloChurchGroups->group_members($Session->get('memberID'), $groupID);
		
		if(count($group)>0){
		
			$html = '<ul class="cards">';
			
			foreach($group as $member){
				$html .= '
				<li class="flow">
					<span class="material-symbols-outlined">person</span>
					<h3><a href="/contacts/edit-contact?id='.$member['contactID'].'">'.$member['contactFirstName'].' '.$member['contactLastName'].'</a></h3>
					<p><a class="button secondary small" href="/contacts/edit-contact?id='.$member['contactID'].'">View</a></p>
					<form method="post" action="/process/remove-group-member">
						<input type="hidden" name="groupID" value="'.$member['groupID'].'" />
						<input type="hidden" name="contactID" value="'.$member['contactID'].'" />
						<input type="hidden" name="primary" value="'.$contactID.'" />
						<input type="submit" class="button border danger small" value="Remove" />
					</form>
				</li>';
			}
			
			$html .= '</ul>';
		
		}else{
			$html = '<p class="alert">No group members defined.</p>';
		}
		
		echo $html;
	    
    }
    
    function hello_church_group_get($id, $field){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchGroups = new HelloChurch_Groups($API);
		
		$group = $HelloChurchGroups->find($id);
		return $group->$field();
		
	}
	
	function hello_church_calendar(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEvents = new HelloChurch_Events($API);
		
		$Session = PerchMembers_Session::fetch();
		$churchID = $Session->get('churchID');
		
		$events = $HelloChurchEvents->events($churchID);
		
		$eventsHTML .= '
		events: [';
		
		foreach($events as $event){

			$firstDay = date('w', strtotime($event['start']));
			
			if($event['repeatEvent']=='daily'){
				$daysOfWeek = '[0, 1, 2, 3, 4, 5, 6]';
			}elseif($event['repeatEvent']=='weekdays'){
				$daysOfWeek = '[1, 2, 3, 4, 5]';
			}elseif($event['repeatEvent']=='weekly'){
				$daysOfWeek = '['.$firstDay.']';
			}
			
			$eventsHTML .= '
			{
		      title: "'.$event['eventName'].'",
		      start: "'.$event['start'].'",
		      end: "'.$event['end'].'",';
		      
		      if($event['repeatEvent']<>''){
			      $pStart = explode(" ", $event['start']);
			      $pEnd = explode(" ", $event['end']);
			      $eventsHTML .= '
			      daysOfWeek: "'.$daysOfWeek.'",
			      startTime: "'.$pStart[1].'",
			      endTime: "'.$pEnd[0].'",
			      startRecur: "'.$event['start'].'",
			      endRecur: "'.$event['repeatEnd'].' 23:59:59",';
		      }
		    $eventsHTML .= '
		      allDay: '.$event['allDay'].',
		      url: "/calendar/edit-event?id='.$event['eventID'].'",
		      displayEventEnd: true
		    },';
			
		}
		
		$eventsHTML = substr($eventsHTML, 0, -1);
		
		$eventsHTML .= ']';
		
		$html .= "<script>

	      document.addEventListener('DOMContentLoaded', function() {
	        var calendarEl = document.getElementById('calendar');
	        var calendar = new FullCalendar.Calendar(calendarEl, {
			  initialView: 'listWeek',
	          headerToolbar: {
		        left: 'prev,next today',
		        center: 'title',
		        right: 'dayGridMonth,dayGridWeek,listWeek'
		      },
		      $eventsHTML,
		      eventTimeFormat: { // like '14:30:00'
			    hour: '2-digit',
			    minute: '2-digit',
			    meridiem: false,
			    hour12: false
			  },
			  firstDay: 1,
			  aspectRatio: 2.1	
	        });
	        calendar.render();
	      });
	    </script>
	    <div id='calendar'></div>";
	    
	    echo $html;
		
	}
	
	function hello_church_event_owner($eventID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEvents = new HelloChurch_Events($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchEvents->check_owner($Session->get('memberID'), $eventID);
		
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
	    
	}
	
	function hello_church_calendar_get($id, $field){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEvents = new HelloChurch_Events($API);
		
		$event = $HelloChurchEvents->find($id);
		return $event->$field();
		
	}