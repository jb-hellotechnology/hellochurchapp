<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_speaker_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Delete a Speaker</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='speaker_deleted'){
			echo '<p class="alert success">Speaker successfully deleted.</p>';
		}
	?>
	<section>
		<header>
			<h2>Delete Speaker</h2>
		</header>
		<?php hello_church_form('delete_speaker.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>