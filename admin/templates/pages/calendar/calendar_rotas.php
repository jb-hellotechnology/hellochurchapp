<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>

<main class="flow">
	<h1>Rotas</h1>
	<div class="section-grid">
		<div>
		<?php
			// DISPLAY MESSAGES
		?>
		<section>
			<header>
				<h2>Download Rotas</h2>
			</header>
			<?php hello_church_form('download_rota_role.html'); ?>
		</section>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>