<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_email_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

$email = hello_church_get_email(perch_get('id'));

perch_layout('header');
?>
<main class="flow full">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1><?= $email['emailSubject'] ?> <time><?= date('m/d/Y H:i:s', strtotime($email['emailSent'])) ?></time></h1>
	<?php
		if(perch_get('msg')=='sent'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Email sent</p>';
		}	
	?>
	<div class="section-grid">
		<div>
			<section>
				<form id="send_email">
					<header>
						<h2>Email</h2>
					</header>
					<article>
						<div class="email-preview flow">
							<?php
								$emailID = perch_get('id');

								$emailContent = hello_church_get_email_content($emailID);

								echo $emailContent;
							?>
						</div>
					</article>
					<input type="hidden" name="email_test" value="send" />
				</form>
			</section>
		</div>
		<div>
			<section>
				<header>
					<h2>Recipients</h2>
				</header>
				<article>
					<?php
						email_recipients($emailID);	
					?>
				</article>
			</section>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>