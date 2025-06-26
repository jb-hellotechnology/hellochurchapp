<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

$contacts = hello_church_contacts_count();
?>
<main class="flow">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
		if($contacts>=200){
	?>
	<h1>Allowance Reached</h1>
	<p>We currently only permit 200 contacts per church profile. If you need more than this please contact <a href="mailto:support@churchplanner.co.uk">support@churchplanner.co.uk</a> to discuss your requirements.</p>
	<?php
		}else{
	?>
	<h1>Add a Contact</h1>
	<section>
		<header>
			<h2>Contact Details</h2>
		</header>
		<?php hello_church_form('create_contact.html'); ?>
	</section>
	<?php
		}
	?>
</main>
<?php perch_layout('footer'); ?>