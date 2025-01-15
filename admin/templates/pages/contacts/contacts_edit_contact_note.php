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
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Edit a Note</h1>
	<p><a href="/contacts/edit-contact?id=<?= perch_get('id') ?>" class="button secondary small">Back</a></p>
	<section>
		<header>
			<h2>Update Note</h2>
		</header>
		<?php hello_church_form('update_note.html'); ?>
	</section>
	<div class="panel flow">
		<h3>More Options</h3>
		<p><a href="/contacts/delete-note?id=<?= perch_get('id') ?>&noteID=<?= perch_get('noteID') ?>"" class="warning">Delete note</a></p>
	</div>
</main>
<?php perch_layout('footer'); ?>