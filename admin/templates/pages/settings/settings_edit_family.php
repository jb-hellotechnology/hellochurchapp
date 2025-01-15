<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_family_owner(perch_get('id'))){
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
	<h1>Edit a Family</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='role_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Family deleted</p>';
		}
	?>
	<section>
		<header>
			<h2>Edit Family</h2>
		</header>
		<?php hello_church_form('update_family.html'); ?>
	</section>
	<div class="panel flow">
		<h3>More Options</h3>
		<p><a href="/settings/families/delete-family?id=<?= perch_get('id') ?>" class="warning">Delete family</a></p>
	</div>
</main>
<?php perch_layout('footer'); ?>