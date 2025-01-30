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
			<?php 
				if(hello_church_roles_exist()){
					hello_church_form('download_rota_role.html'); 
				}else{
			?>
			<article>
				<p>Before creating rotas you'll need to add some <a href="/settings/roles">Roles</a> and <a href="/calendar/add-event">Events</a>.</p>
			</article>
			<?php
				}
			?>
		</section>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>