<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<h1>Add a Group</h1>
	<section>
		<header>
			<h2>Group Details</h2>
		</header>
		<?php hello_church_form('create_group.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>