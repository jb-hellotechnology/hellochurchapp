<?php

class HelloChurch_Admin extends PerchAPI_Base
{
    protected $table  = 'hellochurch_admins';
    protected $pk     = 'adminID';

    public $static_fields = array('adminID', 'churchID', 'memberID', 'adminEmail', 'adminCode');

}
