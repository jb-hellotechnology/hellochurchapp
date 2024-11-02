<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

?>

<main class="flow full">
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