<?php

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

$church = hello_church_church(true);

?>
<main class="flow full">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Settings</h1>
	<ul class="cards menu">
		<li>
			<div>
				<a href="/settings/church">
					<span class="material-symbols-outlined">church</span>
					<h3>Church</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/settings/subscription">
					<span class="material-symbols-outlined">currency_pound</span>
					<h3>Subscription</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/settings/roles">
					<span class="material-symbols-outlined">group</span>
					<h3>Roles</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/settings/venues">
					<span class="material-symbols-outlined">home_work</span>
					<h3>Venues</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/settings/families">
					<span class="material-symbols-outlined">family_restroom</span>
					<h3>Families</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/settings/speakers">
					<span class="material-symbols-outlined">interpreter_mode</span>
					<h3>Speakers</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/settings/series">
					<span class="material-symbols-outlined">collections_bookmark</span>
					<h3>Series</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/settings/podcast">
					<span class="material-symbols-outlined">headphones</span>
					<h3>Podcast</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/settings/admin">
					<span class="material-symbols-outlined">shield_person</span>
					<h3>Administrators</h3>
				</a>
			</div>
		</li>
	</ul>
	<h2>Feeds</h2>
	<div class="panel">
		<h3>iCal Feed</h3>
		<p class="monospace">https://app.churchplanner.co.uk/feed/<?= $church['churchSlug'] ?>/ical.ics
		<h3>Podcast RSS</h3>
		<p class="monospace">https://app.churchplanner.co.uk/feed/<?= $church['churchSlug'] ?>/podcast.rss
	</div>
</main>
<?php perch_layout('footer'); ?>