<?php

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

?>
<main class="flow full">
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
	</ul>
</main>
<?php perch_layout('footer'); ?>