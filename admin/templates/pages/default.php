<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_content_create('Page', array(
    'template' => 'page.html'
));

perch_layout('header');
?>
<main class="flow">
	<?php perch_content('Page'); ?>
</main>
<?php perch_layout('footer'); ?>