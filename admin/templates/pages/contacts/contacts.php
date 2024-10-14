<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main class="flow full">
	<h1>Contacts</h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='contact_deleted'){
			echo '<p class="alert success">Contact successfully deleted.</p>';
		}
	?>
	<section>
		<form method="get" class="search">
			<header>
				<h2>Contacts</h2>
				<div>
					<select onchange="resetPagination();this.form.submit();" name="tag">
						<option value="">Filter by Tag</option>
						<?php hello_church_contact_tag_options(perch_get('tag')); ?>
					</select>
					<input type="text" name="q" placeholder="Search" value="<?= perch_get('q') ?>" onkeyup="resetPagination();" />
				</div>
			</header>
			<?php hello_church_contacts(perch_get('tag'), perch_get('q'), perch_get('page')); ?>
		</form>
		<div class="footer-form">
			<div>
				<input type="text" id="tag" />
				<button class="button primary" onclick="confirm_addTag();">Add Tag</button>		
				<span></span>	
				<button class="button danger" onclick="confirm_contactDelete();">Delete</button>
			</div>
		</div>
	</section>
</main>
<?php perch_layout('footer'); ?>