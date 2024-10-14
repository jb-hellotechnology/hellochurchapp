<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Import Contacts</h1>
	<section>
		<header>
			<h2>Import Contacts</h2>
		</header>
		<?php hello_church_form('import_contacts.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>