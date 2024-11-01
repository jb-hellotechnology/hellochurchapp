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
$start = hello_church_calendar_get(perch_get('id'), 'start');
$pTime = explode(" ", $start);
$time = $pTime[1];
$pDates = explode("-", perch_get('date'));
$date = "$pDates[2]/$pDates[1]/$pDates[0]";

perch_layout('header');
?>
<main class="flow full">
	<h1 class="with-button"><span><?= $name ?> <time><?= $date ?> <?= $time ?></time></span><a class="button primary" href="/calendar/edit-event-plan?id=<?= perch_get('id') ?>&date=<?= perch_get('date') ?>">Plan<span class="material-symbols-outlined">category</span></a></h1>
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