<?php
if(perch_member_logged_in()){
	$customer_id = stripe_data('memberCustomerID');	
	$url = perch_pages_title(true);
	if($customer_id == '' AND $url !== 'Setup Subscription' AND $url !== 'Get Started'){
		header("location:/subscription");
	}
}
?>
<!doctype html>
<html>
<head>
	<title><?= perch_pages_title() ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
	<link href="/assets/css/stylesheet.css?v=<?= rand() ?>" rel="stylesheet">
	
	<link href="/assets/js/tagify/tagify.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<header class="site-header">
		<?php
			if(perch_member_logged_in()){
		?>
		<button class="menu">
			<span class="material-symbols-outlined">
				menu
			</span>
		</button>
		<?php
			}
		?>
			
		<h2><a href="/">Hello Church</a></h2>
		<nav>
			<?php
				if(perch_member_logged_in()){
			?>
			<div class="main-nav-container">
				<?php 
				perch_pages_navigation();
  				?>
			</div>
			<button popovertarget="account-nav">
				<span class="material-symbols-outlined">
				account_circle
				</span>
			</button>
			<?php 
				perch_pages_navigation(array(
					'navgroup' => 'account',
					'template' => 'account.html'
  				));
				}
			?>
		</nav>
	</header>