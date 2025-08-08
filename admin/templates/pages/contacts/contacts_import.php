<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
$contacts = hello_church_contacts_count();
?>
<main class="flow">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
		if($contacts>=200){
	?>
	<h1>Allowance Reached</h1>
	<p>We currently only permit 200 contacts per church profile. If you need more than this please contact <a href="mailto:support@churchplanner.co.uk">support@churchplanner.co.uk</a> to discuss your requirements.</p>
	<?php
		}else{
	?>
	<h1>Import Contacts</h1>
	<div class="section-grid">
		<div>
			<section>
				<header>
					<h2>Import Contacts</h2>
				</header>
				<?php hello_church_form('import_contacts.html'); ?>
			</section>
		</div>
		<div>
			<div class="panel flow">
				<h2>Notes</h2>
				<p>Please only import contacts you have a legitimate reason to manage. By adding contacts to your account you are agreeing to abide by our <a href="https://churchplanner.co.uk/terms" target="_blank">terms of service</a> and understand that inappropriate use of this service can result in the immediate suspension of your account.</p>
			</div>	
		</div>
	</div>
	<?php
		}
	?>
</main>
<?php perch_layout('footer'); ?>