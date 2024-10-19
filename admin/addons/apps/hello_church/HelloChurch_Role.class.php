<?php

class HelloChurch_Role extends PerchAPI_Base
{
    protected $table  = 'hellochurch_roles';
    protected $pk     = 'roleID';

    public $static_fields = array('roleID', 'churchID', 'memberID', 'roleName', 'roleDescription');

}
