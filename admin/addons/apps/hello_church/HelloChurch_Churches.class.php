<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class HelloChurch_Churches extends PerchAPI_Factory
{
    protected $table     = 'hellochurch_churches';
	protected $pk        = 'churchID';
	protected $singular_classname = 'HelloChurch_Church';

	protected $default_sort_column = 'churchName';

	public $static_fields = array('churchID', 'memberID', 'churchName', 'churchSlug', 'churchAddress1', 'churchAddress2', 'churchCity', 'churchCounty', 'churchPostCode', 'churchCountry', 'churchPhone', 'churchEmail', 'churchWebsite', 'churchFacebook', 'churchInstagram', 'churchX', 'churchTikTok', 'churchProperties');

    public $dynamic_fields_column = 'churchProperties';
    
    public function church($churchID){
	    
	    $API  = new PerchAPI(1.0, 'hello_church');
		
		$sql = "SELECT * FROM perch3_hellochurch_churches WHERE memberID='".$churchID."'";
	    $results = $this->db->get_row($sql);
	    return $results;
	    
	}
    
    public function church_valid($data){
	    
	    return true;
	    
    }

}
