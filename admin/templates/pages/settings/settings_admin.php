<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

$adminType = admin_type();

$admins = hello_church_admins(true);
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
	<h1>Administrators</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='admin_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Administrator deleted</p>';
		}
	?>
	<p class="alert error"><span class="material-symbols-outlined">error</span>Be careful, administrators have full read and write access to your church profile and data, so use this feature with care</p>
	<section>
		<header>
			<h2>Administrators</h2>
		</header>
		<?php hello_church_admins(false); ?>
		<footer>
			
		</footer>
	</section>
	<?php
		if(count($admins)<2){
	?>
	<section>
		<header>
			<h2>Create Administrator</h2>
		</header>
		<?php hello_church_form('create_admin.html'); ?>
	</section>
	<?php
		}
		}else{
			echo '<h1>Owner Access Only</h1><p>Profile administrators cannot access this page for security reasons.</p>';	
		}	
	?>
</main>
<?php perch_layout('footer'); ?>