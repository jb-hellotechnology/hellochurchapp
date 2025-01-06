<?php

require '../../../vendor/autoload.php';
include('../../../secrets.php');

perch_layout('header_public');

function logged_in(){
	
}
?>
<main class="flow public narrow">
	<?php
	if(logged_in()){
	?>
	
	<?php	
	}else{
	?>
	<h1>Sign In</h1>
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