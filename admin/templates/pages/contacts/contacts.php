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
			
			// Add a tile layer
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; OpenStreetMap contributors'
			}).addTo(map);
			
			// Step 1: Group data by lat,lng
			var grouped = {};
			markerData.forEach(function(item) {
				var key = item[0] + "," + item[1]; // lat,lng as key
				if (!grouped[key]) {
					grouped[key] = [];
				}
				grouped[key].push({ name: item[2], url: item[3] });
			});
			
			// Step 2: Create markers from grouped data
			var markers = [];
			
			Object.keys(grouped).forEach(function(key) {
				var parts = key.split(",");
				var lat = parseFloat(parts[0]);
				var lng = parseFloat(parts[1]);
				var items = grouped[key];
			
				// Create popup HTML with all items for this location
				var popupContent = items.map(function(i) {
					return `<a href="${i.url}"><b>${i.name}</b>`;
				}).join("<br>");
			
				var marker = L.marker([lat, lng])
					.addTo(map)
					.bindPopup(popupContent);
			
				markers.push(marker);
			});
			
			// Step 3: Fit map to all markers
			var group = L.featureGroup(markers);
			map.fitBounds(group.getBounds(), { padding: [50, 50] });
		
		</script>
	</section>
</main>
<?php perch_layout('footer'); ?>