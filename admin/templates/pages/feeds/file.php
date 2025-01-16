<?php
$file = hello_church_file(perch_get('fileID'));

$fileLocation = '../../../../hc_uploads/'.perch_get('churchID').'/'.$file['fileLocation'];

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($fileLocation));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($fileLocation));
ob_clean();
flush();
readfile($fileLocation);

?>