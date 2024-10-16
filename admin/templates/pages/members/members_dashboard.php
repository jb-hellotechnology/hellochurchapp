<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="full">
	<h1>Dashboard</h1>
	<section>
		<form method="get" class="search">
			<header>
				<h2>Recently Added Contacts</h2>
			</header>
			<article>
			<?php hello_church_recent_contacts(); ?>
			</article>
		</form>
	</section>
</main>
<?php perch_layout('footer'); ?>