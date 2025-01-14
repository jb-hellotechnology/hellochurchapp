<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow full">
	<h1>Calendar</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='event_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Event deleted</p>';
		}
	?>
	<section>
		<form method="get" class="search">
			<header>
				<h2>Calendar</h2>
			</header>
			<article>
			<?php hello_church_calendar(); ?>
			</article>
			<footer>
				<a href="/calendar/add-event" class="button primary">Add an Event</a>
			</footer>
		</form>
	</section>
</main>
<?php perch_layout('footer'); ?>