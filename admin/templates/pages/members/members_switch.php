<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(perch_get('id')){
	$church = hello_church_church_public(perch_get('id'));
	if($church['memberID'] == perch_member_get('memberID') OR perch_get('id') == perch_member_get('churchID')){
		// SET SESSION DATA
		hello_church_update_church_session(perch_get('id'));
		// REDIRECT TO DASHBOARD
		header("location:/dashboard");
	}
}

perch_layout('header');
?>
<main class="flow">
	<h1>Select Church</h1>
	<?php
		if(perch_get('id')){
	?>
	<section>
		<header>Enter Your Key</header>
		<?php hello_church_form('switch_key.html'); ?>
	</section>
	<?php
		}else{
	?>
	<section>
		<header>Please Select</header>
		<article>
			<ul>
				<?php
					perch_member_admin_options();
				?>
			</ul>
		</article>
		<footer>
			<a class="button secondary" href="/settings/church?create=true">Create New Church Profile</a>
		</footer>
	</section>
	<?php
		}
	?>
</main>
<?php perch_layout('footer'); ?>