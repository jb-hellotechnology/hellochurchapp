<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_series_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Delete a Series</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='series_deleted'){
			echo '<p class="alert success">Series successfully deleted.</p>';
		}
	?>
	<section>
		<header>
			<h2>Delete Series</h2>
		</header>
		<?php hello_church_form('delete_series.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>