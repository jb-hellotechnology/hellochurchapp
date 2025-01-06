<?php

class HelloChurch_Podcast extends PerchAPI_Base
{
    protected $table  = 'hellochurch_podcasts';
    protected $pk     = 'podcastID';

    public $static_fields = array('podcastID', 'churchID', 'podcastName', 'podcastDescription', 'podcastImage');

}
