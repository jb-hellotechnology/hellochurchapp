<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(hello_church_member_owner($_GET['id'])){
	header("location:/contacts");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Edit a Contact</h1>
	<section>
		<header>
			<h2>Update Contact Details</h2>
		</header>
		<?php hello_church_form('update_contact.html'); ?>
	</section>
	<h3>More Options</h3>
	<p><a href="/contacts/delete-contact/?id=<?= $_GET['id'] ?>">Delete contact</a></p>
</main>
<?php perch_layout('footer'); ?>