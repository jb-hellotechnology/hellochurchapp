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
	<h1>Export Contacts</h1>
	<section>
		<form method="get" class="search">
			<header>
				<h2>Export Contacts</h2>
			</header>
			<?php hello_church_form('export_contacts.html'); ?>
		</form>
	</section>
</main>
<?php perch_layout('footer'); ?>