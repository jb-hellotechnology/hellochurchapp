<?php
$church = church_by_slug(perch_get('churchSlug'));

$img = '../../../../hc_uploads/'.$church['churchID'].'/podcast/podcast.jpg';
header('Content-Type: image/jpeg');
readfile($img);

?>