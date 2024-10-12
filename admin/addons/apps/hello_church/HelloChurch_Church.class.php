<?php

class HelloChurch_Church extends PerchAPI_Base
{
    protected $table  = 'hellochurch_churches';
    protected $pk     = 'churchID';

    public $static_fields = array('churchID', 'memberID', 'churchName', 'churchSlug', 'churchAddress1', 'churchAddress2', 'churchCity', 'churchCounty', 'churchPostCode', 'churchCountry', 'churchPhone', 'churchEmail', 'churchWebsite', 'churchFacebook', 'churchInstagram', 'churchX', 'churchTikTok', 'churchProperites');

}
