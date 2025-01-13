<?php
if(perch_member_logged_in()){
	$customer_id = stripe_data('churchCustomerID');	
	$url = perch_pages_title(true);
	
	perch_members_refresh_session_data();
	
	$subscription = stripe_data('churchPeriodEnd');
	
	if(!perch_member_has_church() AND $url !== 'Settings - Church'){
		header("location:/settings/church");	
	}

	if(perch_member_has_church() AND $subscription <= time() AND $subscription !== '' AND $url !== 'Setup Subscription'){
		header("location:/subscription");	
	}elseif($subscription <= time() AND $subscription > 0 AND $url !== 'Setup Subscription'){
		header("/settings/subscription");
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
	<link rel="stylesheet" href="/assets/redactor/redactor.min.css" />
	
	<script src='https://www.hCaptcha.com/1/api.js' async defer></script>
	
	<link rel="manifest" href="/manifest.json" />
	<!-- ios support -->
	<link rel="icon" type="image/x-icon" href="/assets/images/icons/favicon.ico">
	<link rel="apple-touch-icon" href="/assets/images/icons/icon-72x72.png" />
	<link rel="apple-touch-icon" href="/assets/images/icons/icon-96x96.png" />
	<link rel="apple-touch-icon" href="/assets/images/icons/icon-128x128.png" />
	<link rel="apple-touch-icon" href="/assets/images/icons/icon-144x144.png" />
	<link rel="apple-touch-icon" href="/assets/images/icons/icon-152x152.png" />
	<link rel="apple-touch-icon" href="/assets/images/icons/icon-192x192.png" />
	<link rel="apple-touch-icon" href="/assets/images/icons/icon-384x384.png" />
	<link rel="apple-touch-icon" href="/assets/images/icons/icon-512x512.png" />
	<meta name="apple-mobile-web-app-status-bar" content="#f0f2f9" />
	<meta name="theme-color" content="#f0f2f9" />

	<link rel="stylesheet" href="https://use.typekit.net/prw8zqs.css">
</head>
<body>
	<header class="site-header">
		<?php
			if(perch_member_logged_in()){
		?>
		<div class="buttons">
			<button class="menu">
				<span class="material-symbols-outlined">
					menu
				</span>
			</button>
			<a class="home" href="/dashboard">
				<span class="material-symbols-outlined">
					home
				</span>
			</a>
		</div>
		<?php
			}
		?>
			
		<h2 class="gooddog"><a href="/dashboard">Hello Church</a></h2>
		<nav>
			<?php
				if(perch_member_logged_in()){
			?>
			<div class="main-nav-container">
				<?php 
				perch_pages_navigation();
  				?>
  				<div class="highlight">
	  				<p>Hello Church Blog</p>
	  				<a href="https://hellochurch.tech/how-should-christians-view-technology/" target="_blank">
		  				<img src="https://hellochurch.tech/wp-content/uploads/2025/01/AdobeStock_117786738-scaled.jpg" alt="Image">
		  			</a>
	  				<h2><a href="https://hellochurch.tech/how-should-christians-view-technology/" target="_blank">How Should Christians View Technology?</a></h2>
  				</div>
			</div>
			<button class="account-nav-button">
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