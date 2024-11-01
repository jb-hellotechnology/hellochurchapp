<?php

class HelloChurch_Folder extends PerchAPI_Base
{
    protected $table  = 'hellochurch_folders';
    protected $pk     = 'folderID';

    public $static_fields = array('folderID', 'churchID', 'memberID', 'folderName', 'folderParent');

}
