<?php

require '../../../vendor/autoload.php';

header('Content-type: text/calendar; charset=utf-8');

$church = hello_church_church_public(perch_get('churchID'));
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hellchurch.tech//hellochurch 1.0//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:<?= $church['churchName']?> - Hello Church
<?php
	ical_feed(perch_get('churchID'));
?>
END:VCALENDAR