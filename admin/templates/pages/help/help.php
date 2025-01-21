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
	<h1>Help</h1>
	<p>Browse our database of help articles to find answers to your questions.</p>
	<?php 
/*
		perch_search_form(); 
		$query = perch_get('q');
		perch_content_search($query, array(
			'count'=>5,
			'from-path'=>'/help',
			'excerpt-chars'=>300
		));
*/
	?>
	<?php
		perch_pages_navigation(array(
	        'from-path' => '/help',
	        'levels'    => 2,
	        'template'  => 'help.html'
	    ));
    ?>
    <h2>Free Onboarding Sessions</h2>
    <p>Need more support? Book an <a href="/help/onboarding">onboarding session</a> with Jack.</p>
</main>
<?php perch_layout('footer'); ?>