<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_member_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

if(!hello_church_note_owner(perch_get('noteID'))){
	perch_member_log_out();
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Delete a Note</h1>
	<p><a href="/contacts/edit-note?id=<?= perch_get('id') ?>&noteID=<?= perch_get('noteID') ?>" class="button secondary small">Back</a></p>
	<section>
		<header>
			<h2>Delete Note</h2>
		</header>
		<?php hello_church_form('delete_note.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>