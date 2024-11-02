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
	<h1>Edit a Speaker</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='speaker_deleted'){
			echo '<p class="alert success">Speaker successfully deleted.</p>';
		}
	?>
	<section>
		<header>
			<h2>Edit Speaker</h2>
		</header>
		<?php hello_church_form('update_speaker.html'); ?>
	</section>
	<div class="panel flow">
		<h3>More Options</h3>
		<p><a href="/settings/speakers/delete-speaker?id=<?= perch_get('id') ?>" class="warning">Delete speaker</a></p>
	</div>
</main>
<?php perch_layout('footer'); ?>