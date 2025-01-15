<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow full">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Help - <?php perch_pages_title(); ?></h1>
	<p>Browse our database of help articles to find answers to your questions:</p>
	<?php
		perch_pages_navigation(array(
	        'from-path' => $_SERVER['REQUEST_URI'],
	        'levels'    => 2,
	        'template'  => 'help.html'
	    ));
    ?>
</main>
<?php perch_layout('footer'); ?>