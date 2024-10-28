<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_role_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Delete a Family</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='role_deleted'){
			echo '<p class="alert success">Role successfully deleted.</p>';
		}
	?>
	<section>
		<header>
			<h2>Delete Role</h2>
		</header>
		<?php hello_church_form('delete_role.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>