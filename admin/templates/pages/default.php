<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main>
	<p>Not Built Yet</p>
</main>
<?php perch_layout('footer'); ?>