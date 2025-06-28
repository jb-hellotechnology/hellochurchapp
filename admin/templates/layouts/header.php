<?php
include('../../../secrets.php');

if($down){
	include('../../../maintenance.html');
	exit;	
}

if(perch_member_logged_in()){
	
	if(perch_member_has_tag('deactivated')){
		perch_member_log_out();
		header("location:/");
	}
	
	$customer_id = stripe_data('churchCustomerID');	
	$url = perch_pages_title(true);

	$subscription = stripe_data('churchPeriodEnd');
	
	$adminType = admin_type();
	
	if($_SESSION['codeError']==1 && $url !== 'Switch'){
		header("location:/switch?msg=error");
	}
	
	if(!perch_member_has_church() AND ($url !== 'Setup Subscription' AND $url !== 'Switch' AND $url !== 'Settings - Church' AND $url !== 'Account')){
		// PROMPT USER TO SELECT CHURCH
		header("location:/switch");
	}elseif(perch_member_has_church() AND $url == 'Settings - Church' AND $subscription==''){
		// SETUP SUBSCRIPTION
		header("location:/subscription");
	}elseif(perch_member_has_church() AND $subscription <= time() AND $subscription !== '' AND ($url !== 'Setup Subscription' AND $url !== 'Switch')){
		// SETUP SUBSCRIPTION
		header("location:/subscription");	
	}elseif(perch_member_has_church() AND $subscription <= time() AND $subscription > 0 AND $url !== 'Setup Subscription' AND $url !== 'Switch'){
		// UPDATE SUBSCRIPTION
		header("location:/settings/subscription");
	}
	
	$church = hello_church_church(true);
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
	<link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
	<link href="/assets/js/select2/css/select2.min.css" rel="stylesheet" />
	
	<script src="https://cdn.brevo.com/js/sdk-loader.js" async></script>
	<script>
		// Version: 2.0
		window.Brevo = window.Brevo || [];
		Brevo.push([
			"init",
			{
				client_key: "8cqow0ygwo4jc6z3299w43gq",
				// Optional: Add other initialization options, see documentation
			}
		]);
	</script>
</head>
<body>
	<?php
		if($adminType=='admin'){
			echo '<p class="admin_alert">You are acting as an administrator for <strong>'.$church['churchName'].'</strong></p>';
		}
	?>
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
			
		<h2><a href="/dashboard"><?php if($church){ echo $church['churchName']; }else{ echo 'Church Planner'; } ?></span></a></h2>
		<nav>
			<?php
				if(perch_member_logged_in()){
			?>
			<div class="main-nav-container">
				<?php 
				perch_pages_navigation();
  				?>
  				<div class="highlight">
	  				<p>Church Planner Blog</p>
	  				<a href="https://churchplanner.co.uk/blog/how-should-christians-think-about-technology" target="_blank">
		  				<img src="https://churchplanner.co.uk/admin/resources/ales-nesetril-im7lzjxelhg-unsplash-w600h400.jpg" alt="Image">
		  			</a>
	  				<h2><a href="https://churchplanner.co.uk/blog/how-should-christians-think-about-technology" target="_blank">How Should Christians View Technology?</a></h2>
  				</div>
			</div>
			<button class="account-nav-button">
				<span class="welcome">Hi, <?= perch_member_get('first_name') ?> 👋</span>
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