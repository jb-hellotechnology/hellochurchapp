<?php
	
	/** CREATE CALENDAR INTERFACE **/
	function hello_church_calendar($view){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEvents = new HelloChurch_Events($API);
		
		$Session = PerchMembers_Session::fetch();
		$churchID = perch_member_get('churchID');
		
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
			
			$dateParts = explode(" ", $event['start']);
			
			$exclusions = explode(" ", $event['exclusions']);
			$exclusions_string = '';
			foreach($exclusions as $exclusion){
				if($exclusion){
					$exclusions_string .= "'".$exclusion."T".$dateParts[1]."',";
				}
			}
			$exclusions_string = substr($exclusions_string, 0, -1);
			
			$eventsHTML .= '
			{
		      title: "'.$event['eventName'].'",
		      start: "'.$event['start'].'",
		      end: "'.$event['end'].'",';
		      
		      if($event['repeatEvent']<>''){
			      $pStart = explode(" ", $event['start']);
			      $pEnd = explode(" ", $event['end']);
				  if($event['repeatEvent']=='daily'){
						$eventsHTML .= '
						rrule: {
						  freq: "DAILY",
						  interval: 1,
						  byweekday: [ "mo", "tu", "we", "th", "fr", "sa", "su" ],
						  dtstart: "'.str_replace(" ", "T", $event['start']).'",
						  until: "'.str_replace(" ", "T", $event['repeatEnd']).'"
						},
						exdate: ['.$exclusions_string.'],';
				  }
				  if($event['repeatEvent']=='weekdays'){
						$eventsHTML .= '
						rrule: {
						  freq: "DAILY",
						  interval: 1,
						  byweekday: [ "mo", "tu", "we", "th", "fr" ],
						  dtstart: "'.str_replace(" ", "T", $event['start']).'",
						  until: "'.str_replace(" ", "T", $event['repeatEnd']).'"
						},
						exdate: ['.$exclusions_string.'],';
					}
				  if($event['repeatEvent']=='weekly'){
					  $eventsHTML .= '
						rrule: {
						  freq: "WEEKLY",
						  interval: 1,
						  dtstart: "'.str_replace(" ", "T", $event['start']).'",
						  until: "'.str_replace(" ", "T", $event['repeatEnd']).'"
						},
						exdate: ['.$exclusions_string.'],';
				  }
				  
			      // $eventsHTML .= '
			      // daysOfWeek: "'.$daysOfWeek.'",
			      // startTime: "'.$pStart[1].'",
			      // endTime: "'.$pEnd[0].'",
			      // startRecur: "'.$event['start'].'",
			      // endRecur: "'.$event['repeatEnd'].' 23:59:59",';
		      }
		    $eventsHTML .= '
		      url: "/calendar/edit-event?id='.$event['eventID'].'&date=",
		      displayEventEnd: true,
		    },';
			
		}
		
		$eventsHTML = substr($eventsHTML, 0, -1);
		
		$eventsHTML .= ']';
		
		$html .= "<script>

	      document.addEventListener('DOMContentLoaded', function() {
	        var calendarEl = document.getElementById('calendar');
	        var calendar = new FullCalendar.Calendar(calendarEl, {
			  initialView: '".$view."',
	          headerToolbar: {
		        left: 'prev,next today',
		        center: 'title',
		        right: 'listWeek,dayGridMonth'
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
	
	/** SAVE EVENT PLAN **/
	function process_save_plan($planID, $date, $time, $plan){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchEvents = new HelloChurch_Events($API);
        
		$Session = PerchMembers_Session::fetch();
		$memberID = $Session->get('memberID');
		$churchID = perch_member_get('churchID');
		
		$plan = $HelloChurchEvents->save_plan($memberID, $churchID, $planID, $date, $time, $plan);
		
		return $plan;
	    
    }
    
    /** GET EVENT PLAN **/
    function hello_church_get_plan($eventID, $date, $time){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchEvents = new HelloChurch_Events($API);
        
		$Session = PerchMembers_Session::fetch();
		$memberID = $Session->get('memberID');
		$churchID = perch_member_get('churchID');
		
		$plan = $HelloChurchEvents->get_plan($memberID, $churchID, $eventID, $date, $time);
		
		return $plan;
		
    }
	
	/** GET EVENT PLAN BY ID **/
	function hello_church_get_event_plan_by_id($planID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$HelloChurchEvents = new HelloChurch_Events($API);
		
		$plan = $HelloChurchEvents->get_plan_by_id($planID); 
		
		return $plan;
		
	}
    
    /** CHECK SIGNED IN USER IS OWNER OF EVENT **/
    function hello_church_event_owner($eventID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEvents = new HelloChurch_Events($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchEvents->check_owner(perch_member_get('churchID'), $eventID);
		
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
	    
	}
	
	/** GET EVENTS FROM CALENDAR **/
	function hello_church_calendar_get($id, $field){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEvents = new HelloChurch_Events($API);
		
		$event = $HelloChurchEvents->find($id);
		return $event->$field();
		
	}
	
	/** GET EVENT **/
	function hello_church_event($id){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchEvents = new HelloChurch_Events($API);
		
		$event = $HelloChurchEvents->find($id);
		return $event;
		
	}
	
	/** LIST RESPONSIBILITIES **/
	function hello_church_contact_responsibilities($id){
		
		$API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = perch_member_get('churchID');

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
	        $html .= '<li>
	        			<div class="heading">
		        			<span class="material-symbols-outlined">event</span>
	        				<h3><a href="/calendar/edit-event?id='.$responsibility['eventID'].'&date='.$responsibility['eventDate'].'">'.$responsibility['roleName'].'</a></h3>
	        				<p class="mono">'.$responsibility['eventName'].'</p>
	        				<p class="mono">'.$date.'</p>
	        			</div>
	        			<div class="functions">
	        				<a href="/calendar/edit-event?id='.$responsibility['eventID'].'&date='.$responsibility['eventDate'].'" class="button secondary small">View</a>
	        			</div>
	        		</li>';
        }
        
        $html .= '</ul></article>';
        
        echo $html;
		
	}
	
	/** LIST RESPONSIBILITIES **/
	function hello_church_contact_responsibilities_email($id, $date){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		
		$Session = PerchMembers_Session::fetch();
		
		$churchID = perch_member_get('churchID');
	
		$HelloChurchEvents = new HelloChurch_Events($API);
		$HelloChurchRoles = new HelloChurch_Roles($API);
		$HelloChurchContacts = new HelloChurch_Contacts($API);
		
		$responsibilities = $HelloChurchEvents->event_responsibilities_email($id, $date);
		
		$html .= '<ul>';
		
		foreach($responsibilities as $responsibility){
			$html .= '<li>
						<strong>'.$responsibility['roleName'].':</strong> '.$responsibility['contactFirstName'].' '.$responsibility['contactLastName'].'
					</li>';
		}
		
		$html .= '</ul>';
		
		return $html;
		
	}
	
	/** CREATE ICAL FEED FOR CALENDAR **/
	function ical_feed($churchName, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');

        $HelloChurchEvents = new HelloChurch_Events($API);

		$events = $HelloChurchEvents->eventsFeed($churchID);
		
$html = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hellchurch.tech//hellochurch 1.0//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:'.$churchName.' - Hello Church
X-WR-TIMEZONE:Europe/London
';
		
		foreach($events as $event){
			
$html .= 'BEGIN:VEVENT
SUMMARY:'.$event['eventName'].'
UID:hellochurch_'.$event['eventID'].'_f';
$dateParts = explode(" ", $event['start']);
$start = str_replace("-", "", $dateParts[0])."T".str_replace(":", "", $dateParts[1]);
$startTime = str_replace(":", "", $dateParts[1]);
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
$end = str_replace("-", "", $dateParts[0])."T".str_replace(":", "", $dateParts[1]);
if($event['repeatEvent']=='daily'){
	$html .='
RRULE:FREQ=DAILY;INTERVAL=1;UNTIL='.str_replace("-", "", $event['repeatEnd']).'T235959Z';
}elseif($event['repeatEvent']=='weekly'){
	$html .= '
RRULE:FREQ=WEEKLY;UNTIL='.str_replace("-", "", $event['repeatEnd']).'T235959Z';
}elseif($event['repeatEvent']=='weekdays'){
	$html .= '
RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=MO,TU,WE,TH,FR;UNTIL='.str_replace("-", "", $event['repeatEnd']).'T235959Z';
}
$html .= '
DTSTART;TZID=Europe/London:'.$start.'
DTEND;TZID=Europe/London:'.$end;
if($event['exclusions']){
	$exclusions = explode(" ", $event['exclusions']);
	$exclusions_string = '';
	foreach($exclusions as $exclusion){
		if($exclusion){
			$exclusions_string .= $exclusion."T".$startTime.",";
		}
	}
	$exclusions_string = substr(str_replace("-", "", $exclusions_string), 0, -1);
	$html .= '
EXDATE;TZID=Europe/London:'.$exclusions_string;
}
$html .= '
DTSTAMP:'.date('Ymd').'T'.date('His').'Z
DESCRIPTION:'.strip_tags($event['eventDescription']);
if($event['venues']){
	$venues = json_decode($event['venues'], true);
	$venuesText = '';
	foreach($venues[0] as $venue){
		$venuesText .= $venue.', ';
	}
	$venuesText = substr($venuesText, 0, -2);
	$html .= '
LOCATION:'.$venuesText;
}
$html .= '
END:VEVENT
';			
		}
$html .= 'END:VCALENDAR';

echo $html;
	    
    }
	
	function public_plan($churchID, $planID){

		$API  = new PerchAPI(1.0, 'hello_church');
		
		$HelloChurchTrainingSessions = new HelloChurch_Training_Sessions($API);
		$HelloChurchEvents = new HelloChurch_Events($API);
		$HelloChurchFolders = new HelloChurch_Folders($API);
		
		$planID = $planID/365;

		$plan = $HelloChurchEvents->get_plan_by_id($planID);
		$plan = json_decode($plan, true);
		
		foreach($plan as $type => $item){
							
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
    
?>