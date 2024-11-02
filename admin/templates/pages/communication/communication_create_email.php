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
	<h1 class="with-button">Create Email</h1>
	<?= $description ?>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='contact_added'){
			echo '<p class="alert success">Contact added to role.</p>';
		}	
	?>
	<div class="section-grid">
		<div>
			<form id="form-email">
			<section>
				<header>
					<h2>Email Content <time><?= $email['emailSubject'] ?></time></h2>
				</header>
				<article>
					<div class="email-container sortable">
						<?php
						$email = json_decode($email, true);
						//print_r($email);
						foreach($email as $type => $item){
					
							$typeParts = explode("_", $type);
							$type = $typeParts[0];
					
							if($type=='heading'){
								echo '<div class="email-item heading draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="heading_'.rand().'" placeholder="Heading" value="'.$item.'" /><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='text'){
								echo '<div class="email-item text draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="text_'.rand().'" placeholder="Text">'.$item.'</textarea><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='youtube'){
								echo '<div class="email-item youtube draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="youtube_'.rand().'" placeholder="<iframe...">'.$item.'</textarea><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='bible'){
								echo '<div class="email-item bible draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="bible_'.rand().'" placeholder="John 3:16" value="'.$item.'" /><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='link'){
								echo '<div class="email-item link draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="link_'.rand().'" placeholder="https://hellochurch.tech" value="'.$item.'" /><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>';
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
						<button class="button small primary" onclick="javascript:preview_email();">Update Preview</button>
					</div>
				</header>
				<article class="email-preview flow">
					
				</article>
				<footer>
					<button class="button primary" onclick="javascript:preview_email();">Update Preview</button>
				</footer>
			</section>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>