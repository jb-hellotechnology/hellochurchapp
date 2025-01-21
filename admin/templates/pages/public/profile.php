<?php

require '../../../vendor/autoload.php';
include('../../../secrets.php');

perch_layout('header_public');

?>
<main class="flow public narrow">
	<?php
	if(signed_in()){
	?>
	<h1>Welcome 👍</h1>
	<ul class="checks">
		<li>
			<span class="material-symbols-outlined">check_circle</span>
			<div><strong>Manage Your Profile</strong><br />Update your details</div>
		</li>
	</ul>
	<section>
		<header>
			<h2>Manage Your Profile</h2>
		</header>
		<?php hello_church_form('update_contact_public.html'); ?>
	</section>
	<section>
		<header>
			<h2>Sign Out</h2>
		</header>
		<article>
			<a href="/profile/sign-out" class="button secondary">Sign Out</a>
		</article>
	</section>
	<?php	
	}else{
	?>
	<h1>Sign In 👍</h1>
	<?php
		if(perch_get('msg')=='success'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Signed out</p>';
		}
	?>
	<section>
		<header>
			<h2>Manage Your Profile</h2>
		</header>
		<article class="flow">
			<label>Find Your Church</label>
			<input type="text" name="church" id="q" onkeyup="searchChurches()" placeholder="Type to search" />
			<div id="results"></div>
			<input type="hidden" name="slug" id="slug" />
			<div id="email">
				<label>Now Enter Your Email Address</label>
				<input type="text" name="email" id="email_address" onkeyup="activateForm()" />
			</div>
			<div id="result"></div>
		</article>
		<footer>
			<input type="submit" id="signin" value="Sign In" class="button primary" disabled />
		</footer>
	</section>
	<?php	
	}
	?>
	
</main>
<?php perch_layout('footer_public'); ?>