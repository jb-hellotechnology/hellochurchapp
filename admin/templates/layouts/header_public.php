<?php
if(perch_get('church')){
	$church = church_by_slug(perch_get('church'));
	print_r($church);
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
	<link href="/assets/css/stylesheet_public.css?v=<?= rand() ?>" rel="stylesheet">
	
	<script src='https://www.hCaptcha.com/1/api.js' async defer></script>
	
	<link rel="stylesheet" href="https://use.typekit.net/prw8zqs.css">
</head>
<body>
	<header class="site-header">
		<h2><a href="https://churchplanner.co.uk">Church Planner</a></h2>
	</header>