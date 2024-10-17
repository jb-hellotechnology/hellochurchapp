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

    PerchSystem::register_template_handler('HelloChurch_Template');

	function hello_church_church(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$church = $HelloChurchChurches->church($Session->get('memberID'));
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
	
	function hello_church_contacts($tag, $q, $page){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$church = $HelloChurchChurches->church($Session->get('memberID'));
		
		$html = '';
		
		if($church){
			
			$totalContacts = $HelloChurchContacts->totalContacts($Session->get('memberID'), $church['churchID'], $tag, $q);
			$pages = ceil($totalContacts/25);
			$contacts = $HelloChurchContacts->contacts($Session->get('memberID'), $church['churchID'], $tag, $q, $page);
			
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
		
		$church = $HelloChurchChurches->church($Session->get('memberID'));
		
		$html = '';
		
		if($church){
			
			$contacts = $HelloChurchContacts->recent_contacts($Session->get('memberID'), $church['churchID']);
			
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
        
        $Template = $API->get('Template');
        $Template->set(PerchUtil::file_path('hellochurch/forms/'.$template), 'forms');
		
		$Session = PerchMembers_Session::fetch();
		
		if($template == 'church.html'){

			$data['memberID'] = $Session->get('memberID');
			$data['email'] = perch_member_get('email');
		
		}elseif($template == 'update_church.html'){
			
			$data = $HelloChurchChurches->church($Session->get('memberID'));
			
		}elseif($template == 'create_contact.html'){
			
			$data = $HelloChurchChurches->church($Session->get('memberID'));
			
		}elseif($template == 'update_contact.html'){
			
			$data = $HelloChurchContacts->contact($_GET['id']);
			
		}elseif($template == 'delete_contact.html'){
			
			$data = $HelloChurchContacts->contact($_GET['id']);
			
		}elseif($template == 'export_contacts.html'){
			
			
		}elseif($template == 'add_note.html'){
			
			$data = $HelloChurchChurches->church($Session->get('memberID'));
			$data['contactID'] = $_GET['id'];
			
		}elseif($template == 'update_note.html'){
	
			$data = $HelloChurchContactNotes->by_noteID($_GET['noteID']);
			
		}elseif($template == 'delete_note.html'){
	
			$data['noteID'] = $_GET['noteID'];
			$data['id'] = $_GET['id'];
			
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
		
		$church = $HelloChurchChurches->church($Session->get('memberID'));
		$HelloChurchContacts->tag_options($church['churchID'], $tag);
	    
    }
    
    function process_delete_contacts($data){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$contacts = explode(",", $data);
		
		foreach($data as $contact){
			$owner = $HelloChurchContacts->check_owner($Session->get('memberID'), $contactID);
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
    
    function hello_church_form_handler($SubmittedForm) {
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $HelloChurchChurch = new HelloChurch_Church($API);
	    $HelloChurchChurches = new HelloChurch_Churches($API); 
	    $HelloChurchContact = new HelloChurch_Contact($API);
	    $HelloChurchContacts = new HelloChurch_Contacts($API); 
	    $HelloChurchContactNote = new HelloChurch_Contact_Note($API);
	    $HelloChurchContactNotes = new HelloChurch_Contact_Notes($API); 
	    
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
	            } 
            break;
            case 'delete_contact':
		        $contact = $HelloChurchContacts->find($SubmittedForm->data['contactID']);
		        $contact->delete_tags($contact->id(), $data);
		        $contact->delete(); 
            break;
            case 'export_contacts':
		        $church = $HelloChurchChurches->church($Session->get('memberID'));
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
						
						$church = $HelloChurchChurches->church($Session->get('memberID'));

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
        }
    	
    	// access logged errors
	    $Perch = Perch::fetch();
	    $form_errors = $Perch->get_form_errors($SubmittedForm->formID);
    }
