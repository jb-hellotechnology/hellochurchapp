<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(perch_get('id')>0){
	if(!hello_church_folder_owner(perch_get('id'))){
		perch_member_log_out();
		header("location:/");
	}
}

perch_layout('header');

$folder = hello_church_folder(perch_get('id'));

?>

<main class="flow">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Delete Folder</h1>
	<div class="section-grid">
		<?php
			// DISPLAY MESSAGES
		?>
		<section>
			<header>
				<h2>Delete Folder</h2>
			</header>
			<?php hello_church_form('delete_folder.html'); ?>
		</section>
	</div>
</main>
<?php perch_layout('footer'); ?>