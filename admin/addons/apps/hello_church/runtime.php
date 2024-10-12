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
		
		return $owner;
		
	}
	
	function hello_church_contacts(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		$HelloChurchChurches = new HelloChurch_Churches($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$church = $HelloChurchChurches->church($Session->get('memberID'));
		
		$html = '';
		
		if($church){
			
			$contacts = $HelloChurchContacts->contacts($Session->get('memberID'), $church['churchID']);
			
			if($contacts){
				
				$html .= '
					<article class="grid contacts flow">
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
								<a href="/contacts/edit-contact?id='.$contact['contactID'].'">'.$contact['contactFirstName'].' '.$contact['contactLastName'].'</a>
							</div>
							<div class="td">
								'.$contact['contactAddress1'].'
							</div>
							<div class="td">
								'.$contact['contactPhone'].'<br />
								'.$contact['contactEmail'].'
							</div>
							<div class="td">
								'.$tags.'
							</div>
							<div class="td">
								<a class="button primary small" href="/contacts/edit-contact?id='.$contact['contactID'].'">Edit</a>
							</div>
						</div>';
				}
				
				$html .= '
						
					</article>';
				
			}else{
				$html .= '<article class="flow"><p class="alert warning">No contacts created - <a href="/contacts/add-a-contact">add one</a>.</p></article>';
			}
			
			
		}else{
			$html .= '<article class="flow"><p class="alert warning">No church defined - please contact support.</p></article>';
		}
		
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
			$html = '<ul class="tags">';
		}

		foreach($tags as $tag){
			$html .= "<li>".$tag['value']."</li>";
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
			
		}
		
        $html = $Template->render($data);
        $html = $Template->apply_runtime_post_processing($html, $data);

        if ($return) return $html;
        echo $html;
    }
    
    function hello_church_form_handler($SubmittedForm) {
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $HelloChurchChurch = new HelloChurch_Church($API);
	    $HelloChurchChurches = new HelloChurch_Churches($API); 
	    $HelloChurchContact = new HelloChurch_Contact($API);
	    $HelloChurchContacts = new HelloChurch_Contacts($API); 

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
	            	$HelloChurchContacts->create($data);
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
	            } 
            break;
            case 'delete_contact':
		        $contact = $HelloChurchContacts->find($SubmittedForm->data['contactID']);
		        $contact->delete(); 
            break;
        }
    	
    	// access logged errors
	    $Perch = Perch::fetch();
	    $form_errors = $Perch->get_form_errors($SubmittedForm->formID);
    }
