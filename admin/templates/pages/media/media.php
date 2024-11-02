<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

?>

<main class="flow full">
	<h1>Media <a class="button primary" href=/media/add-audio>Add Audio<span class="material-symbols-outlined">cloud_upload</span></a></h1>
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
				<?php hello_church_audio(); ?>
			</article>
			<footer>
				
			</footer>
		</section>
	</div>
</main>
<?php perch_layout('footer'); ?>