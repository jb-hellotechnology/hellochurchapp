<?php

class HelloChurch_Series extends PerchAPI_Base
{
    protected $table  = 'hellochurch_series';
    protected $pk     = 'seriesID';

    public $static_fields = array('seriesID', 'churchID', 'memberID', 'seriesName', 'seriesDescription');

}
