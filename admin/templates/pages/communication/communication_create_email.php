<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_email_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

$email = hello_church_get_email(perch_get('id'));

perch_layout('header');
?>
<main class="flow full">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1 class="with-button"><?= $email['emailSubject'] ?> <!-- <a class="button primary" href="/communication/send-email?id=<?= perch_get('id') ?>">Send<span class="material-symbols-outlined">send</span></a> --></h1>
	<?= $description ?>
	<div class="section-grid">
		<div>
			<form id="form-email">
			<section>
				<header>
					<h2><strong>Subject:</strong> <?= $email['emailSubject'] ?></h2>
				</header>
				<article>
					<div class="email-container sortable">
						<?php
						$email = json_decode($email['emailContent'], true);
						//print_r($email);
						foreach($email as $type => $item){
					
							$typeParts = explode("_", $type);
							$type = $typeParts[0];
					
							if($type=='heading'){
								echo '<div class="plan-item heading draggable"><label>Heading</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="heading_'.rand().'" placeholder="Heading" value="'.$item.'" /><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='text'){
								echo '<div class="plan-item text draggable"><label>Text</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="text_'.rand().'" placeholder="Text">'.$item.'</textarea><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='youtube'){
								echo '<div class="plan-item youtube draggable"><label>YouTube</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="youtube_'.rand().'" placeholder="<iframe...">'.$item.'</textarea><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='bible'){
								echo '<div class="plan-item bible draggable"><label>Bible Passage</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="bible_'.rand().'" placeholder="John 3:16" value="'.$item.'" /><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='link'){
								
								if($typeParts[2]=='text'){
									$buttonText = $item;	
								}else{
									echo '<div class="plan-item link draggable"><label>Button</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><div><input type="text" name="link_'.rand().'_text" placeholder="Button Text" value="'.$buttonText.'" /><input type="text" class="no-border-top" name="link_'.rand().'_url" placeholder="https://hellochurch.tech" value="'.$item.'" /></div><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
								}
								
							}
							if($type=='image'){
								$rand = rand();
								echo '<div class="plan-item image draggable"><label>Image</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="image_'.$rand.'" data-image="'.$item.'" data-id="'.$rand.'" id="image_'.$rand.'" class="image-select"></select><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='file'){
								$rand = rand();
								echo '<div class="plan-item file draggable"><label>File</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="file_'.$rand.'" data-file="'.$item.'" data-id="'.$rand.'" id="file_'.$rand.'" class="file-select"></select><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='event'){
								$rand = rand();
								echo '<div class="plan-item image draggable"><label>Event</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="event_'.$rand.'" data-event="'.$item.'" data-id="'.$rand.'" id="event_'.$rand.'" class="event-select"></select><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
						}	
						?>
					</div>
					<div class="email-options">
						<p><strong>Add Item:</strong></p>
						<button class="button secondary add-to-email" data-type="heading">Heading <span class="material-symbols-outlined">title</span></button>
						<button class="button secondary add-to-email" data-type="text">Text <span class="material-symbols-outlined">description</span></button>
						<button class="button secondary add-to-email" data-type="bible">Bible Passage <span class="material-symbols-outlined">menu_book</span></button>
						<button class="button secondary add-to-email" data-type="link">Button <span class="material-symbols-outlined">link</span></button>
						<button class="button secondary add-to-email" data-type="image">Image <span class="material-symbols-outlined">image</span></button>
						<button class="button secondary add-to-email" data-type="file">File <span class="material-symbols-outlined">attach_file</span></button>
						<button class="button secondary add-to-email" data-type="event">Event <span class="material-symbols-outlined">event</span></button>
					</div>
				</article>
				<footer>
					<input type="hidden" class="email-id" value="<?= perch_get('id') ?>" />
					<input type="submit" class="button primary save-email" value="Save" />
				</footer>
			</section>
			</form>
		</div>
		<div>
			<section>
					<header>
						<h2>Preview</h2>
						<div>
							<input type="text" id="test_recipient" placeholder="you@example.com" />
							<button class="button small primary" onclick="javascript:send_test();">Send Test</button>
						</div>
					</header>
					<form id="send_email">
						<input type="hidden" id="email_id" value="<?= perch_get('id') ?>" name="email_id" />
						<article>
							<div class="to">
								<label>To Contacts</label>
								<input type="text" id="contacts" name="contacts" class="contacts-tagify" />
								<label>To Groups</label>
								<input type="text" id="groups" name="groups" class="groups-tagify" />
							</div>
							<div class="email-preview flow">
								
							</div>
						</article>
					</form>
				<footer>
					<button class="button primary" onclick="javascript:send_email();">Send Email</button>
				</footer>
			</section>
		</div>
		<div>
			<section>
				<header>
					<h2>Email Subject</h2>
				</header>
				<?php hello_church_form('update_email.html'); ?>
			</section>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>