<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow full">
	<h1>Groups</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='group_deleted'){
			echo '<p class="alert success">Group successfully deleted.</p>';
		}
	?>
	<section>
		<form method="get" class="search">
			<header>
				<h2>Groups</h2>
			</header>
			<article>
			<?php hello_church_groups(); ?>
			</article>
			<footer>
				<a href="/groups/add-group" class="button primary">Add a Group</a>
			</footer>
		</form>
	</section>
</main>
<?php perch_layout('footer'); ?>