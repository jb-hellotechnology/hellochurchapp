<?php

class HelloChurch_Training_Session extends PerchAPI_Base
{
    protected $table  = 'hellochurch_training_sessions';
    protected $pk     = 'sessionID';

    public $static_fields = array('topicID', 'churchID', 'memberID', 'topicID', 'sessionName');

}
