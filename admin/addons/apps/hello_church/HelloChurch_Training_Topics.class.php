<?php

class HelloChurch_Training_Topics extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_training_topics';
	protected $pk        = 'topicID';
	protected $singular_classname = 'HelloChurch_Training_Topic';

	protected $default_sort_column = 'topicID';

	public $static_fields = array('topicID', 'churchID', 'memberID', 'topicName');
    
    public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i><li><ul><ol>');
		}
		
		return $clean;
	    
    }

	public function topic($topicID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT * FROM perch3_hellochurch_training_topics WHERE topicID='".$topicID."'";    
		
	    $results = $this->db->get_row($sql);

	    return $results;
	    
    }
	
	public function topics($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
	    
	    $sql = "SELECT * FROM perch3_hellochurch_training_topics WHERE churchID='".$churchID."' ORDER BY topicName ASC";    
		
	    $results = $this->db->get_rows($sql);

	    return $results;
	    
    }
    
    public function check_owner($churchID, $topicID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_training_topics WHERE churchID='".$churchID."' AND topicID='".$topicID."'";
	    $results = $this->db->get_rows($sql);
	    if($results){
		    return true;
	    }else{
		    return false;
	    }
	    
    }

}
