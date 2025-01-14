<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Series</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='series_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Series deleted</p>';
		}
	?>
	<section>
		<header>
			<h2>Series</h2>
		</header>
		<?php hello_church_series(); ?>
		<footer>
			
		</footer>
	</section>
	<section>
		<header>
			<h2>Create Series</h2>
		</header>
		<?php hello_church_form('create_series.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>