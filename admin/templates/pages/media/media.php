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
	<h1>Media <a class="button primary" href=/media/add-audio>Add Audio</a></h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='audio_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Audio deleted</p>';
		}
		if($_GET['msg']=='audio_uploaded'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Audio uploaded</p>';
		}
		if($_GET['msg']=='audio_error'){
			echo '<p class="alert success"><span class="material-symbols-outlined">error</span>Upload error</p>';
		}
	?>
		<?php
			// DISPLAY MESSAGES
		?>
		<section>
			<header>
				<h2>Audio</h2>
			</header>
			<article>
				<?php hello_church_audio(); ?>
			</article>
			<footer>
				
			</footer>
		</section>
	</div>
</main>
<?php perch_layout('footer'); ?>