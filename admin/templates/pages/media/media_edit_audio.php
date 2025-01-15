<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(perch_get('id')>0){
	if(!hello_church_audio_owner(perch_get('id'))){
		perch_member_log_out();
		header("location:/");
	}
}

perch_layout('header');

?>

<main class="flow full">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Edit Audio</h1>
		<section>
			<header>
				<h2>Audio</h2>
			</header>
			<?php hello_church_form('edit_audio.html'); ?>
		</section>
	</div>
</main>
<?php perch_layout('footer'); ?>