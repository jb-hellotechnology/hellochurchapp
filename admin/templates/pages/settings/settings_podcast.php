<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

$church = hello_church_church(true);
$podcast = podcast();
?>
<main class="flow">
	<h1>Podcast Settings</h1>
	<div class="section-grid">
		<div>
			<section>
				<header>
					<h2>Podcast Details</h2>
				</header>
				<?php 
					if($podcast){
						hello_church_form('update_podcast.html'); 
					}else{
						hello_church_form('create_podcast.html');
					} 
				?>
			</section>
		</div>
		<div>
			<section>
				<header>
					<h2>Podcast Image</h2>
				</header>
				<article>
					<?php
					$img = 'http://app.hellochurch.tech/feed/'.$church['churchSlug'].'/podcast.jpg';
					if(file_exists($img)){
						echo '<p>Existing Image:</p>';
						echo '<img src="'.$img.'" alt="Podcast Image" />';
					}
					?>
					<label>Image must be a square JPG file and at least 1920px by 1920px in size</label> 
					<input id="file" type="file" name="file" />
				</article>
				<footer>
					<button id="upload_podcast" class="button primary">Upload</button>
				</footer>
			</section>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>