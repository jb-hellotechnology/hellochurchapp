<?php

require '../../../vendor/autoload.php';
include('../../../secrets.php');

perch_layout('header_public');

?>
<main class="flow public narrow training">
	<?php
	echo perch_get('church');
		//public_plan(perch_get('church'), perch_get('plan'));
	?>
</main>
<?php perch_layout('footer_public'); ?>