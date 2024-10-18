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
					<div>
						<input type="text" name="q" id="q" placeholder="Add Member" value="<?= perch_get('q') ?>" onkeyup="searchFamilyMembers();" autocomplete="off" data-member-id="<?= perch_get('id') ?>" />
						<div class="results">
							
						</div>
					</div>
				</header>
				<article>
					<?php hello_church_family_members(perch_get('id')); ?>
				</article>
				<?php hello_church_form('add_family_member.html'); ?>
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
		</div>
	</div>
	
	<div class="panel flow">
		<h3>More Options</h3>
		<p><a href="/contacts/delete-contact/?id=<?= perch_get('id') ?>" class="warning">Delete contact</a></p>
	</div>
</main>
<?php perch_layout('footer'); ?>