<?php

class HelloChurch_Event extends PerchAPI_Base
{
    protected $table  = 'hellochurch_events';
    protected $pk     = 'eventID';

    public $static_fields = array('eventID', 'churchID', 'memberID', 'eventName', 'eventDescription', 'allDay', 'start', 'end', 'repeatEvent', 'repeatEnd', 'roles');

}
