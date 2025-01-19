<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_admin_owner(perch_get('id'))){
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
	<h1>Delete an Administrator</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='admin_deleted'){
			echo '<p class="alert success">Administrator successfully deleted.</p>';
		}
	?>
	<section>
		<header>
			<h2>Delete Administrator</h2>
		</header>
		<?php hello_church_form('delete_admin.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>