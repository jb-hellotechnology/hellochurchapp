<?php

require '../../../vendor/autoload.php';

if(perch_member_get('churchID')>0){
	header("location:/dashboard");
}else{
	header("location:/switch");
}
?>