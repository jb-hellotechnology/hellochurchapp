<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_member_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Add a Note</h1>
	<p><a href="/contacts/edit-contact?id=<?= perch_get('id') ?>" class="button secondary small">Back</a></p>
	<section>
		<header>
			<h2>Add Note</h2>
		</header>
		<?php hello_church_form('add_note.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>