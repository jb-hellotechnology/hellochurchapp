<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

	session_start();

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
    
    
    include(__DIR__.'/fpdf.php');
    include(__DIR__.'/fpdf_html.php');

    PerchSystem::register_template_handler('HelloChurch_Template');
    
    include(__DIR__.'/rt_functions_Admin.php');
    include(__DIR__.'/rt_functions_Audio.php');
    include(__DIR__.'/rt_functions_Church.php');
    include(__DIR__.'/rt_functions_Contact_Note.php');
    include(__DIR__.'/rt_functions_Contact.php');
    include(__DIR__.'/rt_functions_Email.php');
    include(__DIR__.'/rt_functions_Event.php');
    include(__DIR__.'/rt_functions_Family.php');
    include(__DIR__.'/rt_functions_Folder.php');
    include(__DIR__.'/rt_functions_Group.php');
    include(__DIR__.'/rt_functions_Podcast.php');
    include(__DIR__.'/rt_functions_Role.php');
    include(__DIR__.'/rt_functions_Series.php');
    include(__DIR__.'/rt_functions_Speaker.php');
    include(__DIR__.'/rt_functions_Venue.php');
    
    // FORM HANDLERS

    function hello_church_form($template, $return=false)
    {
        $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchChurches = new HelloChurch_Churches($API);
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        $HelloChurchContactNotes = new HelloChurch_Contact_Notes($API);
        $HelloChurchGroups = new HelloChurch_Groups($API);
        $HelloChurchEvents = new HelloChurch_Events($API);
        $HelloChurchRoles = new HelloChurch_Roles($API);
        $HelloChurchVenues = new HelloChurch_Venues($API);
        $HelloChurchFamilies = new HelloChurch_Families($API);
        $HelloChurchFolders = new HelloChurch_Folders($API);
        $HelloChurchSpeakers = new HelloChurch_Speakers($API);
        $HelloChurchAdmins = new HelloChurch_Admins($API);
        $HelloChurchSeriess = new HelloChurch_Seriess($API);
        $HelloChurchAudios = new HelloChurch_Audios($API);
        $HelloChurchEmails = new HelloChurch_Emails($API);
        $HelloChurchPodcasts = new HelloChurch_Podcasts($API);
        
        $Template = $API->get('Template');
        $Template->set(PerchUtil::file_path('hellochurch/forms/'.$template), 'forms');
		
		$Session = PerchMembers_Session::fetch();
		
		$data['churchID'] = $Session->get('churchID');
		$data['memberID'] = $Session->get('memberID');
		
		if($template == 'create_church.html'){

			$data['memberID'] = $Session->get('memberID');
			$data['email'] = perch_member_get('email');
		
		}elseif($template == 'update_church.html'){

			$data = $HelloChurchChurches->church($Session->get('churchID'));
			
		}elseif($template == 'create_contact.html'){
			
			$families = $HelloChurchFamilies->families($Session->get('churchID'));
			$data['families'] = ' |0,';
			foreach($families as $family){
				$data['families'] .= $family['familyName']."|".$family['familyID'].",";	
			}
			$data['families'] = substr($data['families'], 0, -1);
			
		}elseif($template == 'update_contact.html'){
			
			$data = $HelloChurchContacts->contact($_GET['id']);
			$families = $HelloChurchFamilies->families($Session->get('churchID'));
			$data['families'] = ' |0,';
			foreach($families as $family){
				$data['families'] .= $family['familyName']."|".$family['familyID'].",";	
			}
			$data['families'] = substr($data['families'], 0, -1);
			
		}elseif($template == 'update_contact_public.html'){
			
			$data = $HelloChurchContacts->contact($_SESSION['hellochurch_active_contact']);
			
		}elseif($template == 'delete_contact.html'){
			
			$data = $HelloChurchContacts->contact($_GET['id']);
			
		}elseif($template == 'export_contact.html'){
			
			
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
			
		}elseif($template == 'create_role.html'){

			
		}elseif($template == 'update_role.html'){

			$data = $HelloChurchRoles->role($_GET['id']);
			
		}elseif($template == 'delete_role.html'){
			
			$data['roleID'] = $_GET['id'];
			
		}elseif($template == 'create_venue.html'){

			
		}elseif($template == 'update_venue.html'){

			$data = $HelloChurchVenues->venue($_GET['id']);
			
		}elseif($template == 'delete_venue.html'){
			
			$data['venueID'] = $_GET['id'];
			
		}elseif($template == 'create_family.html'){

			
		}elseif($template == 'update_family.html'){

			$data = $HelloChurchFamilies->family($_GET['id']);
			
		}elseif($template == 'delete_family.html'){
			
			$data['familyID'] = $_GET['id'];
			
		}elseif($template == 'download_rota_contact.html'){
			
			$data['contactID'] = $_GET['id'];
			
		}elseif($template == 'download_rota_role.html'){
	        
    	    $roles = $HelloChurchRoles->roles($data['churchID']);
    	    $pRoles = '';
			foreach($roles as $role){
				$pRoles .= $role['roleName'].'|'.$role['roleID'].',';
			}
			$pRoles = substr($pRoles,0,-1);
			$data['roles'] = $pRoles;
			
		}elseif($template == 'download_plan_pdf.html'){
	        
    	    $data['eventID'] = $_GET['id'];
    	    
    	    $start = hello_church_calendar_get($_GET['id'], 'start');
			$pTime = explode(" ", $start);
			$time = $pTime[1];
			$date = $pTime[0];
			$pDates = explode("-", $pTime[0]);
    	    
    	    $data['date'] = $date;
    	    $data['time'] = $time;
			
		}elseif($template == 'add_folder.html'){
			
			$data['folderParent'] = NULL;
			if($_GET['id']>0){
				$data['folderParent'] = $_GET['id'];
			}
			
		}elseif($template == 'update_folder.html'){

			$data = $HelloChurchFolders->folder($_GET['id']);
			$folders = $HelloChurchFolders->folders_exclude($data['churchID'],$_GET['id']);
			$data['folderParent'] = 'Documents|0,';
			foreach($folders as $folder){
				$data['folderParent'] .= $folder['folderName'].'|'.$folder['folderID'].',';
			}
			$data['folderParent'] = substr($data['folderParent'],0,-1);
			
		}elseif($template == 'delete_folder.html'){
			
			$data = $HelloChurchFolders->folder($_GET['id']);
			
		}elseif($template == 'update_file.html'){

			$data = $HelloChurchFolders->get_file($_GET['id']);
			
		}elseif($template == 'create_series.html'){

			
		}elseif($template == 'update_series.html'){

			$data = $HelloChurchSeriess->series($_GET['id']);
			
		}elseif($template == 'delete_series.html'){
			
			$data['seriesID'] = $_GET['id'];
			
		}elseif($template == 'create_speaker.html'){

			
		}elseif($template == 'update_speaker.html'){

			$data = $HelloChurchSpeakers->speaker($_GET['id']);
			
		}elseif($template == 'delete_speaker.html'){
			
			$data['speakerID'] = $_GET['id'];
			
		}elseif($template == 'create_admin.html'){

			
		}elseif($template == 'update_admin.html'){

			$data = $HelloChurchAdmins->admin($_GET['id']);
			
		}elseif($template == 'delete_admin.html'){
			
			$data['adminID'] = $_GET['id'];
			
		}elseif($template == 'update_email.html'){
			
			$data = $HelloChurchEmails->email($_GET['id']);
			
		}elseif($template == 'add_audio.html'){
			
			$speakers = $HelloChurchSpeakers->speakers($data['churchID']);
			$data['audioSpeaker'] = ' |0,';
			foreach($speakers as $speaker){
				$data['audioSpeaker'] .= $speaker['speakerName'].'|'.$speaker['speakerID'].',';
			}
			$data['audioSpeaker'] = substr($data['audioSpeaker'],0,-1);
			
			$seriess = $HelloChurchSeriess->seriess($data['churchID']);
			$data['audioSeries'] = ' |0,';
			foreach($seriess as $series){
				$data['audioSeries'] .= $series['seriesName'].'|'.$series['seriesID'].',';
			}
			$data['audioSeries'] = substr($data['audioSeries'],0,-1);
			
			$data['audioBible'] = ',Genesis,Exodus,Leviticus,Numbers,Deuteronomy,Joshua,Judges,Ruth,1 Samuel,2 Samuel,1 Kings,2 Kings,1 Chronicles,2 Chronicles,Ezra,Nehemiah,Esther,Job,Psalm,Proverbs,Ecclesiastes,Song of Solomon,Isaiah,Jeremiah,Lamentations,Ezekiel,Daniel,Hosea,Joel,Amos,Obadiah,Jonah,Micah,Nahum,Habakkuk,Zephaniah,Haggai,Zechariah,Malachi,Matthew,Mark,Luke,John,Acts,Romans,1 Corinthians,2 Corinthians,Galatians,Ephesians,Philippians,Colossians,1 Thessalonians,2 Thessalonians,1 Timothy,2 Timothy,Titus,Philemon,Hebrews,James,1 Peter,2 Peter,1 John,2 John,3 John,Jude,Revelation';
			
		}elseif($template == 'edit_audio.html'){

			$data = $HelloChurchAudios->audio($_GET['id']);
			
			$speakers = $HelloChurchSpeakers->speakers($data['churchID']);
			$data['audioSpeaker_options'] = ' |0,';
			foreach($speakers as $speaker){
				$data['audioSpeaker_options'] .= $speaker['speakerName'].'|'.$speaker['speakerID'].',';
			}
			$data['audioSpeaker_options'] = substr($data['audioSpeaker_options'],0,-1);
			
			$seriess = $HelloChurchSeriess->seriess($data['churchID']);
			$data['audioSeries_options'] = ' |0,';
			foreach($seriess as $series){
				$data['audioSeries_options'] .= $series['seriesName'].'|'.$series['seriesID'].',';
			}
			$data['audioSeries_options'] = substr($data['audioSeries_options'],0,-1);
			
			$data['audioBible_options'] = ',Genesis,Exodus,Leviticus,Numbers,Deuteronomy,Joshua,Judges,Ruth,1 Samuel,2 Samuel,1 Kings,2 Kings,1 Chronicles,2 Chronicles,Ezra,Nehemiah,Esther,Job,Psalm,Proverbs,Ecclesiastes,Song of Solomon,Isaiah,Jeremiah,Lamentations,Ezekiel,Daniel,Hosea,Joel,Amos,Obadiah,Jonah,Micah,Nahum,Habakkuk,Zephaniah,Haggai,Zechariah,Malachi,Matthew,Mark,Luke,John,Acts,Romans,1 Corinthians,2 Corinthians,Galatians,Ephesians,Philippians,Colossians,1 Thessalonians,2 Thessalonians,1 Timothy,2 Timothy,Titus,Philemon,Hebrews,James,1 Peter,2 Peter,1 John,2 John,3 John,Jude,Revelation';
			
			//print_r($data);
			
		}elseif($template == 'create_podcast.html'){

			
		}elseif($template == 'update_podcast.html'){

			$data = $HelloChurchPodcasts->podcast($data['churchID']);
			
		}elseif($template == 'switch_key.html'){

			$data['churchID'] = $_GET['id'];
			
		}
		
        $html = $Template->render($data);
        $html = $Template->apply_runtime_post_processing($html, $data);

        if ($return) return $html;
        echo $html;
    }

    function hello_church_form_handler($SubmittedForm) {
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    session_start();
	    
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
	    $HelloChurchRole = new HelloChurch_Role($API);
	    $HelloChurchRoles = new HelloChurch_Roles($API);
	    $HelloChurchVenues = new HelloChurch_Venues($API);
	    $HelloChurchFamily = new HelloChurch_Family($API);
	    $HelloChurchFamilies = new HelloChurch_Families($API);
	    $HelloChurchFolders = new HelloChurch_Folders($API);
	    $HelloChurchSpeaker = new HelloChurch_Speaker($API);
	    $HelloChurchSpeakers = new HelloChurch_Speakers($API);
	    $HelloChurchAdmin = new HelloChurch_Admin($API);
	    $HelloChurchAdmins = new HelloChurch_Admins($API);
		$HelloChurchSeries = new HelloChurch_Series($API);
        $HelloChurchSeriess = new HelloChurch_Seriess($API);
        $HelloChurchAudios = new HelloChurch_Audios($API);
        $HelloChurchEmails = new HelloChurch_Emails($API);
        $HelloChurchPodcasts = new HelloChurch_Podcasts($API);
        
	    $Session = PerchMembers_Session::fetch();
	    
	    require '../../../vendor/autoload.php';
		include('../../../secrets.php');

        switch($SubmittedForm->formID) {
            case 'create_church':
            	session_start();
            	unset($_SESSION['churchID']);
	            $valid = $HelloChurchChurches->church_valid($SubmittedForm->data);
	            if(!$valid){
		            //$SubmittedForm->throw_error($valid['reason'], $valid['field']);
	            }else{

		            $stripe = new \Stripe\StripeClient($stripeSK);
		            $stripeCustomer = $stripe->customers->create([
					  'name' => $data['churchName'],
					  'email' => perch_member_get('email'),
					]);
					
					$customer_id = $stripeCustomer->id;
					
		            $data = $SubmittedForm->data;
		            $data['churchSlug'] = strtolower(str_replace(" ", "-", $data['churchName']));
		            $data['churchSlug'] = $data['churchSlug'].'-'.rand();
		            $data['churchCustomerID'] = $customer_id;
		            $data['churchProperties'] = '';
	            	$church = $HelloChurchChurches->create($data);
	            	$PerchMembers_Auth->update_church_session($church->churchID());
	            	header("location:/dashboard");
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
            case 'update_contact_public':
	            $valid = $HelloChurchContacts->contact_valid($SubmittedForm->data);
	            if(!$valid){
		            //$SubmittedForm->throw_error($valid['reason'], $valid['field']);
	            }else{
		            $contact = $HelloChurchContacts->find($_SESSION['hellochurch_active_contact']);
		            $data = $SubmittedForm->data;
		            if(!$data['contactAcceptEmail']){
			            $data['contactAcceptEmail'] = '';
		            }
		            $contact->update($data);
	            } 
            break;
            case 'delete_contact':
		        $contact = $HelloChurchContacts->find($SubmittedForm->data['contactID']);
		        $contact->delete_tags($contact->id(), $data);
		        $contact->delete(); 
            break;
            case 'export_contact':
				$data = $HelloChurchContact->export($SubmittedForm->data['contactID']); 
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
				$HelloChurchGroups->remove_all_members($Session->get('churchID'), $SubmittedForm->data['groupID']);
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
            case 'create_role':
	            $data = $SubmittedForm->data;
		        $role = $HelloChurchRoles->create($data);
            break;
            case 'update_role':
	            $role = $HelloChurchRoles->find($SubmittedForm->data['roleID']);
		        $role->update($SubmittedForm->data);
            break;
            case 'delete_role':
	            $role = $HelloChurchRoles->find($SubmittedForm->data['roleID']);
		        $role->delete();
            break;
            case 'create_venue':
	            $data = $SubmittedForm->data;
		        $venue = $HelloChurchVenues->create($data);
            break;
            case 'update_venue':
	            $venue = $HelloChurchVenues->find($SubmittedForm->data['venueID']);
		        $venue->update($SubmittedForm->data);
            break;
            case 'delete_venue':
	            $venue = $HelloChurchVenues->find($SubmittedForm->data['venueID']);
		        $venue->delete();
            break;
            case 'create_family':
	            $data = $SubmittedForm->data;
		        $family = $HelloChurchFamilies->create($data);
            break;
            case 'update_family':
	            $family = $HelloChurchFamilies->find($SubmittedForm->data['familyID']);
		        $family->update($SubmittedForm->data);
            break;
            case 'delete_family':
	            $family = $HelloChurchFamilies->find($SubmittedForm->data['familyID']);
		        $family->delete();
            break;
            case 'download_rota_contact':
	            $contact = $HelloChurchContacts->find($SubmittedForm->data['contactID']);
	            $responsibilities = $HelloChurchEvents->event_responsibilities($SubmittedForm->data['contactID']);
				$firstName = $contact->contactFirstName();
				$lastName = $contact->contactLastName();
				
				$pdf = new PDF_HTML();
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',16);
				$pdf->Cell(40,10,'Rota For: '.$firstName.' '.$lastName,0,2);
				$pdf->SetFont('Arial','B',12);
				foreach($responsibilities as $responsibility){
			        $dates = explode(" ", $responsibility['eventDate']);
			        $time = $dates[1];
			        $dates = explode("-", $dates[0]);
			        $date = "$dates[2]/$dates[1]/$dates[0]";
			        $pdf->Cell(400,10,$responsibility['roleName'].' - '.$responsibility['eventName'].' - '.$date,0,2);
				}
				$pdf->Output();

            break;
            case 'download_rota_role':
	            $role = $HelloChurchRoles->find($SubmittedForm->data['roleID']);
	            $responsibilities = $HelloChurchEvents->event_responsibilities_role($SubmittedForm->data['roleID']);
				$roleName = $role->roleName();
				
				$pdf = new PDF_HTML();
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',16);
				$pdf->Cell(40,10,'Rota For: '.$roleName,0,2);
				$pdf->SetFont('Arial','B',12);
				foreach($responsibilities as $responsibility){
					$contact = $HelloChurchContacts->find($responsibility['contactID']);
			        $dates = explode(" ", $responsibility['eventDate']);
			        $time = $dates[1];
			        $dates = explode("-", $dates[0]);
			        $date = "$dates[2]/$dates[1]/$dates[0]";
			        if($responsibility['roleType']=='Individual'){
			        	$pdf->Cell(400,10,$contact->contactFirstName().' '.$contact->contactLastName().' - '.$responsibility['eventName'].' - '.$date,0,2);
			        }else{
				        $pdf->Cell(400,10,$contact->contactFirstName().' '.$contact->contactLastName().' + Family - '.$responsibility['eventName'].' - '.$date,0,2);
			        }
				}
				$pdf->Output();

            break;
            case 'download_plan_pdf':

	            $event = $HelloChurchEvents->event($SubmittedForm->data['eventID']);
	            $plan = $HelloChurchEvents->get_plan($SubmittedForm->data['memberID'], $SubmittedForm->data['churchID'], $SubmittedForm->data['eventID'], $SubmittedForm->data['date'], $SubmittedForm->data['time']);
	            
	            $dates = explode("-", $SubmittedForm->data['date']);
	            $date = "$dates[2]/$dates[1]/$dates[0]";
	            $time = $SubmittedForm->data['time'];
				
				$pdf = new PDF_HTML();
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',16);
				$pdf->Cell(40,10,$event['eventName'],0,2);
				$pdf->SetFont('Arial','B',12);
				$pdf->WriteHTML("<p>".$date." ".$time."<p><br><br>");
				$pdf->SetFont('Arial','B',12);
				
				$plan = json_decode($plan, true);

				foreach($plan as $type => $item){
									
					$typeParts = explode("_", $type);
					$type = $typeParts[0];
					
					if($type=='heading'){
						$pdf->SetFont('Arial','B',14);
						$pdf->WriteHTML("<p>".$item."<p><br><br>");
					}
					if($type=='text'){
						$pdf->SetFont('Arial','',12);
						$pdf->WriteHTML("<p>".nl2br($item)."<p><br><br>");
					}
					if($type=='youtube'){
						
					}
					if($type=='bible'){
						// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
						$ch = curl_init();
						
						curl_setopt($ch, CURLOPT_URL, 'https://api.esv.org/v3/passage/html/?include-footnotes=false&q='.urlencode($item));
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
						
						
						$headers = array();
						$headers[] = 'Authorization: Token 0aa221b3e0dbb4ca9d1abe0438ceac27e2b81cee';
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						
						$resultESV = curl_exec($ch);
						if (curl_errno($ch)) {
						    echo 'Error:' . curl_error($ch);
						}
						curl_close($ch);
						$json = json_decode($resultESV,true);

						$pdf->SetFont('Arial','',12);
						
						$passage = strip_tags($json['passages'][0]);
						$passage = str_replace("&nbsp;", " ", $passage);
						$passage = str_replace ('“', '"', $passage);
						$passage = str_replace ('”', '"', $passage);
						$passage = str_replace ('‘', "'", $passage);
						$passage = str_replace ('’', "'", $passage);
						$passage = str_replace ('–', "-", $passage);
						
						
						$pdf->WriteHTML($passage."<br><br>");
				
					}
					if($type=='link'){
						$pdf->SetFont('Arial','',12);
						
						if($typeParts[2]=='text'){
							$buttonText = $item;	
						}else{
							$pdf->WriteHTML("<p>[".$buttonText."] ".$item."<p><br><br>");
						}
								
						
					}
					
				}
				$pdf->Output();

            break;
            case 'add_folder':
	            $data = $SubmittedForm->data;
	            if($data['folderParent']==''){
		            $data['folderParent'] = 0;
	            }
		        $folder = $HelloChurchFolders->create($data);
            break;
            case 'update_folder':
	            $folder = $HelloChurchFolders->find($SubmittedForm->data['folderID']);
		        $folder->update($SubmittedForm->data);
            break;
            case 'delete_folder':
	            $folder = $HelloChurchFolders->find($SubmittedForm->data['folderID']);
		        $folder->delete();
            break;
            case 'update_file':
	            $HelloChurchFolders->update_file($SubmittedForm->data);
            break;
            case 'create_series':
	            $data = $SubmittedForm->data;
		        $series = $HelloChurchSeriess->create($data);
            break;
            case 'update_series':
	            $series = $HelloChurchSeriess->find($SubmittedForm->data['seriesID']);
		        $series->update($SubmittedForm->data);
            break;
            case 'delete_series':
	            $series = $HelloChurchSeriess->find($SubmittedForm->data['seriesID']);
		        $series->delete();
            break;
            case 'create_speaker':
	            $data = $SubmittedForm->data;
		        $speaker = $HelloChurchSpeakers->create($data);
            break;
            case 'update_speaker':
	            $speaker = $HelloChurchSpeakers->find($SubmittedForm->data['speakerID']);
		        $speaker->update($SubmittedForm->data);
            break;
            case 'delete_speaker':
	            $speaker = $HelloChurchSpeakers->find($SubmittedForm->data['speakerID']);
		        $speaker->delete();
            break;
            case 'create_admin':
	            $data = $SubmittedForm->data;
	            $chars = "abcdefghijkmnopqrstuvwxyz0123456789"; 
			    srand((double)microtime()*1000000); 
			    $i = 0; 
			    $pass = '' ; 
			
			    while ($i <= 11) { 
			        $num = rand() % 33; 
			        $tmp = substr($chars, $num, 1); 
			        $pass = $pass . $tmp; 
			        $i++; 
			    }
			    $data['adminCode'] = $pass;
		        $admin = $HelloChurchAdmins->create($data);
            break;
            case 'update_admin':
	            $admin = $HelloChurchAdmins->find($SubmittedForm->data['adminID']);
		        $admin->update($SubmittedForm->data);
            break;
            case 'delete_admin':
	            $admin = $HelloChurchAdmins->find($SubmittedForm->data['adminID']);
	            // DELETE SESSIONS - JUST IN CASE!
		        $admin->delete();
            break;
            case 'update_audio':
	            $audio = $HelloChurchAudios->find($SubmittedForm->data['audioID']);
	            $audio->update($SubmittedForm->data);
            break;
            case 'add_email':
	            $data = $SubmittedForm->data;
		        $email = $HelloChurchEmails->create($data);
            break;
            case 'update_email':
	            $email = $HelloChurchEmails->find($SubmittedForm->data['emailID']);
	            $email->update($SubmittedForm->data);
            break;
            case 'create_podcast':
	            $data = $SubmittedForm->data;
		        $podcast = $HelloChurchPodcasts->create($data);
            break;
            case 'update_podcast':
	            $podcast = $HelloChurchPodcasts->find($SubmittedForm->data['podcastID']);
		        $podcast->update($SubmittedForm->data);
            break;
            case 'switch_key':
	            $admin = $HelloChurchAdmins->confirm($SubmittedForm->data['key'],$SubmittedForm->data['churchID'],$Session->get('memberID'));
		        if($admin){
			        // SET CHURCH ID SESSION AND REDIRECT
			        $PerchMembers_Auth->update_church_session($SubmittedForm->data['churchID']);
			        //perch_members_refresh_session_data();
			        //header("location:/dashboard");
		        }else{
			        $SubmittedForm->throw_error('required', 'key');
		        }
            break;
        }
    	
    	// access logged errors
	    $Perch = Perch::fetch();
	    $form_errors = $Perch->get_form_errors($SubmittedForm->formID);
    }    