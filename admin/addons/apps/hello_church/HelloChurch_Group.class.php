<?php

class HelloChurch_Group extends PerchAPI_Base
{
    protected $table  = 'hellochurch_groups';
    protected $pk     = 'groupID';

    public $static_fields = array('groupID', 'churchID', 'memberID', 'groupName', 'groupDescription', 'groupAutoAdd', 'groupProperites');

}
