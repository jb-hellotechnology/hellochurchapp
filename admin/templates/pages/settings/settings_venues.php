<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
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
	<h1>Venues</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='venue_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Venue deleted</p>';
		}
	?>
	<section>
		<header>
			<h2>Venues</h2>
		</header>
		<?php hello_church_venues(); ?>
		<footer>
			
		</footer>
	</section>
	<section>
		<header>
			<h2>Create Venue</h2>
		</header>
		<?php hello_church_form('create_venue.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>