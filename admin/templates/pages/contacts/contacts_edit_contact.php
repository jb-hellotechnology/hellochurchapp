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

$name = hello_church_contact_get(perch_get('id'), 'contactFirstName').' '.hello_church_contact_get(perch_get('id'), 'contactLastName');
?>
<main class="flow full">
	<h1><?= $name ?></h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='note_deleted'){
			echo '<p class="alert success">Note successfully deleted.</p>';
		}elseif($_GET['msg']=='note_created'){
			echo '<p class="alert success">Note successfully created.</p>';
		}
	?>
	<div class="section-grid">
		<div>
			<section>
				<header>
					<h2>Update Contact Details</h2>
				</header>
				<?php hello_church_form('update_contact.html'); ?>
			</section>
		</div>
		<div>
			<section>
				<header>
					<h2>Future Responsibilities</h2>
				</header>
				<?php hello_church_contact_responsibilities(perch_get('id')); ?>
				<footer>
					<?php hello_church_form('download_rota_contact.html'); ?>
					<a href="/calendar" class="button primary">Manage Calendar</a>
				</footer>
			</section>
			<section>
				<header>
					<h2>Groups</h2>
				</header>
				<?php hello_church_contact_groups(perch_get('id')); ?>
				<footer>
					<a href="/groups" class="button primary">Manage Groups</a>
				</footer>
			</section>
			<section>
				<header>
					<h2>Family Members</h2>
				</header>
				<article>
					<?php hello_church_family_members(perch_get('id')); ?>
				</article>
			</section>
			<section>
				<header>
					<h2>Notes</h2>
				</header>
				<?php hello_church_contact_notes(perch_get('id')); ?>
				<footer>
					<a href="/contacts/add-note?id=<?= perch_get('id') ?>" class="button primary">Add Note</a>
				</footer>
			</section>
			<section>
				<header>
					<h2>Upload File</h2>
				</header>
				<article>
					<?php hello_church_files(0, perch_get('id'), 0, 0, 0); ?>
					<input id="file" type="file" name="file" />
					<input type="hidden" name="folderID" id="folderID" value="0" />
					<input type="hidden" name="contactID" id="contactID" value="<?= perch_get('id') ?>" />
					<input type="hidden" name="groupID" id="groupID" value="0" />
					<input type="hidden" name="eventID" id="eventID" value="0" />
					<input type="hidden" name="eventDate" id="eventDate" value="0000-00-00" />
				</article>
				<footer>
					<button id="upload" class="button primary">Upload</button>
				</footer>
			</section>
		</div>
	</div>
	
	<div class="panel flow">
		<h3>More Options</h3>
		<p><a href="/contacts/delete-contact/?id=<?= perch_get('id') ?>" class="warning">Delete contact</a></p>
	</div>
</main>
<?php perch_layout('footer'); ?>