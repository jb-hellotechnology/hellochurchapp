<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

$church = hello_church_church(true);
$podcast = podcast();
?>
<main class="flow full big-little">
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
			<div class="panel">
				<h3>Notes</h3>
				<ul>
					<li>Podcasts are given the category 'Religion & Spirituality' and sub-category 'Christianity' by default</li>
					<li>Podcasts are marked as 'not explicit' by default</li>
					<li>The 'owner' of your podcast is <?= $church['churchEmail'] ?></li>
				</ul>
				<h3>Feed</h3>
				<p class="monospace">https://app.hellochurch.tech/feed/<?= $church['churchSlug'] ?>/podcast.rss
			</div>
		</div>
		<div>
			<section>
				<header>
					<h2>Podcast Image</h2>
				</header>
				<article>
					<?php
						function get_remote_file_info($url) {
						    $ch = curl_init($url);
						    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						    curl_setopt($ch, CURLOPT_HEADER, TRUE);
						    curl_setopt($ch, CURLOPT_NOBODY, TRUE);
						    $data = curl_exec($ch);
						    $fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
						    $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
						    curl_close($ch);
						    return [
						        'fileExists' => (int) $httpResponseCode == 200,
						        'fileSize' => (int) $fileSize
						    ];
						}
						
						$img = 'https://app.hellochurch.tech/feed/'.$church['churchSlug'].'/podcast.jpg';
						
						$result = get_remote_file_info($img);

						if($result['fileExists']>0){
							echo '<p>Existing Image:</p>';
							echo '<img src="'.$img.'" alt="" />';
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