<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

/*
if(hello_church_member_owner($_GET['id'])){
	header("location:/contacts");
}
*/

perch_layout('header');
?>
<main class="flow">
	<h1>Delete a Note</h1>
	<section>
		<header>
			<h2>Delete Note</h2>
		</header>
		<?php hello_church_form('delete_note.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>