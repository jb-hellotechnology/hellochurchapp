<?php

class HelloChurch_Audio extends PerchAPI_Base
{
    protected $table  = 'hellochurch_audio';
    protected $pk     = 'audioID';

    public $static_fields = array('audioID', 'churchID', 'memberID', 'audioName', 'audioDate', 'audioDescription', 'audioSeries', 'audioSpeaker', 'audioBible', 'audioFile');

}
