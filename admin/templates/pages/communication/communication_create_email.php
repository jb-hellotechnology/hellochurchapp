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
								echo '<div class="plan-item heading draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="heading_'.rand().'" placeholder="Heading" value="'.$item.'" /><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='text'){
								echo '<div class="plan-item text draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="text_'.rand().'" placeholder="Text">'.$item.'</textarea><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='youtube'){
								echo '<div class="plan-item youtube draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="youtube_'.rand().'" placeholder="<iframe...">'.$item.'</textarea><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='bible'){
								echo '<div class="plan-item bible draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="bible_'.rand().'" placeholder="John 3:16" value="'.$item.'" /><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='link'){
								echo '<div class="plan-item link draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="link_'.rand().'" placeholder="https://hellochurch.tech" value="'.$item.'" /><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
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
						<input type="hidden" id="email_id" value="<?= perch_get('id') ?>" />
						<button class="button small primary" onclick="javascript:send_test();">Send Test</button>
					</div>
				</header>
				<article>
					<div class="to">
						<label>To Contacts</label>
						<input type="text" id="contacts" class="contacts-tagify" />
						<label>To Groups</label>
						<input type="text" id="groups" class="groups-tagify" />
					</div>
					<div class="email-preview flow">
						
					</div>
				</article>
				<footer>
					
					<button class="button primary" onclick="javascript:send_email();">Send Email <span class="material-symbols-outlined">send</span></button>
				</footer>
			</section>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>