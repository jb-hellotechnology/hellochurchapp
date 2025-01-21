<?php

require '../../../vendor/autoload.php';
include('../../../secrets.php');

send_link(strip_tags(addslashes($_POST['c'])), strip_tags(addslashes($_POST['e'])));

?>