<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_topic_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

$topic = hello_church_get_topic(perch_get('id'));

perch_layout('header');
?>
<main class="flow full big-little">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1><?= $topic['topicName'] ?></h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='session_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Session deleted</p>';
		}
	?>
		<?php
			// DISPLAY MESSAGES
		?>
		<div class="section-grid">
			<div>
				<section>
					<header>
						<h2>Sessions</h2>
					</header>
					<article>
						<?php hello_church_training_sessions(perch_get('id')); ?>
					</article>
				</section>
			</div>
			<div>
				<section>
					<header>
						<h2>New Session</h2>
					</header>
					<?php 
					PerchSystem::set_var('topicID', perch_get('id'));
					hello_church_form('add_session.html'); 
					?>
				</section>
				<section>
					<header>
						<h2>Update Topic</h2>
					</header>
					<?php 
					hello_church_form('update_topic.html'); 
					?>
				</section>
				<?php if(!sessions_exist(perch_get('id'))){ ?>
				<section>
					<header>
						<h2>Delete Topic</h2>
					</header>
					<?php 
					PerchSystem::set_var('topicID', perch_get('id'));
					hello_church_form('delete_topic.html'); 
					?>
				</section>
				<?php } ?>
			</div>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>