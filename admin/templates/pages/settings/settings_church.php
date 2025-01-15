<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
	<?php 
		if(perch_member_has_church()){
			perch_pages_breadcrumbs(array(
				'include-hidden' => true,
			)); 
		}
	?>
	<h1>Church Settings</h1>
	<?php 
		if(!perch_member_has_church()){
			echo '<p class="alert success">Success! Please now add your church contact details using the form below:</p>';
		}
	?>
	<section>
		<header>
			<h2>Contact Details</h2>
		</header>
		
		<?php 
			if(perch_member_has_church()){
				hello_church_form('update_church.html'); 
			}else{
				hello_church_form('create_church.html');
			}
		?>
	</section>
</main>
<?php perch_layout('footer'); ?>