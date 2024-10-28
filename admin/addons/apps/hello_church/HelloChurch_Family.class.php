<?php

class HelloChurch_Family extends PerchAPI_Base
{
    protected $table  = 'hellochurch_families';
    protected $pk     = 'familyID';

    public $static_fields = array('familyID', 'churchID', 'memberID', 'familyName', 'familyDescription');

}
