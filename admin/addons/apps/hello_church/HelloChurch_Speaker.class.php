<?php

class HelloChurch_Speaker extends PerchAPI_Base
{
    protected $table  = 'hellochurch_speakers';
    protected $pk     = 'speakerID';

    public $static_fields = array('speakerID', 'churchID', 'memberID', 'speakerName');

}
