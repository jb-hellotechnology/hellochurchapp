<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Speakers</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='speaker_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Speaker deleted</p>';
		}
	?>
	<section>
		<header>
			<h2>Speakers</h2>
		</header>
		<?php hello_church_speakers(); ?>
		<footer>
			
		</footer>
	</section>
	<section>
		<header>
			<h2>Create Speaker</h2>
		</header>
		<?php hello_church_form('create_speaker.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>