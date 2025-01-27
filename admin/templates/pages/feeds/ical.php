<?php

require '../../../vendor/autoload.php';

header('Content-type: text/calendar; charset=utf-8');

$church = church_by_slug(perch_get('churchSlug'));

ical_feed($church['churchID']);
?>
