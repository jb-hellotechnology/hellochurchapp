<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(perch_get('id')>0){
	if(!hello_church_file_owner(perch_get('id'))){
		perch_member_log_out();
		header("location:/");
	}
}

perch_layout('header');

$file = hello_church_file(perch_get('id'));

$folders = hello_church_folder_structure();
PerchSystem::set_var('folders', $folders);

?>

<main class="flow">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Edit File</h1>
	<div class="section-grid">
		<?php
			// DISPLAY MESSAGES
		?>
		<section>
			<header>
				<h2>Edit File</h2>
			</header>
			<?php hello_church_form('update_file.html'); ?>
		</section>
		<section>
			<header>
				<h2>Public URL</h2>
			</header>
			<article>
				<p>Click the button below to copy the public URL for your file.</p>
				<?php $url = hello_church_file_public_url(perch_get('id')); ?>
			</article>
			<footer>
				<button class="button primary" onclick="copy('<?= $url ?>')">Click to Copy</button>
			</footer>
		</section>
	</div>
</main>
<?php perch_layout('footer'); ?>