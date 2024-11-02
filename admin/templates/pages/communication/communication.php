<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

?>

<main class="flow full big-little">
	<h1>Communication</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='audio_deleted'){
			echo '<p class="alert success">Email successfully deleted.</p>';
		}
	?>
		<?php
			// DISPLAY MESSAGES
		?>
		<div class="section-grid">
			<div>
				<section>
					<header>
						<h2>Email</h2>
					</header>
					<article>
						<?php hello_church_email(); ?>
					</article>
					<footer>
						
					</footer>
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