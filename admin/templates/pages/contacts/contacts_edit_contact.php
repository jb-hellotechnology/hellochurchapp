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
<main class="flow full">
	<h1>Edit a Contact</h1>
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
				<article>
					<?php hello_church_contact_notes(perch_get('id')); ?>
					<h3>Add a Note</h3>
					<?php hello_church_form('add_note.html'); ?>
				</article>
			</section>
		</div>
	</div>
	
	<div class="panel flow">
		<h3>More Options</h3>
		<p><a href="/contacts/delete-contact/?id=<?= $_GET['id'] ?>" class="warning">Delete contact</a></p>
	</div>
</main>
<?php perch_layout('footer'); ?>