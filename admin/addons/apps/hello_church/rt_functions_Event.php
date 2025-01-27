<?php
	
	/** CREATE CALENDAR INTERFACE **/
	function hello_church_calendar(){
		
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
UID:hellochurch_'.$event['eventID'].'_b';
$dateParts = explode(" ", $event['start']);
$start = str_replace("-", "", $dateParts[0])."T".str_replace(":", "", $dateParts[1]);
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
DTEND;TZID=Europe/London:'.$end.'
DTSTAMP:'.date('Ymd').'T'.date('His').'Z
END:VEVENT
';			
		}
$html .= 'END:VCALENDAR';

echo $html;
	    
    }
    
?>