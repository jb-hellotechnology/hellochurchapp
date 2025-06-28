<?php
	
	/** SAVE EMAIL CONTENT **/
	function process_save_email($emailID, $plan){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchEmails = new HelloChurch_Emails($API);
        
		$Session = PerchMembers_Session::fetch();
		$memberID = $Session->get('memberID');
		$churchID = perch_member_get('churchID');
		
		$email = $HelloChurchEmails->save_email($memberID, $churchID, $emailID, $plan);
		
		return $email;
	    
    }
    
    /** STORE BREVO DATA FOR SENT EMAIL **/
    function hello_church_store_email_result($emailID, $result){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchEmails = new HelloChurch_Emails($API);
		
		$HelloChurchEmails->save_email_result($emailID, $result);
	    
    }
    
    /** LOG EMAIL AGAINST CONTACT **/
	function hello_church_log_email_contact($emailID, $recipients){
		
		$API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchEmails = new HelloChurch_Emails($API);
		
		foreach($recipients as $recipient){
			$HelloChurchEmails->log_email_contact($emailID, $recipient);
		}
		
	}
	
	/** LIST EMAILS SENT TO CONTACT **/
	function hello_church_contact_emails($contactID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchEmails = new HelloChurch_Emails($API);
        
        $emails = $HelloChurchEmails->contact_emails($contactID);
		
		echo '<article>
				<ul class="list">';
        
        foreach($emails as $email){
	
	        $emailContent = $HelloChurchEmails->find($email['emailID']);

			if($emailContent){
	        	$date = date('m/d/Y H:i:s', strtotime($emailContent->emailSent()));
			
				echo '<li>
						<div class="heading">
							<span class="material-symbols-outlined">mail</span>
							<h3><a href="/communication/view-email?id='.$email['emailID'].'">'.$emailContent->emailSubject().'</a></h3>
							<p class="mono">'.$date.'</p>
						</div>
						<div class="functions">
							<a href="/communication/view-email?id='.$email['emailID'].'" class="button secondary small">View</a>
						</div>
					</li>';
					
			}
				
        }

        echo '</ul>
        	</article>';
		
	}

	/** LIST EMAILS **/
    function hello_church_email($status){
	    
	 	$API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = perch_member_get('churchID');

        $HelloChurchEmails = new HelloChurch_Emails($API);

        $emails = $HelloChurchEmails->emails($churchID,$status);

		if($status=='Draft'){
			$task = 'edit';
		}else{
			$task = 'view';
		}

		echo '<article>
				<ul class="list">';
        
        foreach($emails as $email){
	        echo '<li>
	        		<div class="heading">
		        		<span class="material-symbols-outlined">mail</span>
				        <h3><a href="/communication/'.$task.'-email?id='.$email['emailID'].'">'.$email['emailSubject'].'</a></h3>';
				        if($email['emailSent']){
				        	echo '<p class="mono">'.date('m/d/Y H:i:s', strtotime($email['emailSent'])).'</p>';
				        }
				    echo '
			        </div>
			        <div class="functions">';
			    if($status=='Draft'){
				    echo '
				    <a href="/communication/'.$task.'-email?id='.$email['emailID'].'" class="button secondary small">View</a>
					<form><a href="/process/delete-email?id='.$email['emailID'].'" class="button danger small border"><span class="material-symbols-outlined">delete</span></a></form>';
				}else{
					echo '<a href="/communication/'.$task.'-email?id='.$email['emailID'].'" class="button secondary small">View</a>';
				}
				echo '</div>
				</li>';
        }

        echo '</ul>
        	</article>';   
	    
    }
    
    /** GET EMAIL CONTENT **/
    function hello_church_get_email_content($emailID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchEmails = new HelloChurch_Emails($API);
        $HelloChurchEvents = new HelloChurch_Events($API);
        $HelloChurchFolders = new HelloChurch_Folders($API);
		$HelloChurchTrainingSessions = new HelloChurch_Training_Sessions($API);
	    
	    $email = $HelloChurchEmails->email($emailID);
	    
	    $email = json_decode($email['emailContent'], true);

		foreach($email as $type => $item){
							
			$typeParts = explode("_", $type);
			$type = $typeParts[0];

			if($type=='heading'){
				$emailContent .= '<h2 style="font-family: Helvetica, sans-serif; font-size: 24px; font-weight: strong; margin: 0; margin-bottom: 16px;">'.$item.'</h2>';
			}
			if($type=='text'){
				$emailContent .= '<p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">'.nl2br($item).'</p>';
			}
			if($type=='youtube'){
				$emailContent .= preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>",$item);
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
				$passage = strip_tags($json['passages'][0],"<p><a>");
				$emailContent .= '<div style="font-family: Helvetica, sans-serif !important; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;background:#f0f2f9;padding:16px;">'.$passage.'</div>';
		
			}
			if($type=='link'){
				
				if($typeParts[2]=='text'){
					$buttonText = $item;	
				}else{
				
				$emailContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
		                    <tbody>
		                      <tr>
		                        <td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
		                          <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
		                            <tbody>
		                              <tr>
		                                <td style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; border-radius: 4px; text-align: center; background-color: #142c8e;" valign="top" align="center" bgcolor="#0867ec"> <a href="'.$item.'" target="_blank" style="border: solid 2px #142c8e; border-radius: 4px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 16px; font-weight: bold; margin: 0; padding: 12px 24px; text-decoration: none; text-transform: capitalize; background-color: #142c8e; border-color: #142c8e; color: #ffffff;">'.$buttonText.'</a> </td>
		                              </tr>
		                            </tbody>
		                          </table>
		                        </td>
		                      </tr>
		                    </tbody>
		                  </table>';
		                  
		        }
			}
			if($type=='image'){
				$image = hello_church_file($item);
				$emailContent .= '<img src="https://app.churchplanner.co.uk/feed/file-image/'.$image['churchID'].'/'.$image['fileID'].'" alt="Image" style="margin-bottom:16px;" />';
			}
			if($type=='file'){
				$file = hello_church_file($item);
				$emailContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
		                    <tbody>
		                      <tr>
		                        <td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
		                          <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
		                            <tbody>
		                              <tr>
		                                <td style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; border-radius: 4px; text-align: center; background-color: #142c8e;" valign="top" align="center" bgcolor="#0867ec"> <a href="https://app.churchplanner.co.uk/feed/file/'.$file['churchID'].'/'.$file['fileID'].'" target="_blank" style="border: solid 2px #142c8e; border-radius: 4px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 16px; font-weight: bold; margin: 0; padding: 12px 24px; text-decoration: none; text-transform: capitalize; background-color: #142c8e; border-color: #142c8e; color: #ffffff;">Download: '.$file['fileName'].'</a> </td>
		                              </tr>
		                            </tbody>
		                          </table>
		                        </td>
		                      </tr>
		                    </tbody>
		                  </table>';
			}
			if($type=='event'){
				$parts = explode("_", $item);
				$event = $HelloChurchEvents->event($parts[0]);
				$tParts = explode(" ", $parts[1]);
				$dParts = explode("-", $tParts[0]);
				$timeStamp = "$dParts[2]/$dParts[1]/$dParts[0] $tParts[1]";
				$emailContent .= '<div style="font-family: Helvetica, sans-serif !important; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;background:#f0f2f9;padding:16px;"><h2 style="font-family: Helvetica, sans-serif; font-size: 20px; font-weight: strong; margin: 0; margin-bottom: 16px;">'.$event['eventName'].'</h2><p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; font-style:italic; margin: 0; margin-bottom: 16px;">'.$timeStamp.'</p><p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0;">'.nl2br($event['eventDescription']).'</p></div>';
		
			}
			if($type=='plan'){
				
				$plan = $HelloChurchEvents->get_plan_by_id($item);
				$plan = json_decode($plan, true);
				
				foreach($plan as $type => $item){
									
					$typeParts = explode("_", $type);
					$type = $typeParts[0];
					
					if($type=='heading'){
						$emailContent .= '<h2 style="font-family: Helvetica, sans-serif; font-size: 24px; font-weight: strong; margin: 0; margin-bottom: 16px;">'.$item.'</h2>';
					}
					if($type=='text'){
						$emailContent .= '<p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">'.nl2br($item).'</p>';
					}
					if($type=='youtube'){
						$emailContent .=  preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<a href='".$item."'><img src='https://img.youtube.com/vi/$1/hqdefault.jpg' alt='YouTube' style='margin-bottom:16px;' /></a>",$item);
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
						$passage = strip_tags($json['passages'][0],"<p><a>");
						$emailContent .= '<div style="font-family: Helvetica, sans-serif !important; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;background:#f0f2f9;padding:16px;">'.$passage.'</div>';
				
					}
					if($type=='link'){
						
						if($typeParts[2]=='text'){
							$buttonText = $item;	
						}else{
							$emailContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
								<tbody>
								  <tr>
									<td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
									  <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
										<tbody>
										  <tr>
											<td style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; border-radius: 4px; text-align: center; background-color: #142c8e;" valign="top" align="center" bgcolor="#0867ec"> <a href="'.$item.'" target="_blank" style="border: solid 2px #142c8e; border-radius: 4px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 16px; font-weight: bold; margin: 0; padding: 12px 24px; text-decoration: none; text-transform: capitalize; background-color: #142c8e; border-color: #142c8e; color: #ffffff;">'.$buttonText.'</a> </td>
										  </tr>
										</tbody>
									  </table>
									</td>
								  </tr>
								</tbody>
							  </table>';
						}
						
					}
					
				}
			
			}
			
			if($type=='training'){
				
				$session = $HelloChurchTrainingSessions->get_session(null, null, $item);
				$session = json_decode($session, true);
				
				foreach($session as $type => $item){
									
					$typeParts = explode("_", $type);
					$type = $typeParts[0];
					
					if($type=='heading'){
						$emailContent .= '<h2 style="font-family: Helvetica, sans-serif; font-size: 24px; font-weight: strong; margin: 0; margin-bottom: 16px;">'.$item.'</h2>';
					}
					if($type=='text'){
						$emailContent .= '<p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">'.nl2br($item).'</p>';
					}
					if($type=='youtube'){
						$emailContent .=  preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<a href='".$item."'><img src='https://img.youtube.com/vi/$1/hqdefault.jpg' alt='YouTube' style='margin-bottom:16px;' /></a>",$item);
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
						$passage = strip_tags($json['passages'][0],"<p><a>");
						$emailContent .= '<div style="font-family: Helvetica, sans-serif !important; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;background:#f0f2f9;padding:16px;">'.$passage.'</div>';
				
					}
					if($type=='link'){
						
						if($typeParts[2]=='text'){
							$buttonText = $item;	
						}else{
							$emailContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
								<tbody>
								  <tr>
									<td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
									  <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
										<tbody>
										  <tr>
											<td style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; border-radius: 4px; text-align: center; background-color: #142c8e;" valign="top" align="center" bgcolor="#0867ec"> <a href="'.$item.'" target="_blank" style="border: solid 2px #142c8e; border-radius: 4px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 16px; font-weight: bold; margin: 0; padding: 12px 24px; text-decoration: none; text-transform: capitalize; background-color: #142c8e; border-color: #142c8e; color: #ffffff;">'.$buttonText.'</a> </td>
										  </tr>
										</tbody>
									  </table>
									</td>
								  </tr>
								</tbody>
							  </table>';
						}
						
					}
					if($type=='image'){
						$image = hello_church_file($item);
						$emailContent .= '<img src="https://app.churchplanner.co.uk/feed/file-image/'.$image['churchID'].'/'.$image['fileID'].'" alt="Image" style="margin-bottom:16px;" />';
					}
					if($type=='file'){
						$file = hello_church_file($item);
						$emailContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
									<tbody>
									  <tr>
										<td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
										  <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
											<tbody>
											  <tr>
												<td style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; border-radius: 4px; text-align: center; background-color: #142c8e;" valign="top" align="center" bgcolor="#0867ec"> <a href="https://app.churchplanner.co.uk/feed/file/'.$file['churchID'].'/'.$file['fileID'].'" target="_blank" style="border: solid 2px #142c8e; border-radius: 4px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 16px; font-weight: bold; margin: 0; padding: 12px 24px; text-decoration: none; text-transform: capitalize; background-color: #142c8e; border-color: #142c8e; color: #ffffff;">Download: '.$file['fileName'].'</a> </td>
											  </tr>
											</tbody>
										  </table>
										</td>
									  </tr>
									</tbody>
								  </table>';
					}
					
				}
				
			}
			
		}
	    
	    return $emailContent;
	    
    }
    
    /** LIST EMAIL RECIPIENTS **/
    function email_recipients($emailID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEmails = new HelloChurch_Emails($API);
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		
		$recipients = $HelloChurchEmails->recipients($emailID);
		echo "<ul>";
		foreach($recipients as $recipient){

			$contact = $HelloChurchContacts->find($recipient['contactID']);
			echo '<li><a href="/contacts/edit-contact?id='.$recipient['contactID'].'">'.$contact->contactFirstName().' '.$contact->contactLastName().'</a></li>';
			
		}
		echo "</ul>";
	    
    }
    
    /** CHECKED SIGNED IN USER IS OWNER OF EMAIL **/
    function hello_church_email_owner($emailID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEmails = new HelloChurch_Emails($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchEmails->check_owner(perch_member_get('churchID'), $emailID);
		return $owner;
		
	}
	
	/** GET EMAIL **/
	function hello_church_get_email($emailID){
	
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEmails = new HelloChurch_Emails($API);
		
		$email = $HelloChurchEmails->email($emailID);
		
		return $email;
		
	}
	
	/** DELETE EMAIL **/
	function hello_church_delete_email($emailID){
	    
	 	$API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();

        $HelloChurchEmails = new HelloChurch_Emails($API);
        
        $email = $HelloChurchEmails->find($emailID);
        $email->delete();
        
    }
    
    /** POPULATE SELECT **/
    function hello_church_get_populate_select($type){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $HelloChurchFolders = new HelloChurch_Folders($API);
	    $HelloChurchEvents = new HelloChurch_Events($API);
		$HelloChurchTrainingSessions = new HelloChurch_Training_Sessions($API);
	    
	    if($type=='image'){
		    $files = $HelloChurchFolders->images_for_email(perch_member_get('churchID'));
			echo $files;
	    }elseif($type=='file'){
		    $files = $HelloChurchFolders->files_for_email(perch_member_get('churchID'));
			echo $files;
	    }elseif($type=='event'){
		    $events = $HelloChurchEvents->events_for_email(perch_member_get('churchID'));
			echo $events;
	    }elseif($type=='plan'){
			$plans = $HelloChurchEvents->event_plans_for_email(perch_member_get('churchID'));
			echo $plans;
		}elseif($type=='training'){
			$training = $HelloChurchTrainingSessions->training_sessions_for_email(perch_member_get('churchID'));
			echo $training;
		}
	    
    }
    
?>