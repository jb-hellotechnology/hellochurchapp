<?php

require '../../../vendor/autoload.php';
include('../../../secrets.php');

perch_layout('header_public');

?>
<main class="flow public narrow training">
	<?php
		public_session(perch_get('church'), perch_get('session'));
	?>
</main>
<?php perch_layout('footer_public'); ?>