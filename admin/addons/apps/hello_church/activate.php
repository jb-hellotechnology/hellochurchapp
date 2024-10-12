<?php
    // Prevent running directly:
    if (!defined('PERCH_DB_PREFIX')) exit;

    // Let's go
    $sql = "

        CREATE TABLE `__PREFIX__hellochurch_churches` (
          `churchID` int(10) NOT NULL AUTO_INCREMENT,
          `memberID` int(10) NOT NULL,
          `churchName` char(128) NOT NULL DEFAULT '',
          `churchAddress1` char(128) NOT NULL DEFAULT '',
          `churchAddress2` char(128) NOT NULL DEFAULT '',
          `churchCity` char(128) NOT NULL DEFAULT '',
          `churchCounty` char(128) NOT NULL DEFAULT '',
          `churchPostCode` char(128) NOT NULL DEFAULT '',
          `churchCountry` char(128) NOT NULL DEFAULT '',
          `churchPhone` char(128) NOT NULL DEFAULT '',
          `churchEmail` char(128) NOT NULL DEFAULT '',
          `churchWebsite` char(128) NOT NULL DEFAULT '',
          `churchFacebook` char(128) NOT NULL DEFAULT '',
          `churchInstagram` char(128) NOT NULL DEFAULT '',
          `churchX` char(128) NOT NULL DEFAULT '',
          `churchTikTok` char(128) NOT NULL DEFAULT '',
          PRIMARY KEY (`churchID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        
    ";

    $sql = str_replace('__PREFIX__', PERCH_DB_PREFIX, $sql);

    $statements = explode(';', $sql);
    foreach($statements as $statement) {
        $statement = trim($statement);
        if ($statement!='') $this->db->execute($statement);
    }


    $API = new PerchAPI(1.0, 'hello_church');

    $sql = 'SHOW TABLES LIKE "'.$this->table.'"';
    $result = $this->db->get_value($sql);

    return $result;

