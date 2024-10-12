<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Settings</h1>
	<section>
		<header>
			<h2>Contact Details</h2>
		</header>
		<?php hello_church_form('update_church.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>