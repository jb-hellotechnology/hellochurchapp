<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

$adminType = admin_type();
$cancel = stripe_data('churchCancel');
?>
<main class="flow">
	<?php
			if($adminType=='owner'){
	?>
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Delete a Church Profile</h1>
	<?php 
		if($cancel){ 
	?>
	<section>
		<header>
			<h2>Delete Church Profile</h2>
		</header>
		<?php hello_church_form('delete_church.html'); ?>
	</section>
	<?php
			}else{
				echo '<p>Only profiles scheduled for cancellation can be deleted.</p>';
			}
		}else{
			echo '<h1>Owner Access Only</h1><p>Profile administrators cannot access this page for security reasons.</p>';	
		}	
	?>
</main>
<?php perch_layout('footer'); ?>