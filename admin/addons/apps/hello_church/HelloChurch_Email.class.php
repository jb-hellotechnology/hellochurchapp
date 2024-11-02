<?php

class HelloChurch_Email extends PerchAPI_Base
{
    protected $table  = 'hellochurch_emails';
    protected $pk     = 'emailID';

    public $static_fields = array('emailID', 'churchID', 'memberID', 'emailSubject', 'emailTo', 'emailContent');

}
