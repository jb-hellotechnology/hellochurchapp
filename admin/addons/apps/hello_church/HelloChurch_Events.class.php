<?php

class HelloChurch_Events extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_events';
	protected $pk        = 'eventID';
	protected $singular_classname = 'HelloChurch_Event';

	protected $default_sort_column = 'start';

	public $static_fields = array('eventID', 'churchID', 'memberID', 'eventName', 'eventDescription', 'allDay', 'start', 'end', 'repeatEvent', 'repeatEnd', 'roles', 'venues', 'eventPublish');
	
	public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i><li><ul><ol>');
		}
		
		return $clean;
	    
    }
	
	public function events($churchID){
		
		$sql = "SELECT * FROM perch3_hellochurch_events WHERE churchID='".$churchID."' ORDER BY start ASC";
	    $results = $this->db->get_rows($sql);
	    
	    return $results;
	    
	}
	
	public function eventsFeed($churchID){
		
		$sql = "SELECT * FROM perch3_hellochurch_events WHERE churchID='".$churchID."' AND eventPublish='Yes' ORDER BY start ASC";
	    $results = $this->db->get_rows($sql);
	    
	    return $results;
	    
	}
	
	public function check_owner($churchID, $eventID){

		$API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_events WHERE churchID='".$churchID."' AND eventID='".$eventID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
	}
	
	public function event($eventID){
		
		$sql = "SELECT * FROM perch3_hellochurch_events WHERE eventID='".$eventID."'";
	    $results = $this->db->get_row($sql);
	    
	    return $results;
	    
	}
	
	public function event_roles($eventID){
		
		$sql = "SELECT roles FROM perch3_hellochurch_events WHERE eventID='".$eventID."'";
	    $results = $this->db->get_row($sql);
	    
	    return $results;
	    
	}
	
	public function add_role_contact($memberID, $churchID, $eventID, $eventDate, $contactID, $roleID){
		
		$sql = "INSERT INTO perch3_hellochurch_roles_contacts (memberID, churchID, eventID, eventDate, contactID, roleID) VALUES 
		('$memberID', '$churchID', '$eventID', '$eventDate', '$contactID', '$roleID')";
		$result = $this->db->execute($sql); 
		
	}
	
	public function event_contact_roles($eventID, $eventDate, $roleID){
		
		$date = explode(" ", $eventDate);
		
		$sql = "SELECT * FROM perch3_hellochurch_roles_contacts WHERE eventID='".$eventID."' AND eventDate='".$date[0]."' AND roleID='".$roleID."'";
	    $results = $this->db->get_rows($sql);
	    
	    return $results;
	    
	}
	
	public function remove_role_contact($roleContactID){
		
		$sql = "DELETE FROM perch3_hellochurch_roles_contacts WHERE roleContactID='$roleContactID'";
		$result = $this->db->execute($sql); 
		
	}
	
	public function event_responsibilities($contactID){
		
		$date = date('Y-m-d');
		
		$sql = "SELECT r.roleName, e.eventName, e.start, e.eventID, rc.eventDate 
		FROM perch3_hellochurch_roles_contacts rc
		JOIN perch3_hellochurch_roles r ON rc.roleID = r.roleID
		JOIN perch3_hellochurch_events e ON rc.eventID = e.eventID
		WHERE rc.contactID = $contactID AND LEFT(rc.eventDate, 10)>='$date'";
		$results = $this->db->get_rows($sql);
	    
	    return $results;
		
	}
	
	public function event_responsibilities_email($eventID, $date){
		
		$sql = "SELECT r.roleName, e.eventName, e.start, e.eventID, rc.eventDate, c.contactFirstName, c.contactLastName FROM perch3_hellochurch_roles_contacts rc JOIN perch3_hellochurch_roles r ON rc.roleID = r.roleID JOIN perch3_hellochurch_events e ON rc.eventID = e.eventID JOIN perch3_hellochurch_contacts c ON rc.contactID = c.contactID WHERE e.eventID = $eventID AND LEFT(rc.eventDate, 10)='$date'; ";
		$results = $this->db->get_rows($sql);
		
		return $results;
		
	}
	
	public function event_responsibilities_role($roleID){
		
		$date = date('Y-m-d');
		
		$sql = "SELECT r.roleName, r.roleType, rc.contactID, e.eventName, e.start, rc.eventDate 
		FROM perch3_hellochurch_roles_contacts rc
		JOIN perch3_hellochurch_roles r ON rc.roleID = r.roleID
		JOIN perch3_hellochurch_events e ON rc.eventID = e.eventID
		WHERE rc.roleID = $roleID AND LEFT(rc.eventDate, 10)>='$date' ORDER BY rc.eventDate";
	
		$results = $this->db->get_rows($sql);
	    
	    return $results;
		
	}
	
	public function save_plan($memberID, $churchID, $planID, $date, $time, $plan){
		
		$sql = "SELECT * FROM perch3_hellochurch_event_plans WHERE eventID='".$planID."' AND eventDate='".$date."' AND eventTime='".$time."'";
	    $results = $this->db->get_rows($sql);
	    
	    if(count($results)==1){
		    
		    $sql = "UPDATE perch3_hellochurch_event_plans SET eventPlan='".addslashes($plan)."' WHERE eventID='".$planID."' AND eventDate='".$date."' AND eventTime='".$time."'";
		    $result = $this->db->execute($sql);
		    
	    }else{
		    
		    $sql = "INSERT INTO perch3_hellochurch_event_plans (memberID, churchID, eventID, eventDate, eventTime, eventPlan) VALUES 
		    ('".$memberID."', '".$churchID."', '".$planID."', '".$date."', '".$time."', '".addslashes($plan)."')";
		    $result = $this->db->execute($sql);
		    
	    }
		
	}
	
	public function get_plan($memberID, $churchID, $eventID, $date, $time){
		
		$sql = "SELECT * FROM perch3_hellochurch_event_plans WHERE eventID='".$eventID."' AND eventDate='".$date."' AND eventTime='".$time."' ORDER BY eventPlanID DESC LIMIT 1";
	    $result = $this->db->get_row($sql);
	    
	    return $result['eventPlan'];
		
	}
	
	public function get_plan_by_id($planID){
		
		$sql = "SELECT * FROM perch3_hellochurch_event_plans WHERE eventPlanID='".$planID."'";
		$result = $this->db->get_row($sql);
		
		return $result['eventPlan'];
		
	}
	
	public function event_plans_for_email($churchID){
		
		$today = date('Y-m-d');
		
		$sql = "SELECT * FROM perch3_hellochurch_event_plans WHERE churchID='".$churchID."' AND eventDate>='".$today."' ORDER BY eventDate ASC";
		$results = $this->db->get_rows($sql);
		
		$plans = array();
		
		foreach($results as $plan){
			$sql2 = "SELECT * FROM perch3_hellochurch_events WHERE eventID='".$plan['eventID']."'";
			$result2 = $this->db->get_row($sql2);
			
			$fDate = date("d/m/Y H:i:s", strtotime($plan['eventDate'].' '.$plan['eventTime']));
			
			$thisPlan = array('value' => $plan['eventPlanID'], 'text' => $result2['eventName'].' - '.$fDate);
			$plans[] = $thisPlan;
		}
		
		return json_encode($plans);
	}
	
	
	public function events_for_email($churchID){
		
		$today = date('Y-m-d');
		
		$sql = "SELECT * FROM perch3_hellochurch_events WHERE churchID='".$churchID."' AND 
			(
				LEFT(start, 10)>='".$today."' OR 
				(repeatEvent!='' AND repeatEnd>='".$today."')
			)
			ORDER BY start ASC";
	    $results = $this->db->get_rows($sql);
	    
	    //print_r($results);
	    
	    $events = array();
		
	    $results = $this->db->get_rows($sql);
	    
	    foreach($results as $event){
		    
		    $startParts = explode(" ", $event['start']);
		    $dateParts = explode("-", $startParts[0]);
		    $startDate = "$dateParts[2]/$dateParts[1]/$dateParts[0] $startParts[1]";
		    
		    $thisEvent = array('value' => $event['eventID'].'_'.$event['start'], 'text' => $event['eventName'].' - '.$startDate, 'date' => strtotime($startParts[0]));
		    $events[] = $thisEvent;
		    
		    if($event['repeatEvent']){
			    
			    if($event['repeatEvent']=='daily'){
				   
				    $eDate = $today;
				    while($eDate<$event['repeatEnd']){
					    $pDate = explode("-", $eDate);
					    $eDate = date("Y-m-d", mktime(0, 0, 0, $pDate[1], $pDate[2]+1, $pDate[0]));
					    $fDate = date("d/m/Y", mktime(0, 0, 0, $pDate[1], $pDate[2]+1, $pDate[0]));
					    $thisEvent = array('value' => $event['eventID'].'_'.$eDate.' '.$startParts[1], 'text' => $event['eventName'].' - '.$fDate.' '.$startParts[1], 'date' => strtotime($eDate));
					    $events[] = $thisEvent;
				    }
				    
			    }elseif($event['repeatEvent']=='weekly'){
				    
				    $eDate = $startParts[0];
				    while($eDate<$event['repeatEnd']){
					    $pDate = explode("-", $eDate);
					    $eDate = date("Y-m-d", mktime(0, 0, 0, $pDate[1], $pDate[2]+7, $pDate[0]));
					    $fDate = date("d/m/Y", mktime(0, 0, 0, $pDate[1], $pDate[2]+7, $pDate[0]));
					    $thisEvent = array('value' => $event['eventID'].'_'.$eDate.' '.$startParts[1], 'text' => $event['eventName'].' - '.$fDate.' '.$startParts[1], 'date' => strtotime($eDate));
					    $events[] = $thisEvent;
				    }
				    
			    }elseif($event['repeatEvent']=='weekdays'){
				    
				    $eDate = $startParts[0];
				    while($eDate<$event['repeatEnd']){
					    $pDate = explode("-", $eDate);
					    $eDay = date("N", mktime(0, 0, 0, $pDate[1], $pDate[2]+1, $pDate[0]));
					    
					    $eDate = date("Y-m-d", mktime(0, 0, 0, $pDate[1], $pDate[2]+1, $pDate[0]));
					    $fDate = date("d/m/Y", mktime(0, 0, 0, $pDate[1], $pDate[2]+1, $pDate[0]));
					    if($eDay<6){
						    $thisEvent = array('value' => $event['eventID'].'_'.$eDate.' '.$startParts[1], 'text' => $event['eventName'].' - '.$fDate.' '.$startParts[1], 'date' => strtotime($eDate));
						    $events[] = $thisEvent;
					    }
				    }
				    
			    }
			    
		    }
		    
	    }
	    
	    //** SORT THE ARRAY **//
	    array_multisort(array_column($events,'date'), SORT_ASC, SORT_NUMERIC, 
	    	array_keys($events), SORT_NUMERIC, SORT_ASC,$events);
	  	
	    $events = json_encode($events);
	    return $events;
		
	}

}
