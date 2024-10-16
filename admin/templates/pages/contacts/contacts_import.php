<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow">
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
				<p>Please only import contacts you have a legitimate reason to manage. By adding contacts to your account you are agreeing to abide by our <a href="/terms">terms of service</a> and understand that inappropriate use of this service can result in the immediate suspension of your account.</p>
			</div>	
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>