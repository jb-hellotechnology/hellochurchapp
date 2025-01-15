<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

?>

<main class="flow">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Add Audio</h1>
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
			<?php hello_church_form('add_audio.html'); ?>
		</section>
	</div>
</main>
<?php perch_layout('footer'); ?>