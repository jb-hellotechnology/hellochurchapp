<?php
$image = hello_church_file(perch_get('fileID'));

$img = '../../../../hc_uploads/'.perch_get('churchID').'/'.$image['fileLocation'];
header('Content-Type: image/jpeg');
readfile($img);

?>