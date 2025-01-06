<?php

require '../../../vendor/autoload.php';

header('Content-type: text/calendar; charset=utf-8');

$church = church_by_slug(perch_get('churchSlug'));
print_r($church);
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hellchurch.tech//hellochurch 1.0//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:<?= $church['churchName']?> - Hello Church
<?php
	ical_feed($church['churchID']);
?>
END:VCALENDAR