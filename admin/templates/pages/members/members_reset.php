<?php
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
			header("location:/dashboard/");	
		}else{
			header("location:/get-started/");
		}
	}else{
		perch_member_remove_tag('active');
		header("location:/account/");
	}
	
}
perch_layout('header');
?>
<main class="narrow">
	<h1>Forgotten Your Password? 🔑</h1>
	<section>
		<header>
			<h2>Reset Your Password</h2>
		</header>
		<?php perch_member_form('reset.html'); ?>
	</section>
	
	<p>Already a member? <a href="/">Sign in</a>.</p>
</main>
<?php
perch_layout('footer');
?>