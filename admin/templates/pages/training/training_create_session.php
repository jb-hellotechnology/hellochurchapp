<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_session_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

$church = hello_church_church(true);

$session = hello_church_get_session(perch_get('id'));
$publicLink = $session['uniqueID'];

$topic = hello_church_get_topic($session['topicID']);

perch_layout('header');
?>
<main class="flow full">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1><a href="/training/view-topic?id=<?= $topic['topicID'] ?>"><?= $topic['topicName'] ?></a>: <?= $session['sessionName'] ?></h1>
	<div class="section-grid">
		<div>
			<form id="form-session">
			<section>
				<header>
					<h2>Session Content</h2>
				</header>
				<article>
					<div class="session-container sortable">
						<?php
						$session = json_decode($session['sessionContent'], true);
						//print_r($email);
						foreach($session as $type => $item){
					
							$typeParts = explode("_", $type);
							$type = $typeParts[0];
					
							if($type=='heading'){
								echo '<div class="plan-item heading draggable"><label>Heading</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="heading_'.rand().'" placeholder="Heading" value="'.$item.'" /><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='text'){
								echo '<div class="plan-item text draggable"><label>Text</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="text_'.rand().'" placeholder="Text">'.$item.'</textarea><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='youtube'){
								echo '<div class="plan-item youtube draggable"><label>YouTube</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="youtube_'.rand().'" placeholder="https://youtu.be/..." value="'.$item.'" /><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='bible'){
								echo '<div class="plan-item bible draggable"><label>Bible Passage</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="bible_'.rand().'" placeholder="John 3:16" value="'.$item.'" /><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='link'){
								
								if($typeParts[2]=='text'){
									$buttonText = $item;	
								}else{
									echo '<div class="plan-item link draggable"><label>Button</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><div><input type="text" name="link_'.rand().'_text" placeholder="Button Text" value="'.$buttonText.'" /><input type="text" class="no-border-top" name="link_'.rand().'_url" placeholder="https://churchplanner.co.uk" value="'.$item.'" /></div><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>';
								}
								
							}
							if($type=='image'){
								$rand = rand();
								echo '<div class="plan-item image draggable"><label>Image</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="image_'.$rand.'" data-image="'.$item.'" data-id="'.$rand.'" id="image_'.$rand.'" class="image-select js-example-basic-single"></select><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='file'){
								$rand = rand();
								echo '<div class="plan-item file draggable"><label>File</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="file_'.$rand.'" data-file="'.$item.'" data-id="'.$rand.'" id="file_'.$rand.'" class="file-select js-example-basic-single"></select><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='event'){
								$rand = rand();
								echo '<div class="plan-item image draggable"><label>Event</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="event_'.$rand.'" data-event="'.$item.'" data-id="'.$rand.'" id="event_'.$rand.'" class="event-select js-example-basic-single"></select><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='plan'){
								$rand = rand();
								echo '<div class="plan-item image draggable"><label>Plan</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="plan_'.$rand.'" data-plan="'.$item.'" data-id="'.$rand.'" id="plan_'.$rand.'" class="plan-select js-example-basic-single"></select><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
						}	
						?>
					</div>
					<div class="session-options">
						<p><strong>Add Item:</strong></p>
						<button class="button secondary add-to-session" data-type="heading">Heading <span class="material-symbols-outlined">title</span></button>
						<button class="button secondary add-to-session" data-type="text">Text <span class="material-symbols-outlined">description</span></button>
						<button class="button secondary add-to-session" data-type="bible">Bible Passage <span class="material-symbols-outlined">menu_book</span></button>
						<button class="button secondary add-to-session" data-type="youtube">YouTube Video <span class="material-symbols-outlined">youtube_activity</span></button>
						<button class="button secondary add-to-session" data-type="link">Button <span class="material-symbols-outlined">link</span></button>
						<button class="button secondary add-to-session" data-type="image">Image <span class="material-symbols-outlined">image</span></button>
						<button class="button secondary add-to-session" data-type="file">File <span class="material-symbols-outlined">attach_file</span></button>
					</div>
				</article>
				<footer>
					<input type="hidden" class="session-id" value="<?= perch_get('id') ?>" />
					<input type="submit" class="button primary save-session" value="Save" />
				</footer>
			</section>
			</form>
		</div>
		<div>
			<section>
					<header>
						<h2>Preview</h2>
					</header>
					<article>
						<div class="session-preview flow">
							
						</div>
					</article>
				<!-- <footer>
					<button class="button primary" onclick="javascript:send_email();">Send Email</button>
				</footer> -->
			</section>
		</div>
	</div>
	<div class="section-grid three">
		<div>
			<section>
				<header>
					<h2>Share Public Link</h2>
				</header>
				<article>
					<div>
						<p class="monospace">https://app.churchplanner.co.uk/session/<?= $church['churchSlug'] ?>/<?= $publicLink ?>
					</div>
				</article>
				<?php 
				
				?>
			</section>
		</div>
		<div>
			<section>
				<header>
					<h2>Update Session</h2>
				</header>
				<?php 
				hello_church_form('update_session.html'); 
				?>
			</section>
		</div>
		<div>
			<section>
				<header>
					<h2>Delete Session</h2>
				</header>
				<?php 
				PerchSystem::set_var('sessionID', perch_get('id'));
				PerchSystem::set_var('topicID', $topic['topicID']);
				hello_church_form('delete_session.html'); 
				?>
			</section>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>