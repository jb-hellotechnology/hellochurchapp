<?php

require '../../../vendor/autoload.php';

?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hellchurch.tech//hellochurch 1.0//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
<?php
	ical_feed(perch_get('churchID'));
?>
END:VCALENDAR