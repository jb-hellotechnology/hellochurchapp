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

PerchSystem::set_var('startDate', perch_get('date'));

perch_layout('header');
?>
<main class="flow full">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1 class="with-button"><span><?= $name ?> <time><?= $date ?> <?= $time ?></time></span><a class="button primary" href="/calendar/edit-event-plan?id=<?= perch_get('id') ?>&date=<?= perch_get('date') ?>">Plan</a></h1>
	<?= $description ?>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='contact_added'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Contact added to role</p>';
		}
		if($_GET['msg']=='contact_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Contact removed from role</p>';
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
			<section>
				<header>
					<h2>Upload File</h2>
				</header>
				<article>
					<?php hello_church_files(0, 0, 0, perch_get('id'), perch_get('date')); ?>
					<input id="file" type="file" name="file" />
					<input type="hidden" name="folderID" id="folderID" value="0" />
					<input type="hidden" name="contactID" id="contactID" value="0" />
					<input type="hidden" name="groupID" id="groupID" value="0" />
					<input type="hidden" name="eventID" id="eventID" value="<?= perch_get('id') ?>" />
					<input type="hidden" name="eventDate" id="eventDate" value="<?= perch_get('date') ?>" />
				</article>
				<footer>
					<button id="upload" class="button primary">Upload</button>
				</footer>
			</section>
		</div>
	</div>
	<div class="panel flow">
		<h3>More Options</h3>
		<p><a href="/calendar/delete-event/?id=<?= perch_get('id') ?>" class="warning">Delete event</a></p>
	</div>
</main>
<?php perch_layout('footer'); ?>