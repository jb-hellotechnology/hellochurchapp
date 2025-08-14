<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_member_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

perch_layout('header');

$name = hello_church_contact_get(perch_get('id'), 'contactFirstName').' '.hello_church_contact_get(perch_get('id'), 'contactLastName');

$lat = hello_church_contact_get(perch_get('id'), 'contactLat');
$lng = hello_church_contact_get(perch_get('id'), 'contactLng');
?>
<main class="flow full">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1><?= $name ?></h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='note_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Note deleted</p>';
		}elseif($_GET['msg']=='note_created'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Note created</p>';
		}
	?>
	<div class="section-grid">
		<div>
			<section>
				<header>
					<h2>Update Contact Details</h2>
				</header>
				<?php hello_church_form('update_contact.html'); ?>
			</section>
		</div>
		<div>
			<section>
				<header>
					<h2>Future Responsibilities</h2>
				</header>
				<?php hello_church_contact_responsibilities(perch_get('id')); ?>
				<footer>
					<?php hello_church_form('download_rota_contact.html'); ?>
					<a href="/calendar" class="button primary">Manage Calendar</a>
				</footer>
			</section>
			<?php if($lat){ ?>
			<section>
				<div id="map" style="width: 100%; height: 400px;"></div>
				<script>
				
					const map = L.map('map').setView([<?= $lat;  ?>, <?= $lng; ?>], 13);
				
					const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
						maxZoom: 19,
						attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
					}).addTo(map);
				
					const marker = L.marker([<?= $lat;  ?>, <?= $lng; ?>]).addTo(map);
				
					function onMapClick(e) {
						popup
							.setLatLng(e.latlng)
							.setContent(`You clicked the map at ${e.latlng.toString()}`)
							.openOn(map);
					}
				
					map.on('click', onMapClick);
				
				</script>
			</section>
			<?php } ?>
			<section>
				<header>
					<h2>Groups</h2>
				</header>
				<?php hello_church_contact_groups(perch_get('id')); ?>
				<footer>
					<a href="/groups" class="button primary">Manage Groups</a>
				</footer>
			</section>
			<section>
				<header>
					<h2>Family Members</h2>
				</header>
				<article>
					<?php hello_church_family_members(perch_get('id')); ?>
				</article>
			</section>
			<section>
				<header>
					<h2>Communication</h2>
				</header>
				<?php hello_church_contact_emails(perch_get('id')); ?>
			</section>
			<section>
				<header>
					<h2>Notes</h2>
				</header>
				<?php hello_church_contact_notes(perch_get('id')); ?>
				<footer>
					<a href="/contacts/add-note?id=<?= perch_get('id') ?>" class="button primary">Add Note</a>
				</footer>
			</section>
			<section>
				<header>
					<h2>Upload File</h2>
				</header>
				<article>
					<p class="alert hide" id="alert"></p>
					<?php hello_church_files(0, perch_get('id'), 0, 0, 0); ?>
					<input id="file" type="file" name="file" />
					<input type="hidden" name="folderID" id="folderID" value="0" />
					<input type="hidden" name="contactID" id="contactID" value="<?= perch_get('id') ?>" />
					<input type="hidden" name="groupID" id="groupID" value="0" />
					<input type="hidden" name="eventID" id="eventID" value="0" />
					<input type="hidden" name="eventDate" id="eventDate" value="0000-00-00" />
				</article>
				<footer>
					<button id="upload" class="button primary">Upload</button>
				</footer>
			</section>
		</div>
	</div>
	
	<div class="panel flow">
		<h3>More Options</h3>
		<p><a href="/contacts/export-contact/?id=<?= perch_get('id') ?>">Export contact</a></p>
		<p><a href="/contacts/delete-contact/?id=<?= perch_get('id') ?>" class="warning">Delete contact</a></p>
	</div>
</main>
<?php perch_layout('footer'); ?>