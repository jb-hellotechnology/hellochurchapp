<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_member_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Delete a Contact</h1>
	<section>
		<header>
			<h2>Delete Contact</h2>
		</header>
		<?php hello_church_form('delete_contact.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>