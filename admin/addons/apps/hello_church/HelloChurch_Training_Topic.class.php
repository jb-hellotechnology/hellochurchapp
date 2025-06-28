<?php

class HelloChurch_Training_Topic extends PerchAPI_Base
{
    protected $table  = 'hellochurch_training_topics';
    protected $pk     = 'topicID';

    public $static_fields = array('topicID', 'churchID', 'memberID', 'topicName');

}
