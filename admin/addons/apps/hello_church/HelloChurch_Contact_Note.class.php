<?php

class HelloChurch_Contact_Note extends PerchAPI_Base
{
    protected $table  = 'hellochurch_contact_notes';
    protected $pk     = 'noteID';

    public $static_fields = array('noteID', 'memberID', 'churchID', 'contactID', 'timestamp', 'note');

}
