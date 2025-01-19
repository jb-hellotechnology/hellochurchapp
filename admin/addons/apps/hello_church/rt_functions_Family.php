<?php
	
	/** LIST FAMILIES **/
	function hello_church_families(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = perch_member_get('churchID');

        $HelloChurchFamilies = new HelloChurch_Families($API);
        
        $families = $HelloChurchFamilies->families($churchID);
        
		echo '<article>
				<ul class="list">';
        
        foreach($families as $family){
	        $description = strip_tags($family['familyDescription']);
	        echo '<li>
	        		<div class="heading">
	        			<span class="material-symbols-outlined">family_restroom</span>
				        <h3><a href="/settings/families/edit-family?id='.$family['familyID'].'">'.$family['familyName'].'</a></h3>
						<p>'.$description.'</p>
					</div>
					<div class="functions">
						<a href="/settings/families/edit-family?id='.$family['familyID'].'" class="button secondary small">View</a>
					</div>
				</li>';
        }

        echo '</ul>
        	</article>';
	    
    }
    
    /** GET LIST OF FAMILIES FOR TAGIFY FIELD **/
    function hello_church_families_tagify(){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $Session = PerchMembers_Session::fetch();
	    
	    $churchID = perch_member_get('churchID');

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
    
    /** CHECK IF SIGNED IN USER IS OWNER OF FAMILY **/
    function hello_church_family_owner($familyID){
		
		$API  = new PerchAPI(1.0, 'hello_church');
		$HelloChurchFamilies = new HelloChurch_Families($API);
		
		$Session = PerchMembers_Session::fetch();
		
		$owner = $HelloChurchFamilies->check_owner(perch_member_get('churchID'), $familyID);
		if($owner==1){
		    return true;
	    }else{
		    return false;
	    }
		
	}
	
	/** LIST FAMILY MEMBERS **/
	function hello_church_family_members($contactID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
        
        $HelloChurchContacts = new HelloChurch_Contacts($API);
        
		$Session = PerchMembers_Session::fetch();
		
		$family = $HelloChurchContacts->family_members($contactID);
		
		if($family){
		
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
	
?>