<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow full">
	<h1>Contacts</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='contact_deleted'){
			echo '<p class="alert success">Contact successfully deleted.</p>';
		}
	?>
	<section>
		<header>
			<h2>Contacts</h2>
			<form method="get" class="search">
				<select>
					<option>Filter by Tag</option>
				</select>
				<input type="text" name="q" placeholder="Search" />
			</form>
		</header>
		<?php hello_church_contacts(); ?>
		<footer>
			<a class="button primary" href="/contacts/add-contact">Add a Contact</a>
		</footer>
	</section>
</main>
<?php perch_layout('footer'); ?>