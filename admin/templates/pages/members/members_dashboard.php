<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="full">
	<h1>Dashboard</h1>
	<section class="no-shadow">
	<ul class="cards menu">
		<li>
			<div>
				<a href="/contacts">
					<span class="material-symbols-outlined">contacts</span>
					<h3>Contacts</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/groups">
					<span class="material-symbols-outlined">groups</span>
					<h3>Groups</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/communication">
					<span class="material-symbols-outlined">chat</span>
					<h3>Communication</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/calendar">
					<span class="material-symbols-outlined">calendar_today</span>
					<h3>Calendar</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/documents">
					<span class="material-symbols-outlined">folder</span>
					<h3>Documents</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/media">
					<span class="material-symbols-outlined">perm_media</span>
					<h3>Media</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/settings">
					<span class="material-symbols-outlined">settings</span>
					<h3>Settings</h3>
				</a>
			</div>
		</li>
		<li>
			<div>
				<a href="/help">
					<span class="material-symbols-outlined">help</span>
					<h3>Help</h3>
				</a>
			</div>
		</li>
	</ul>
	</section>
	<section>
		<form method="get" class="search">
			<header>
				<h2>Recently Added Contacts</h2>
			</header>
			<article>
			<?php hello_church_recent_contacts(); ?>
			</article>
		</form>
	</section>
</main>
<?php perch_layout('footer'); ?>