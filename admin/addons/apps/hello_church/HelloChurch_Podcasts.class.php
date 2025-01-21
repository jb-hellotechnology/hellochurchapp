<?php

class HelloChurch_Podcasts extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_podcasts';
	protected $pk        = 'podcastID';
	protected $singular_classname = 'HelloChurch_Podcast';

	protected $default_sort_column = 'podcastName';

	public $static_fields = array('podcastID', 'churchID', 'podcastName', 'podcastDescription', 'podcastImage');
	
	public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i>');
		}
		
		return $clean;
	    
    }
    
	public function podcast($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_podcasts WHERE churchID='".$churchID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}

}
