<?php

class HelloChurch_Venue extends PerchAPI_Base
{
    protected $table  = 'hellochurch_venues';
    protected $pk     = 'venueID';

    public $static_fields = array('venueID', 'churchID', 'memberID', 'venueName', 'venueDescription');

}
