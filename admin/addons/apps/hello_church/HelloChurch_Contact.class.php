<?php

class HelloChurch_Contact extends PerchAPI_Base
{
    protected $table  = 'hellochurch_contacts';
    protected $pk     = 'contactID';

    public $static_fields = array('contactID', 'churchID', 'memberID', 'contactFirstName', 'contactLastName', 'contactEmail', 'contactPhone', 'contactAddress1', 'contactAddress2', 'contactCity', 'contactCounty', 'contactPostCode', 'contactCountry', 'contactAcceptEmail', 'contactAcceptSMS', 'contactTags', 'contactProperites');

}
