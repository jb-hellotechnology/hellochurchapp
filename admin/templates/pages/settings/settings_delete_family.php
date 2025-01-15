<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_family_owner(perch_get('id'))){
	perch_member_log_out();
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
	<h1>Delete a Family</h1>
	<section>
		<header>
			<h2>Delete Family</h2>
		</header>
		<?php hello_church_form('delete_family.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>