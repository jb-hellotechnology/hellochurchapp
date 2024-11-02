<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Seriess extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_series';
	protected $pk        = 'seriesID';
	protected $singular_classname = 'HelloChurch_Series';

	protected $default_sort_column = 'seriesName';

	public $static_fields = array('seriesID', 'churchID', 'memberID', 'seriesName', 'seriesDescription');
    
    public function role_valid($data){
	    
	    return true;
	    
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
    
    public function check_owner($memberID, $seriesID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_series WHERE memberID='".$memberID."' AND seriesID='".$seriesID."'";
	    $results = $this->db->get_rows($sql);
	    return count($results);
	    
    }
    
    public function series_byName($series, $churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_series WHERE churchID='".$churchID."' AND seriesName='".$series."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
    }

}
