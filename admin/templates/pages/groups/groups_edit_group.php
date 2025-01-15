<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_group_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

$name = hello_church_group_get(perch_get('id'), 'groupName');
$description = hello_church_group_get(perch_get('id'), 'groupDescription');

perch_layout('header');
?>
<main class="flow">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1><?= $name ?></h1>
	<?= $description ?>
	<section>
		<header>
			<h2>Members</h2>
			<div>
				<input type="text" name="q" id="q" placeholder="Add Member" value="<?= perch_get('q') ?>" onkeyup="searchContacts();" autocomplete="off" data-group-id="<?= perch_get('id') ?>" />
				<div class="results">
					
				</div>
			</div>
		</header>
		<article>
			<?php hello_church_group_members(perch_get('id')); ?>
		</article>
		<footer>
			
		</footer>
	</section>
	<section>
		<header>
			<h2>Group Details</h2>
		</header>
		<?php hello_church_form('update_group.html'); ?>
	</section>
	<section>
		<header>
			<h2>Upload File</h2>
		</header>
		<article>
			<?php hello_church_files(0, 0, perch_get('id'), 0, 0); ?>
			<input id="file" type="file" name="file" />
			<input type="hidden" name="folderID" id="folderID" value="0" />
			<input type="hidden" name="contactID" id="contactID" value="0" />
			<input type="hidden" name="groupID" id="groupID" value="<?= perch_get('id') ?>" />
			<input type="hidden" name="eventID" id="eventID" value="0" />
			<input type="hidden" name="eventDate" id="eventDate" value="0000-00-00" />
		</article>
		<footer>
			<button id="upload" class="button primary">Upload</button>
		</footer>
	</section>
	<div class="panel flow">
		<h3>More Options</h3>
		<p><a href="/groups/delete-group/?id=<?= perch_get('id') ?>" class="warning">Delete group</a></p>
	</div>
</main>
<?php perch_layout('footer'); ?>