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
	<h1>Media <a class="button primary" href=/audio/add-audio>Add Audio<span class="material-symbols-outlined">cloud_upload</span></a></h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='audio_deleted'){
			echo '<p class="alert success">Audio successfully deleted.</p>';
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
				<?php hello_church_files(perch_get('id')); ?>
			</article>
			<footer>
				
			</footer>
		</section>
	</div>
</main>
<?php perch_layout('footer'); ?>