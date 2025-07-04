<?php
	
	/* LIST TOPICS */
	function hello_church_training_topics(){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$HelloChurchTrainingTopics = new HelloChurch_Training_Topics($API);
		
		$churchID = perch_member_get('churchID');
		
		$topics = $HelloChurchTrainingTopics->topics($churchID);
		
		echo '<article>
				<ul class="list">';
		
		foreach($topics as $topic){
			
			echo '<li>
					<div class="heading">
						<span class="material-symbols-outlined">book</span>
						<h3><a href="/training/view-topic?id='.$topic['topicID'].'">'.$topic['topicName'].'</a></h3>
					</div>
					<div class="functions">
						<a href="/training/view-topic?id='.$topic['topicID'].'" class="button secondary small">View</a>
					</div>
				</li>';
				
		}
		
		echo '</ul>
			</article>';
			
	}
	
	/* LIST TOPICS */
	function hello_church_training_sessions($topicID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$HelloChurchTrainingSessions = new HelloChurch_Training_Sessions($API);
		
		$sessions = $HelloChurchTrainingSessions->sessions($topicID);
		
		echo '<article>
				<ul class="list sortable-sessions draggable-sessions">';
		
		foreach($sessions as $session){
			
			echo '<li data-session="'.$session['sessionID'].'">
					<div class="heading">
						<span class="material-symbols-outlined">book</span>
						<h3><a href="/training/create-session?id='.$session['sessionID'].'">'.$session['sessionName'].'</a></h3>
					</div>
					<div class="functions">
						<a href="/training/create-session?id='.$session['sessionID'].'" class="button secondary small">View</a>
					</div>
				</li>';
				
		}
		
		echo '</ul>
			</article>';
			
	}
	
	/** CHECKED SIGNED IN USER IS OWNER OF TOPIC **/
	function hello_church_topic_owner($topicID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchTrainingTopics = new HelloChurch_Training_Topics($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchTrainingTopics->check_owner(perch_member_get('churchID'), $topicID);
		return $owner;
		
	}
	
	/** CHECKED SIGNED IN USER IS OWNER OF TOPIC **/
	function hello_church_session_owner($sessionID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchTrainingSessions = new HelloChurch_Training_Sessions($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchTrainingSessions->check_owner(perch_member_get('churchID'), $sessionID);
		return $owner;
		
	}
	
	/** GET TOPIC **/
	function hello_church_get_topic($topicID){
	
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchTrainingTopics = new HelloChurch_Training_Topics($API);
		
		$topic = $HelloChurchTrainingTopics->topic($topicID);
		
		return $topic;
		
	}
	
	/** GET SESSION **/
	function hello_church_get_session($sessionID){
	
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchTrainingSessions = new HelloChurch_Training_Sessions($API);
		
		$session = $HelloChurchTrainingSessions->session($sessionID);
		
		return $session;
		
	}
	
	/** CHECK IF SESSIONS EXIST **/
	function sessions_exist($topicID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$HelloChurchTrainingSessions = new HelloChurch_Training_Sessions($API);
		
		$sessions = $HelloChurchTrainingSessions->sessions($topicID);
		
		if($sessions){
			return true;
		}else{
			return false;
		}
	}
	
	/** SAVE EMAIL CONTENT **/
	function process_save_session($sessionID, $session){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$HelloChurchTrainingSessions = new HelloChurch_Training_Sessions($API);
		
		$Session = PerchMembers_Session::fetch();
		$memberID = $Session->get('memberID');
		$churchID = perch_member_get('churchID');
		
		$session = $HelloChurchTrainingSessions->save_session($memberID, $churchID, $sessionID, $session);
		
		return $session;
		
	}
	
	/** SAVE EMAIL CONTENT **/
	function process_save_session_order($sessionID, $order){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$HelloChurchTrainingSessions = new HelloChurch_Training_Sessions($API);
		
		$HelloChurchTrainingSessions->save_session_order($sessionID, $order);
		
	}
	
	function public_session($churchID, $sessionID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$HelloChurchTrainingSessions = new HelloChurch_Training_Sessions($API);
		$HelloChurchEvents = new HelloChurch_Events($API);
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$session = $HelloChurchTrainingSessions->public_session($churchID, $sessionID);

		$session = json_decode($session['sessionContent'], true);
		
		foreach($session as $type => $item){
							
			$typeParts = explode("_", $type);
			$type = $typeParts[0];
		
			if($type=='heading'){
				$sessionContent .= '<h1>'.$item.'</h1>';
			}
			if($type=='text'){
				$sessionContent .= '<p>'.nl2br($item).'</p>';
			}
			if($type=='youtube'){
				$sessionContent .= preg_replace("/\s*[a-zA-Z\/\/:\.]*youtu.be\/([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"100%\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>",$item);
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
				$sessionContent .= '<div class="bible">'.$passage.'</div>';
		
			}
			if($type=='link'){
				
				if($typeParts[2]=='text'){
					$buttonText = $item;	
				}else{
				
				$sessionContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
							<tbody>
							  <tr>
								<td align="left" style="vertical-align: top; padding-bottom: 16px;" valign="top">
								  <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
									<tbody>
									  <tr>
										<td style="vertical-align: top; border-radius: 4px; text-align: center; background-color: #142c8e;" valign="top" align="center" bgcolor="#0867ec"> <a href="'.$item.'" target="_blank" style="border: solid 2px #142c8e; border-radius: 4px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 16px; font-weight: bold; margin: 0; padding: 12px 24px; text-decoration: none; text-transform: capitalize; background-color: #142c8e; border-color: #142c8e; color: #ffffff;">'.$buttonText.'</a> </td>
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
				$sessionContent .= '<img src="https://app.churchplanner.co.uk/feed/file-image/'.$image['churchID'].'/'.$image['fileID'].'" alt="Image" style="margin-bottom:16px;" />';
			}
			if($type=='file'){
				$file = hello_church_file($item);
				$sessionContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
							<tbody>
							  <tr>
								<td align="left" style="vertical-align: top; padding-bottom: 16px;" valign="top">
								  <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
									<tbody>
									  <tr>
										<td style="vertical-align: top; border-radius: 4px; text-align: center; background-color: #142c8e;" valign="top" align="center" bgcolor="#0867ec"> <a href="https://app.churchplanner.co.uk/feed/file/'.$file['churchID'].'/'.$file['fileID'].'" target="_blank" style="border: solid 2px #142c8e; border-radius: 4px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 16px; font-weight: bold; margin: 0; padding: 12px 24px; text-decoration: none; text-transform: capitalize; background-color: #142c8e; border-color: #142c8e; color: #ffffff;">Download: '.$file['fileName'].'</a> </td>
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
				$sessionContent .= '<div style="font-weight: normal; margin: 0; margin-bottom: 16px;background:#f0f2f9;padding:16px;"><h2 style="font-family: Helvetica, sans-serif; font-size: 20px; font-weight: strong; margin: 0; margin-bottom: 16px;">'.$event['eventName'].'</h2><p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; font-style:italic; margin: 0; margin-bottom: 16px;">'.$timeStamp.'</p><p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0;">'.nl2br($event['eventDescription']).'</p></div>';
		
			}
			if($type=='plan'){
				
				$plan = $HelloChurchEvents->get_plan_by_id($item);
				$plan = json_decode($plan, true);
				
				foreach($plan as $type => $item){
									
					$typeParts = explode("_", $type);
					$type = $typeParts[0];
					
					if($type=='heading'){
						$sessionContent .= '<h2>'.$item.'</h2>';
					}
					if($type=='text'){
						$sessionContent .= '<p>'.nl2br($item).'</p>';
					}
					if($type=='youtube'){
						$sessionContent .= preg_replace("/\s*[a-zA-Z\/\/:\.]*youtu.be\/([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<a href='".$item."'><img src='https://img.youtube.com/vi/$1/hqdefault.jpg' alt='YouTube' style='margin-bottom:16px;' /></a>",$item);
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
						$sessionContent .= '<div style="font-family: Helvetica, sans-serif !important; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;background:#f0f2f9;padding:16px;">'.$passage.'</div>';
				
					}
					if($type=='link'){
						
						if($typeParts[2]=='text'){
							$buttonText = $item;	
						}else{
							$sessionContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
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
			
		}
		
		echo $sessionContent;
			
	}
	
	/** GET SESSION CONTENT **/
	function hello_church_get_session_content($sessionID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
	
		$HelloChurchTrainingSessions = new HelloChurch_Training_Sessions($API);
		$HelloChurchEvents = new HelloChurch_Events($API);
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$session = $HelloChurchTrainingSessions->session($sessionID);
		
		$session = json_decode($session['sessionContent'], true);
	
		foreach($session as $type => $item){
							
			$typeParts = explode("_", $type);
			$type = $typeParts[0];
	
			if($type=='heading'){
				$sessionContent .= '<h2 style="font-family: Helvetica, sans-serif; font-size: 24px; font-weight: strong; margin: 0; margin-bottom: 16px;">'.$item.'</h2>';
			}
			if($type=='text'){
				$sessionContent .= '<p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">'.nl2br($item).'</p>';
			}
			if($type=='youtube'){
				$sessionContent .= preg_replace("/\s*[a-zA-Z\/\/:\.]*youtu.be\/([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"100%\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>",$item);
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
				$sessionContent .= '<div style="font-family: Helvetica, sans-serif !important; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;background:#f0f2f9;padding:16px;">'.$passage.'</div>';
		
			}
			if($type=='link'){
				
				if($typeParts[2]=='text'){
					$buttonText = $item;	
				}else{
				
				$sessionContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
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
				$sessionContent .= '<img src="https://app.churchplanner.co.uk/feed/file-image/'.$image['churchID'].'/'.$image['fileID'].'" alt="Image" style="margin-bottom:16px;" />';
			}
			if($type=='file'){
				$file = hello_church_file($item);
				$sessionContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
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
				$sessionContent .= '<div style="font-family: Helvetica, sans-serif !important; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;background:#f0f2f9;padding:16px;"><h2 style="font-family: Helvetica, sans-serif; font-size: 20px; font-weight: strong; margin: 0; margin-bottom: 16px;">'.$event['eventName'].'</h2><p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; font-style:italic; margin: 0; margin-bottom: 16px;">'.$timeStamp.'</p><p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0;">'.nl2br($event['eventDescription']).'</p></div>';
		
			}
			if($type=='plan'){
				
				$plan = $HelloChurchEvents->get_plan_by_id($item);
				$plan = json_decode($plan, true);
				
				foreach($plan as $type => $item){
									
					$typeParts = explode("_", $type);
					$type = $typeParts[0];
					
					if($type=='heading'){
						$sessionContent .= '<h2 style="font-family: Helvetica, sans-serif; font-size: 24px; font-weight: strong; margin: 0; margin-bottom: 16px;">'.$item.'</h2>';
					}
					if($type=='text'){
						$sessionContent .= '<p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">'.nl2br($item).'</p>';
					}
					if($type=='youtube'){
						$sessionContent .=  preg_replace("/\s*[a-zA-Z\/\/:\.]*youtu.be\/([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<a href='".$item."'><img src='https://img.youtube.com/vi/$1/hqdefault.jpg' alt='YouTube' style='margin-bottom:16px;' /></a>",$item);
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
						$sessionContent .= '<div style="font-family: Helvetica, sans-serif !important; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;background:#f0f2f9;padding:16px;">'.$passage.'</div>';
				
					}
					if($type=='link'){
						
						if($typeParts[2]=='text'){
							$buttonText = $item;	
						}else{
							$sessionContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
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
			
		}
		
		return $sessionContent;
		
	}
    
?>