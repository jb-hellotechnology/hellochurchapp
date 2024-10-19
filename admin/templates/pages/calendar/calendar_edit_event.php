<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_event_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

$name = hello_church_calendar_get(perch_get('id'), 'eventName');
$description = hello_church_calendar_get(perch_get('id'), 'eventDescription');

perch_layout('header');
?>
<main class="flow full">
	<h1><?= $name ?></h1>
	<?= $description ?>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='contact_added'){
			echo '<p class="alert success">Contact added to role.</p>';
		}	
	?>
	<div class="section-grid">
		<div>
			<section>
				<header>
					<h2>Event Details</h2>
				</header>
				<?php hello_church_form('update_event.html'); ?>
			</section>
		</div>
		<div>
			<?php hello_church_event_roles(perch_get('id')); ?>
		</div>
	</div>
	<div class="panel flow">
		<h3>More Options</h3>
		<p><a href="/calendar/delete-event/?id=<?= perch_get('id') ?>" class="warning">Delete event</a></p>
	</div>
</main>
<?php perch_layout('footer'); ?>