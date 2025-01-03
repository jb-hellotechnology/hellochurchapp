<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow full">
	<h1>Help</h1>
	<p>Search our database of help articles to find answers to your questions.</p>
	<?php 
		perch_search_form(); 
		$query = perch_get('q');
		perch_content_search($query, array(
			'count'=>5,
			'from-path'=>'/help',
			'excerpt-chars'=>300
		));
	?>
	<?php
		perch_pages_navigation(array(
	        'from-path' => '/help',
	        'levels'    => 2,
	        'template'  => 'help.html'
	    ));
    ?>
</main>
<?php perch_layout('footer'); ?>