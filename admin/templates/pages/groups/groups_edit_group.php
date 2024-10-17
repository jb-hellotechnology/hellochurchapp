<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_group_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Edit a Group</h1>
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
</main>
<?php perch_layout('footer'); ?>