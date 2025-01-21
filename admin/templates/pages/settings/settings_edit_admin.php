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
$adminType = admin_type();
?>
<main class="flow">
	<?php
		if($adminType=='owner'){
	?>
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Edit an Administrator</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='admin_deleted'){
			echo '<p class="alert success">Administrator successfully deleted.</p>';
		}
	?>
	<section>
		<header>
			<h2>Edit Administrator</h2>
		</header>
		<?php hello_church_form('update_admin.html'); ?>
	</section>
	<div class="panel flow">
		<h3>More Options</h3>
		<p><a href="/settings/admin/delete-admin?id=<?= perch_get('id') ?>" class="warning">Delete administrator</a></p>
	</div>
	<?php
		}else{
			echo '<h1>Owner Access Only</h1><p>Profile administrators cannot access this page for security reasons.</p>';	
		}	
	?>
</main>
<?php perch_layout('footer'); ?>