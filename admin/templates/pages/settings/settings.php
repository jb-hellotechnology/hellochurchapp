<?php

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

?>
<main class="flow full">
	<h1>Settings</h1>
	<ul class="cards align-left">
		<li>
			<div>
				<h3>Church</h3>
				<p>Manage your church or organisation's contact details.</p>
			</div>
			<a class="button" href="/settings/church">Church</a>
		</li>
		<li>
			<div>
				<h3>Subscription</h3>
				<p>Manage your subscription, edit your payment details and access invoices.</p>
			</div>
			<a class="button" href="/settings/subscription">Subscription</a>
		</li>
		<li>
			<div>
				<h3>Roles</h3>
				<p>Define roles which exist within your organisation. These are used to help manage events and rotas.</p>
			</div>
			<a class="button" href="/settings/roles">Roles</a>
		</li>
		<li>
			<div>
				<h3>Families</h3>
				<p>Create family groups to which individual contacts can be assigned.</p>
			</div>
			<a class="button" href="/settings/families">Families</a>
		</li>
		<li>
			<div>
				<h3>Speakers</h3>
				<p>Manage your speaker profiles. These are used when adding audio to your media library.</p>
			</div>
			<a class="button" href="/settings/speakers">Speakers</a>
		</li>
		<li>
			<div>
				<h3>Series</h3>
				<p>Define series titles and descriptions for your media library.</p>
			</div>
			<a class="button" href="/settings/series">Series</a>
		</li>
	</ul>
</main>
<?php perch_layout('footer'); ?>