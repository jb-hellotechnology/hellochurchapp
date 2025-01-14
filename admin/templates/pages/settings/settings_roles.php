<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Roles</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='role_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Role deleted</p>';
		}
	?>
	<section>
		<header>
			<h2>Roles</h2>
		</header>
		<?php hello_church_roles(); ?>
		<footer>
			
		</footer>
	</section>
	<section>
		<header>
			<h2>Create Role</h2>
		</header>
		<?php hello_church_form('create_role.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>