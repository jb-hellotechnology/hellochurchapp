<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Families</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='family_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Family deleted</p>';
		}
	?>
	<section>
		<header>
			<h2>Families</h2>
		</header>
		<?php hello_church_families(); ?>
		<footer>
			
		</footer>
	</section>
	<section>
		<header>
			<h2>Create Family</h2>
		</header>
		<?php hello_church_form('create_family.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>