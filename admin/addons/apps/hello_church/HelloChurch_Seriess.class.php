<?php

class HelloChurch_Seriess extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_series';
	protected $pk        = 'seriesID';
	protected $singular_classname = 'HelloChurch_Series';

	protected $default_sort_column = 'seriesName';

	public $static_fields = array('seriesID', 'churchID', 'memberID', 'seriesName', 'seriesDescription');
    
    public function valid($data){
	    
	    $clean = array();
	
		foreach($data as $key => $value){
			$clean[$key] = strip_tags($value, '<p><a><h2><h3><em><strong><i>');
		}
		
		return $clean;
	    
    }
    
	public function series($seriesID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_series WHERE seriesID='".$seriesID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	     
	}
    
    public function seriess($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_series WHERE churchID='".$churchID."' ORDER BY seriesName ASC";
	    $results = $this->db->get_rows($sql);
	    return $results;
	    
    }
    
    public function check_owner($churchID, $seriesID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_series WHERE churchID='".$churchID."' AND seriesID='".$seriesID."'";
	    $results = $this->db->get_rows($sql);
	    
	    if($results){
		    return true;
	    }else{
		    return false;
	    }
	    
    }
    
    public function series_byName($series, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_series WHERE churchID='".$churchID."' AND seriesName='".$series."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }

}
