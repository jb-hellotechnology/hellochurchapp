<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_member_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

PerchSystem::set_var('contactID', perch_get('id'));

perch_layout('header');
?>
<main class="flow">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Export a Contact</h1>
	<section>
		<header>
			<h2>Export Contact</h2>
		</header>
		<?php hello_church_form('export_contact.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>