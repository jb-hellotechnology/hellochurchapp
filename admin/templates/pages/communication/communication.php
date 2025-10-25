<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

?>

<main class="flow full big-little">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Communication</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Email deleted</p>';
		}
	?>
		<?php
			// DISPLAY MESSAGES
		?>
		<div class="section-grid">
			<div>
				<section>
					<header>
						<h2>Drafts</h2>
					</header>
					<?php hello_church_email('Draft'); ?>
				</section>
			</div>
			<div>
				<section>
					<header>
						<h2>New Email</h2>
					</header>
					<?php hello_church_form('add_email.html'); ?>
				</section>
			</div>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>