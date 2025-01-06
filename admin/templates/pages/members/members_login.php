<?php
include('../../../secrets.php');
if(perch_member_logged_in()){

	if(perch_member_has_tag('deactivated')){
		perch_member_log_out();
		PerchSystem::redirect('/');
	}

	// CHECK FOR ACTIVE SUBSCRIPTION
	$current_period = stripe_data('memberPeriodEnd');
	if($current_period > time()){
		$date = gmdate("Y-m-d", $current_period);
		perch_member_add_tag('active', $date);
		if(perch_member_has_tag('onboarded')){
			header("location:/dashboard");	
		}else{
			header("location:/get-started");
		}
	}else{
		perch_member_remove_tag('active');
		header("location:/dashboard");
	}
	
}
perch_layout('header');
PerchSystem::set_var('hcaptchaSiteKey', $hcaptchaSiteKey);
?>
<main class="narrow">
	<h1>Hello 👋</h1>
	<section>
		<header>
			<h2>Sign In</h2>
		</header>
		<?php perch_members_login_form(); ?>
	</section>
	<p>Forgotten your password? <a href="/reset/">Reset it now</a>.</p>
	<p>Not a member? <a href="/register/">Sign up for a 30 day free trial</a>.</p>
</main>
<?php
perch_layout('footer');
?>