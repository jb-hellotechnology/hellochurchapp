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
	<h1>Add an Event</h1>
	<section>
		<header>
			<h2>Event Details</h2>
		</header>
		<?php hello_church_form('create_event.html'); ?>
	</section>
</main>
<?php perch_layout('footer'); ?>