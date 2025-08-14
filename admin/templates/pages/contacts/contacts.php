<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow full">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Contacts</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='contact_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Contact deleted</p>';
		}
	?>
	<section>
		<form method="get" class="search">
			<header>
				<h2>Contacts</h2>
				<div>
					<select onchange="resetPagination();this.form.submit();" name="tag">
						<option value="">Filter by Tag</option>
						<?php hello_church_contact_tag_options(perch_get('tag')); ?>
					</select>
					<input type="text" name="q" placeholder="Search" value="<?= perch_get('q') ?>" onkeyup="resetPagination();" autocomplete="off" />
					<input type="submit" value="Go" class="button primary small" />
				</div>
			</header>
			<article>
			<?php hello_church_contacts(perch_get('tag'), perch_get('q'), perch_get('page')); ?>
			</article>
		</form>
		<div class="footer-form">
			<p>With Selected Contacts:</p>
			<div>
				<input type="text" id="tag" placeholder="New Tag" />
				<button class="button primary" onclick="confirm_addTag();">Add Tag</button>		
				<span></span>	
				<button class="button danger" onclick="confirm_contactDelete();">Delete</button>
			</div>
		</div>
	</section>
	<section>
		<div id="map"></div>
		<script>
		
			// Example coordinates array [latitude, longitude]
			var markerData = [
				<?php hello_church_contact_map_markers(); ?>
			];
			
			// Create the map
			var map = L.map('map').setView([51.505, -0.09], 13);
			
			// Add a tile layer (required for map display)
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; OpenStreetMap contributors'
			}).addTo(map);
			
			// Create an array to store the marker objects
			var markers = [];
			
			// Add markers from coordinates
			markerData.forEach(function(data) {
				var lat = data[0];
				var lng = data[1];
				var name = data[2];
				var url = data[3];
				
				var popupContent = `<a href="${url}"><b>${name}</b></a>`;
				
				var marker = L.marker([lat, lng])
					.addTo(map)
					.bindPopup(popupContent);
				
				markers.push(marker);
			});
			
			// Create a feature group from the markers
			var group = L.featureGroup(markers);
			
			// Fit map to the bounds of the group (with padding)
			map.fitBounds(group.getBounds(), {
				padding: [50, 50] // optional nice margin
			});
		
		</script>
	</section>
</main>
<?php perch_layout('footer'); ?>