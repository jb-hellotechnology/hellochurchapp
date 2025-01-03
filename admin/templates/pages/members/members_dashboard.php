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
	<ul class="cards align-left">
		<li>
			<div>
				<h3>Contacts</h3>
				<p>Add, edit and remove contacts, including contact details, notes, tags, family members and files.</p>
			</div>
			<a class="button" href="/contacts">Contacts</a>
		</li>
		<li>
			<div>
				<h3>Groups</h3>
				<p>Create and manage groups to support your church's operations and ministries.</p>
			</div>
			<a class="button" href="/groups">Groups</a>
		</li>
		<li>
			<div>
				<h3>Communication</h3>
				<p>Write and send emails to keep your church updated and informed. Easily share events and files.</p>
			</div>
			<a class="button" href="/communication">Communication</a>
		</li>
		<li>
			<div>
				<h3>Calendar</h3>
				<p>Manage recurring and one-off meetings and events. Define roles and create rotas with ease.</p>
			</div>
			<a class="button" href="/calendar">Calendar</a>
		</li>
		<li>
			<div>
				<h3>Documents</h3>
				<p>Upload and store documents of various kinds. Organise into folders for easy retrieval.</p>
			</div>
			<a class="button" href="/documents">Documents</a>
		</li>
		<li>
			<div>
				<h3>Media</h3>
				<p>Upload audio files into your library. Share online via your website or social media.</p>
			</div>
			<a class="button" href="/media">Media</a>
		</li>
		<li>
			<div>
				<h3>Settings</h3>
				<p>Configure various Hello Church settings and options to make it work well for you and your church.</p>
			</div>
			<a class="button" href="/settings">Settings</a>
		</li>
		<li>
			<div>
				<h3>Help</h3>
				<p>Watch videos and read articles which will help you understand how to use Hello Church.</p>
			</div>
			<a class="button" href="/help">Help</a>
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