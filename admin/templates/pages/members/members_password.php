<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');

?>
<main>
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1>Password</h1>
	<section>
		<header>
			<h2>Change Password</h2>
		</header>
		<?php perch_member_form('password.html'); ?>
	</section>
</main>

<?php perch_layout('footer'); ?>