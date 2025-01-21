<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!perch_member_has_church() && !perch_member_has_admin_rights()){
	header("location:/settings/church?create=true");
}

if(perch_get('id')){
	$church = church_by_slug(perch_get('id'));
	if(($church['memberID'] == perch_member_get('memberID')) OR ($church['churchID'] == perch_member_get('churchID'))){
		// SET SESSION DATA
		hello_church_update_church_session($church['churchID']);
		// REDIRECT TO DASHBOARD
		header("location:/dashboard");
	}
}

PerchSystem::set_var('churchSlug', perch_get('id'));

perch_layout('header');
?>
<main class="flow">
	<h1>Select Church</h1>
	<?php
		if($_SESSION['codeError'] == 1){
			echo '<p class="alert error"><span class="material-symbols-outlined">error</span>Error - please try again</p>';
			$_SESSION['codeError'] = 0;
		}
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