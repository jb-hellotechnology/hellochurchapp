<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_member_owner(perch_get('id'))){
	header("location:/contacts");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Edit a Note</h1>
	<section>
		<header>
			<h2>Update Note</h2>
		</header>
		<?php hello_church_form('update_note.html'); ?>
	</section>
	<h3>More Options</h3>
	<p><a href="/contacts/delete-note?id=<?= $_GET['noteID'] ?>">Delete note</a></p>
</main>
<?php perch_layout('footer'); ?>