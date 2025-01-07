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
    
    class PDF_HTML extends FPDF
	{
		protected $B = 0;
		protected $I = 0;
		protected $U = 0;
		protected $HREF = '';
		
		function WriteHTML($html)
		{
		    // HTML parser
		    $html = str_replace("\n",' ',$html);
		    $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		    foreach($a as $i=>$e)
		    {
		        if($i%2==0)
		        {
		            // Text
		            if($this->HREF)
		                $this->PutLink($this->HREF,$e);
		            else
		                $this->Write(5,$e);
		        }
		        else
		        {
		            // Tag
		            if($e[0]=='/')
		                $this->CloseTag(strtoupper(substr($e,1)));
		            else
		            {
		                // Extract attributes
		                $a2 = explode(' ',$e);
		                $tag = strtoupper(array_shift($a2));
		                $attr = array();
		                foreach($a2 as $v)
		                {
		                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
		                        $attr[strtoupper($a3[1])] = $a3[2];
		                }
		                $this->OpenTag($tag,$attr);
		            }
		        }
		    }
		}
		
		function OpenTag($tag, $attr)
		{
		    // Opening tag
		    if($tag=='B' || $tag=='I' || $tag=='U')
		        $this->SetStyle($tag,true);
		    if($tag=='A')
		        $this->HREF = $attr['HREF'];
		    if($tag=='BR')
		        $this->Ln(5);
		}
		
		function CloseTag($tag)
		{
		    // Closing tag
		    if($tag=='B' || $tag=='I' || $tag=='U')
		        $this->SetStyle($tag,false);
		    if($tag=='A')
		        $this->HREF = '';
		}
		
		function SetStyle($tag, $enable)
		{
		    // Modify style and select corresponding font
		    $this->$tag += ($enable ? 1 : -1);
		    $style = '';
		    foreach(array('B', 'I', 'U') as $s)
		    {
		        if($this->$s>0)
		            $style .= $s;
		    }
		    $this->SetFont('',$style);
		}
		
		function PutLink($URL, $txt)
		{
		    // Put a hyperlink
		    $this->SetTextColor(0,0,255);
		    $this->SetStyle('U',true);
		    $this->Write(5,$txt,$URL);
		    $this->SetStyle('U',false);
		    $this->SetTextColor(0);
		}
	}

    PerchSystem::register_template_handler('HelloChurch_Template');

	function hello_church_church_public($churchID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$church = $HelloChurchChurches->church($churchID);
		
		return $church;
		
	}
	
	function church_by_slug($slug){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$church = $HelloChurchChurches->church_by_slug($slug);
		
		return $church;
		
	}
	
	function hello_church_church($return){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$church = $HelloChurchChurches->church($Session->get('churchID'));
		
		if($return){
			return $church;
		}else{
			if($church){
				return true;
			}else{
				return false;
			}
		}
		
	}
	
	function hello_church_member_owner($contactID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchContacts->check_owner($Session->get('churchID'), $contactID);
		
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
		
		$owner = $HelloChurchContacts->check_owner($Session->get('churchID'), $noteID);
		
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
			$owner = $HelloChurchContacts->check_owner($Session->get('churchID'), $contact);
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
			$owner = $HelloChurchContacts->check_owner($Session->get('churchID'), $contactID);
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
		
		$family = $HelloChurchContacts->family_members($contactID);
		
		if(count($family)>0){
		
			$html = '<ul class="cards">';
			
			foreach($family as $member){
				$html .= '
				<li class="flow">
					<span class="material-symbols-outlined">person</span>
					<h3><a href="/contacts/edit-contact?id='.$member['contactID'].'">'.$member['contactFirstName'].' '.$member['contactLastName'].'</a></h3>
					<p><a class="button secondary small" href="/contacts/edit-contact?id='.$member['contactID'].'">View</a></p>
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
		
		$contacts = $HelloChurchContacts->search_family_members($Session->get('churchID'), $q, $memberID);
		
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
    
    function process_remove_family_member($contactID_a, $contactID_b){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchContacts = new HelloChurch_Contacts($API);

	    $HelloChurchContacts->remove_family_member($contactID_a, $contactID_b);
	    
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
		            $data['churchCustomerID'] = $customer_id;
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
						$pdf->WriteHTML("<p>".$item."<p><br><br>");
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
            case 'update_audio':
	            $audio = $HelloChurchAudios->find($SubmittedForm->data['audioID']);
	            $audio->update($SubmittedForm->data);
            break;
            case 'add_email':
	            $data = $SubmittedForm->data;
		        $email = $HelloChurchEmails->create($data);
            break;
            case 'create_podcast':
	            $data = $SubmittedForm->data;
		        $podcast = $HelloChurchPodcasts->create($data);
            break;
            case 'update_podcast':
	            $podcast = $HelloChurchPodcasts->find($SubmittedForm->data['podcastID']);
		        $podcast->update($SubmittedForm->data);
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
		
		$owner = $HelloChurchGroups->check_owner($Session->get('churchID'), $groupID);
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
		      url: "/calendar/edit-event?id='.$event['eventID'].'&date=",
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
		        right: 'listWeek'
		      },
		      $eventsHTML,
		      eventTimeFormat: { // like '14:30:00'
			    hour: '2-digit',
			    minute: '2-digit',
			    meridiem: false,
			    hour12: false
			  },
			  firstDay: 1,
			  aspectRatio: 2.1,
			  eventClick: function(info) {
				info.jsEvent.preventDefault(); // don't let the browser navigate
				console.log(info);
				var eventDate = info.event.start;
				var pDate = dateToDMY(eventDate);
			    if (info.event.url) {
			      window.open(info.event.url+pDate, '_self');
			    }	  
			  }
	        });
	        calendar.render();
	      });
	      function dateToDMY(date) {
		    var d = date.getDate();
		    var m = date.getMonth() + 1; //Month from 0 to 11
		    var y = date.getFullYear();
		    return '' + y + '-' + (m <= 9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
		  }
	    </script>
	    <div id='calendar'></div>";
	    
	    echo $html;
		
	}
	
	function process_save_plan($planID, $date, $time, $plan){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchEvents = new HelloChurch_Events($API);
        
		$Session = PerchMembers_Session::fetch();
		$memberID = $Session->get('memberID');
		$churchID = $Session->get('churchID');
		
		$plan = $HelloChurchEvents->save_plan($memberID, $churchID, $planID, $date, $time, $plan);
		
		return $plan;
	    
    }
    
    function hello_church_get_plan($eventID, $date, $time){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchEvents = new HelloChurch_Events($API);
        
		$Session = PerchMembers_Session::fetch();
		$memberID = $Session->get('memberID');
		$churchID = $Session->get('churchID');
		
		$plan = $HelloChurchEvents->get_plan($memberID, $churchID, $eventID, $date, $time);
		
		return $plan;
		
    }
    
    function process_save_email($emailID, $plan){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchEmails = new HelloChurch_Emails($API);
        
		$Session = PerchMembers_Session::fetch();
		$memberID = $Session->get('memberID');
		$churchID = $Session->get('churchID');
		
		$email = $HelloChurchEmails->save_email($memberID, $churchID, $emailID, $plan);
		
		return $email;
	    
    }
	
	function hello_church_event_owner($eventID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEvents = new HelloChurch_Events($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchEvents->check_owner($Session->get('churchID'), $eventID);
		
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
	
	function hello_church_venues(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchVenues = new HelloChurch_Venues($API);
        
        $venues = $HelloChurchVenues->venues($churchID);
        
		echo '<article>
				<ul class="list">';
        
        foreach($venues as $venue){
	        $description = strip_tags($venue['venueDescription']);
	        echo '<li>
			        <h3>'.$venue['venueName'].'</h3>
					<p>'.$description.'</p>
					<a href="/settings/venues/edit-venue?id='.$venue['venueID'].'" class="button secondary small">View</a>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
	
	function hello_church_roles(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchRoles = new HelloChurch_Roles($API);
        
        $roles = $HelloChurchRoles->roles($churchID);
        
		echo '<article>
				<ul class="list">';
        
        foreach($roles as $role){
	        $description = strip_tags($role['roleDescription']);
	        echo '<li>
			        <h3>'.$role['roleName'].'</h3>
					<p>'.$description.'</p>
					<a href="/settings/roles/edit-role?id='.$role['roleID'].'" class="button secondary small">View</a>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    function hello_church_venues_tagify(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchVenues = new HelloChurch_Venues($API);
        
        $venues = $HelloChurchVenues->venues($churchID);
        
		$html = '[';
        
        foreach($venues as $venue){
	        $html .=  "'".$venue['venueName']."', ";
        }
        
        if(strlen($html)>1){
        	$html = substr($html, 0 , -2);
        }

        $html .= ']';
        
        return $html;
	    
    }
    
    function hello_church_roles_tagify(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchRoles = new HelloChurch_Roles($API);
        
        $roles = $HelloChurchRoles->roles($churchID);
        
		$html = '[';
        
        foreach($roles as $role){
	        $html .=  "'".$role['roleName']."', ";
        }
        
        if(strlen($html)>1){
        	$html = substr($html, 0 , -2);
        }

        $html .= ']';
        
        return $html;
	    
    }
    
    function hello_church_venue_owner($venueID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchVenues = new HelloChurch_Venues($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchVenues->check_owner($Session->get('churchID'), $venueID);
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
    
    function hello_church_role_owner($roleID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchRoles = new HelloChurch_Roles($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchRoles->check_owner($Session->get('churchID'), $roleID);
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	function hello_church_contact_responsibilities($id){
		
		$API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchEvents = new HelloChurch_Events($API);
        $HelloChurchRoles = new HelloChurch_Roles($API);
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
        $responsibilities = $HelloChurchEvents->event_responsibilities($id);
        
        $html .= '<article><ul class="list">';
        
        foreach($responsibilities as $responsibility){
	        $dates = explode(" ", $responsibility['eventDate']);
	        $time = $dates[1];
	        $dates = explode("-", $dates[0]);
	        $date = "$dates[2]/$dates[1]/$dates[0]";
	        $html .= '<li><h3>'.$responsibility['roleName'].'</h3><p>'.$responsibility['eventName'].'</p><p>'.$date.'</p></li>';
        }
        
        $html .= '</ul></article>';
        
        echo $html;
		
	}
	
	function hello_church_event_roles($id){
		
		$API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

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
							<h3><a href="/contacts/edit-contact?id='.$contactData['contactID'].'">'.$contactData['contactFirstName']." ".$contactData['contactLastName'];
							if($roleData['roleType']=='Family'){
								$html .= ' &plus; Family';
							}
					$html .= '</a></h3>
							<p></p>
							<form action="/process/remove-role-contact" method="post">
								<input type="submit" class="button border danger small" value="Remove" />
								<input type="hidden" name="roleContactID" value="'.$contact['roleContactID'].'" />
								<input type="hidden" name="eventID" value="'.perch_get('id').'" />
								<input type="hidden" name="date" value="'.perch_get('date').'" />
							</form>
						</li>';
				}

	        $html .= '</ul>
	        	</article>
	        </section>';
	        $i++;
	        echo $html;
        }
        
	}
	
	function process_search_role_members($q, $eventID, $eventDate, $roleID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$contacts = $HelloChurchContacts->search_role_members($Session->get('memberID'), $q, $eventID, $eventDate);
		
		foreach($contacts as $contact){
			echo '
			<form method="post" action="/process/add-role-contact">
				<input type="hidden" name="eventID" value="'.$eventID.'" />
				<input type="hidden" name="eventDate" value="'.$eventDate.'" />
				<input type="hidden" name="contactID" value="'.$contact['contactID'].'" />
				<input type="hidden" name="roleID" value="'.$roleID.'" />
				<button class="button primary small" value="Add Member">'.$contact['contactFirstName'].' '.$contact['contactLastName'].'<span class="material-symbols-outlined">person_add</span></button>
			</form>';
		}
	    
    }
    
    function process_add_role_contact($eventID, $eventDate, $contactID, $roleID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchEvents = new HelloChurch_Events($API);
        
        $Session = PerchMembers_Session::fetch();

	    $HelloChurchEvents->add_role_contact($Session->get('memberID'), $Session->get('churchID'), $eventID, $eventDate, $contactID, $roleID);
	    
    }
    
    function process_remove_role_contact($eventID, $eventDate, $roleContactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchEvents = new HelloChurch_Events($API);
        
        $Session = PerchMembers_Session::fetch();
        
        echo $roleContactID;

	    $HelloChurchEvents->remove_role_contact($roleContactID);
	    
    }
    
    function hello_church_families(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchFamilies = new HelloChurch_Families($API);
        
        $families = $HelloChurchFamilies->families($churchID);
        
		echo '<article>
				<ul class="list">';
        
        foreach($families as $family){
	        $description = strip_tags($family['familyDescription']);
	        echo '<li>
			        <h3>'.$family['familyName'].'</h3>
					<p>'.$description.'</p>
					<a href="/settings/families/edit-family?id='.$family['familyID'].'" class="button secondary small">View</a>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    function hello_church_families_tagify(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchFamilies = new HelloChurch_Families($API);
        
        $families = $HelloChurchFamiles->families($churchID);
        
		$html = '[';
        
        foreach($families as $family){
	        $html .=  "'".$family['familyName']."', ";
        }
        
        $html = substr($html, 0 , -2);

        $html .= ']';
        
        return $html;
	    
    }
    
    function hello_church_family_owner($familyID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFamilies = new HelloChurch_Families($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchFamilies->check_owner($Session->get('churchID'), $familyID);
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	function hello_church_folder_structure(){
	
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
	
		$folderString = " |0,";
		
		$folders = $HelloChurchFolders->folders($Session->get('churchID'), $folderParent);
		
		foreach($folders as $folder){
			$folderString .= "- $folder[folderName]|$folder[folderID],";
			
			$subFolders = $HelloChurchFolders->folders($Session->get('churchID'), $folder['folderID']);
			foreach($subFolders as $subFolder){
				$folderString .= "-- $subFolder[folderName]|$subFolder[folderID],";
				$subFolders_2 = $HelloChurchFolders->folders($Session->get('churchID'), $subFolder['folderID']);	
				foreach($subFolders_2 as $subFolder_2){	
					$folderString .= "-- $subFolder_2[folderName]|$subFolder_2[folderID],";
				}
			}
		}
		
		return substr($folderString,0,-1);
		
	}
	
	function hello_church_folders($folderParent){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$folders = $HelloChurchFolders->folders($Session->get('churchID'), $folderParent);
		
		$html = '<ul class="folders">';
		
		foreach($folders as $folder){
			$html .= '<li><a href="/documents?id='.$folder['folderID'].'"><span class="material-symbols-outlined">folder</span><h3>'.$folder['folderName'].'</h3></a></li>';
		}
		
		$html .= '</ul>';
		
		echo $html;
		
	}
	
	function hello_church_folder($folderID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$folder = $HelloChurchFolders->folder($folderID);
		
		return $folder;
		
	}
	
	function hello_church_folder_owner($folderID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchFolders->check_owner($Session->get('churchID'), $folderID);
		
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	function folder_has_children($folderID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$folders = $HelloChurchFolders->folders($Session->get('churchID'), $folderID);	
		
		if(count($folders)>0){
			return true;
		}else{
			return false;
		}
		
	}
	
	function process_file_upload($folderID, $contactID, $groupID, $eventID, $eventDate, $fileName){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchFolders = new HelloChurch_Folders($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$HelloChurchFolders->add_file($Session->get('churchID'), $Session->get('memberID'), $folderID, $contactID, $groupID, $eventID, $eventDate, $fileName);
	    
    }
    
    function process_delete_file($fileID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchFolders = new HelloChurch_Folders($API);
        
        $file = $HelloChurchFolders->get_file($fileID);
        $fileName = explode("/", $file['fileLocation']);
        
        unlink("../../../../hc_uploads/".$file['churchID']."/".$file['fileLocation']);
        rmdir("../../../../hc_uploads/".$file['churchID']."/".$fileName[0]);
		
		$HelloChurchFolders->delete_file($fileID);
	    
    }
    
    function process_download_file($fileID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchFolders = new HelloChurch_Folders($API);
        
        $file = $HelloChurchFolders->get_file($fileID);
        
        $fileLocation = "../../../../hc_uploads/".$file['churchID']."/".$file['fileLocation']; 
		
		if (file_exists($fileLocation))
		{
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.basename($fileLocation));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($fileLocation));
		    ob_clean();
		    flush();
		    readfile($fileLocation);
		    exit;
		}
		else
		{
		    echo "File does not exist";
		}
	    
    }
    
    function hello_church_files($folderParent, $contactParent, $groupParent, $eventParent, $eventDate){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);

		$HelloChurchContacts = new HelloChurch_Contacts($API);
		$HelloChurchGroups = new HelloChurch_Groups($API);
		$HelloChurchEvents = new HelloChurch_Events($API);
		
		$Session = PerchMembers_Session::fetch();
		
		if($contactParent>0){
			$files = $HelloChurchFolders->files($Session->get('churchID'), 0, $contactParent, 0, 0, 0);
		}elseif($groupParent>0){
			$files = $HelloChurchFolders->files($Session->get('churchID'), 0, 0, $groupParent, 0, 0);
		}elseif($eventParent>0){
			$files = $HelloChurchFolders->files($Session->get('churchID'), 0, 0, 0, $eventParent, $eventDate);
		}else{
			$files = $HelloChurchFolders->files($Session->get('churchID'), $folderParent, 0, 0, $eventParent, $eventDate);
		}
		
		$html = '<p class="section-heading">Files:</p>';
		
		if($files){
			$html .= '<ul class="list files">';
			
			foreach($files as $file){
				$documentFileType = strtolower(pathinfo($file['fileName'],PATHINFO_EXTENSION));
				if($documentFileType=='doc' OR $documentFileType=='docx' OR $documentFileType=='pages'){
					$icon = 'description';
				}elseif($documentFileType=='xls' OR $documentFileType=='xlsx' OR $documentFileType=='numbers'){
					$icon = 'table';
				}elseif($documentFileType=='ppt' OR $documentFileType=='pptx' OR $documentFileType=='key'){
					$icon = 'slideshow';
				}elseif($documentFileType=='csv'){
					$icon = 'csv';
				}elseif($documentFileType=='txt'){
					$icon = 'article';
				}elseif($documentFileType=='pdf'){
					$icon = 'picture_as_pdf';
				}else{
					$icon = 'draft';
				}
				
				$html .= '<li>
							<div>
								<span class="material-symbols-outlined">'.$icon.'</span>
								<p><a href="/documents/edit-file?id='.$file['fileID'].'">'.$file['fileName'].'</a></p>';
								if($file['contactID']){
									$contact = $HelloChurchContacts->contact($file['contactID']);
									$html .= '<small><a href="/contacts/edit-contact?id='.$file['contactID'].'">'.$contact['contactFirstName'].' '.$contact['contactLastName'].'</a></small>';
								}elseif($file['groupID']){
									$group = $HelloChurchGroups->group($file['groupID']);
									$html .= '<small><a href="/groups/edit-group?id='.$file['groupID'].'">'.$group['groupName'].'</a></small>';
								}elseif($file['eventID']){
									$event = $HelloChurchEvents->event($file['eventID']);
									$dateParts = explode("-", $file['eventDate']);
									$date = "$dateParts[2]/$dateParts[1]/$dateParts[0]";
									$html .= '<small><a href="/calendar/edit-event?id='.$event['editID'].'&date='.$event['eventDate'].'">'.$event['eventName'].' &bull; '.$date.'</a></small>';
								}
							$html .= '
							</div>
							<form method="post" action="/process/download-file">
								<input type="submit" class="button secondary small" value="Download" />
								<input type="hidden" name="id" value="'.$folderParent.'" />
								<input type="hidden" name="fileID" value="'.$file['fileID'].'" />
							</form>
							<form method="post" action="/process/delete-file">
								<button type="submit" class="button danger small border"><span class="material-symbols-outlined">delete</span></button>
								<input type="hidden" name="id" value="'.$folderParent.'" />
								<input type="hidden" name="fileID" value="'.$file['fileID'].'" />
							</form>
						</li>';
			}
			
			$html .= '</ul>';
		}else{
			$html .= "<p>No files uploaded.</p>";
		}
		
		echo $html;
		
	}
	
	function hello_church_file_owner($fileID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchFolders->check_file_owner($Session->get('churchID'), $fileID);
		
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	function hello_church_audio_owner($audioID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchAudios = new HelloChurch_Audios($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchAudios->check_audio_owner($Session->get('churchID'), $audioID);
		
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	function hello_church_file($fileID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$file = $HelloChurchFolders->get_file($fileID);
		
		return $file;
		
	}
	
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
			        <h3>'.$series['seriesName'].'</h3>
					<p>'.$description.'</p>
					<a href="/settings/series/edit-series?id='.$series['seriesID'].'" class="button secondary small">View</a>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    function hello_church_speakers(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchSpeakers = new HelloChurch_Speakers($API);
        
        $speakers = $HelloChurchSpeakers->speakers($churchID);
        
		echo '<article>
				<ul class="list">';
        
        foreach($speakers as $speaker){
	        echo '<li>
			        <h3>'.$speaker['speakerName'].'</h3>
					<p></p>
					<a href="/settings/speakers/edit-speaker?id='.$speaker['speakerID'].'" class="button secondary small">View</a>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    function hello_church_speaker_owner($speakerID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchSpeakers = new HelloChurch_Speakers($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchSpeakers->check_owner($Session->get('churchID'), $speakerID);
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	function hello_church_series_owner($seriesID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchSeriess = new HelloChurch_Seriess($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchSeriess->check_owner($Session->get('churchID'), $seriesID);
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	function process_audio_upload($audioName, $audioDate, $audioDescription, $audioSpeaker, $audioSeries, $audioBible, $audioFile){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        $HelloChurchAudios = new HelloChurch_Audios($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$HelloChurchAudios->add_audio($Session->get('memberID'), $Session->get('churchID'), $audioName, $audioDate, $audioDescription, $audioSpeaker, $audioSeries, $audioBible, $audioFile);
	    
    }
    
    function hello_church_audio(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchAudios = new HelloChurch_Audios($API);
		$HelloChurchSeriess = new HelloChurch_Seriess($API);
		$HelloChurchSpeakers = new HelloChurch_Speakers($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$files = $HelloChurchAudios->audios($Session->get('churchID'));
		
		if($files){
			$html .= '<ul class="list files">';
			
			foreach($files as $file){
				$series = $HelloChurchSeriess->series($file['audioSeries']);
				$speaker = $HelloChurchSpeakers->speaker($file['audioSpeaker']);
				$html .= '<li>
							<div>
								<span class="material-symbols-outlined">record_voice_over</span>
								<p><a href="/media/edit-audio?id='.$file['audioID'].'">'.$file['audioName'].'</a></p>
							</div>
							<form method="post" action="/process/download-audio">
								<input type="submit" class="button secondary small" value="Download" />
								<input type="hidden" name="audioID" value="'.$file['audioID'].'" />
							</form>
							<form method="post" action="/process/delete-audio">
								<button type="submit" class="button danger small border"><span class="material-symbols-outlined">delete</span></button>
								<input type="hidden" name="fileID" value="'.$file['audioID'].'" />
							</form>
						</li>';
			}
			
			$html .= '</ul>';
		}else{
			$html .= "<p>No files uploaded.</p>";
		}
		
		echo $html;
		
	}
	
	function hello_church_audio_file($audioID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchAudios = new HelloChurch_Audios($API);
		
		$file = $HelloChurchAudios->audio($audioID);
        
        $fileName = "../../../../hc_uploads/".$file['churchID']."/".$file['audioFileLocation']."/".$file['audioFileName'];
        
        header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename='.basename($fileName));
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($fileName));
	    ob_clean();
	    flush();
	    readfile($fileName);
		
	}
	
	function process_delete_audio($audioID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchAudios = new HelloChurch_Audios($API);
        
        $file = $HelloChurchAudios->audio($audioID);
        
        unlink("../../../../hc_uploads/".$file['churchID']."/".$file['audioFileLocation']."/".$file['audioFileName']);
		
		$HelloChurchAudios->delete_audio($audioID);
	    
    }
    
    function process_download_audio($fileID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchAudios = new HelloChurch_Audios($API);
        
        $file = $HelloChurchAudios->audio($fileID);
        
        $fileName = "../../../../hc_uploads/".$file['churchID']."/".$file['audioFileLocation']."/".$file['audioFileName']; 
		
		if (file_exists($fileName))
		{
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.basename($fileName));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($fileName));
		    ob_clean();
		    flush();
		    readfile($fileName);
		    exit;
		}
		else
		{
		    echo "File does not exists";
		}
	    
    }
    
    function hello_church_email(){
	    
	 	$API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = $Session->get('churchID');

        $HelloChurchEmails = new HelloChurch_Emails($API);

        $emails = $HelloChurchEmails->emails($churchID);

		echo '<article>
				<ul class="list files">';
        
        foreach($emails as $email){
	        echo '<li>
	        		<div>
		        		<span class="material-symbols-outlined">mail</span>
				        <h3><a href="/communication/edit-email?id='.$email['emailID'].'">'.$email['emailSubject'].'</a></h3>
			        </div>
					<p>'.$email['emailStatus'].'</p>
					<form><a href="/process/delete-email?id='.$email['emailID'].'" class="button danger small border"><span class="material-symbols-outlined">delete</span></a></form>
				</li>';
        }

        echo '</ul>
        	</article>';   
	    
    }
    
    function hello_church_email_owner($emailID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEmails = new HelloChurch_Emails($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchEmails->check_owner($Session->get('churchID'), $emailID);
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	function hello_church_get_email($emailID){
	
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEmails = new HelloChurch_Emails($API);
		
		$email = $HelloChurchEmails->email($emailID);
		
		return $email;
		
	}
	
	/** SUBSCRIPTION FUNCTIONS **/
	
	function church_subscription_period(){
	    $API  = new PerchAPI(1.0, 'hello_church');
		$Session = PerchMembers_Session::fetch();
		$Churches = new HelloChurch_Churches($API);
		return $Churches->current_period_end($Session->get('churchID'));
    }
    
    function stripe_data($field){
	    $API  = new PerchAPI(1.0, 'hello_church');
		$Session = PerchMembers_Session::fetch();
		$Churches = new HelloChurch_Churches($API);
		return $Churches->get_stripe_data($Session->get('churchID'), $field);
    }

	function church_update_stripe_id($reference, $church_id){
		$API  = new PerchAPI(1.0, 'hello_church');
		$Session = PerchMembers_Session::fetch();
		$Churches = new HelloChurch_Churches($API);
		$Church = new HelloChurch_Church($API);

		if(is_object($Churches)){
			$Church = $Churches->find_by_reference($reference);
			$Church = $Churches->find($Church[0]['churchID']);
        	if(is_object($Church)) {
				$Church->update_stripe_id($church_id);
				$PerchMembers_Auth = new PerchMembers_Auth($API);
                $PerchMembers_Auth->refresh_session_data($Member);
			}
		}
	}
	
	function church_update_subscription_id($id){
		$API  = new PerchAPI(1.0, 'hello_church');
		$Session = PerchMembers_Session::fetch();
		$Churches = new HelloChurch_Churches($API);
		$Church = new HelloChurch_Church($API);

		if(is_object($Churches)){
			$Church = $Churches->find($Session->get('churchID'));
        	if(is_object($Church)) {
				$Church->update_subscription_id($Session->get('churchID'), $id);
				$PerchMembers_Auth = new PerchMembers_Auth($API);
                $PerchMembers_Auth->refresh_session_data($Member);
			}
		}
	}
	
	function church_update_subscription_details(
	    $customer_id,
	    $subscription_id,
	    $payment_method,
	    $current_period_end,
	    $cancel,
	    $plan_id,
	    $cost
    ){
	    $API  = new PerchAPI(1.0, 'hello_church');
		$Session = PerchMembers_Session::fetch();
		$Churches = new HelloChurch_Churches($API);
		$Church = new HelloChurch_Church($API);

		if(is_object($Churches)){
			$Church = $Churches->find_by_customer_id($customer_id);
			$Church = $Churches->find($Church['churchID']);
        	if(is_object($Church)) {
				$Church->update_subscription_details(
					$subscription_id,
				    $payment_method,
				    $current_period_end,
				    $cancel,
				    $plan_id,
				    $cost
				);
// 				$Church = $Churches->find($Session->get('churchID'));
                $PerchMembers_Auth = new PerchMembers_Auth($API);
                $PerchMembers_Auth->refresh_session_data($Member);
			}
		}
    }
    
    function ical_feed($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchEvents = new HelloChurch_Events($API);
	    
		$events = $HelloChurchEvents->events($churchID);
		
		foreach($events as $event){
			
echo 'BEGIN:VEVENT
SUMMARY:'.$event['eventName'].'
UID:hellochurch_'.$event['eventID'];
$dateParts = explode(" ", $event['start']);
$start = str_replace("-", "", $dateParts[0])."T".str_replace(":", "", $dateParts[1])."Z";
$timestamp = strtotime($dateParts[0]);
$dateDay = date('N', $timestamp);
if($dateDay==1){$day='MO';}
if($dateDay==2){$day='TU';}
if($dateDay==3){$day='WE';}
if($dateDay==4){$day='TH';}
if($dateDay==5){$day='FR';}
if($dateDay==6){$day='SA';}
if($dateDay==7){$day='SU';}
$dateParts = explode(" ", $event['end']);
$end = str_replace("-", "", $dateParts[0])."T".str_replace(":", "", $dateParts[1])."Z";
if($event['repeatEvent']=='daily'){
	echo '
RRULE:FREQ=DAILY;INTERVAL=1;UNTIL='.str_replace("-", "", $event['repeatEnd']).'T235959Z';
}elseif($event['repeatEvent']=='weekly'){
	echo '
RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY='.$day.';UNTIL='.str_replace("-", "", $event['repeatEnd']).'T235959Z';
}elseif($event['repeatEvent']=='weekdays'){
	echo '
RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=MO,TU,WE,TH,FR;UNTIL='.str_replace("-", "", $event['repeatEnd']).'T235959Z';
}
echo '
DTSTART:'.$start.'
DTEND:'.$end.'
DTSTAMP:'.$start.'
DESCRIPTION:'.strip_tags($event['eventDescription']).'
END:VEVENT
';			
		}
	    
    }
    
    function podcast_feed($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchAudios = new HelloChurch_Audios($API);
	    
		$audios = $HelloChurchAudios->audios($churchID);
		
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
				url="http://app.hellochurch.tech/podcast/episode/'.$audio['audioID'].'/audio.mp3"
				/>
				<guid>hellochurch_'.$audio['audioID'].'</guid>
				<pubDate>'.$pubDate.'</pubDate>
			</item>';
		
		}
	    
    }
    
    function podcast(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchPodcasts = new HelloChurch_Podcasts($API);
	    
	    $Session = PerchMembers_Session::fetch();
	    
		$podcast = $HelloChurchPodcasts->podcast($Session->get('churchID'));
		
		return $podcast;
	    
    }
    
    function podcast_public($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchPodcasts = new HelloChurch_Podcasts($API);
	    
		$podcast = $HelloChurchPodcasts->podcast($churchID);
		
		return $podcast;
	    
    }
    
    function search_church($q){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchChurches = new HelloChurch_Churches($API);
	    
		$results = $HelloChurchChurches->public_search($q);
		
		echo $results;
	    
    }
    
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
    
    function signed_in(){
	    
	    session_start();
	    
	    if($_SESSION['hellochurch_active_session']==true && $_SESSION['hellochurch_active_church']>0 && $_SESSION['hellochurch_active_contact']>0){
		    return true;
	    }else{
		    return false;
	    }
	    
    }