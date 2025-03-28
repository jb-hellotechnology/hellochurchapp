<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(perch_get('id')>0){
	if(!hello_church_folder_owner(perch_get('id'))){
		perch_member_log_out();
		header("location:/");
	}
}

perch_layout('header');

$folder = hello_church_folder(perch_get('id'));

if($folder){
	$name = $folder['folderName'];
	$shortName = $folder['folderName'];
	
	$parent = hello_church_folder($folder['folderParent']);	
	
	if($folder['folderParent']<>0){
		$parent = '<a href="/documents?id='.$folder['folderParent'].'">&larr; '.$parent['folderName'].'</a>';
	}else{
		$parent = '<a href="/documents">&larr; Documents</a>';
	}
}else{
	$parent = 'Documents';
	$name = 'Documents';
	$shortName = 'Documents';
}
?>

<main class="flow full big-little">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1><?= $name ?></h1>
	<?php
		// DISPLAY MESSAGES
		if($_GET['msg']=='folder_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Folder deleted</p>';
		}
		if($_GET['msg']=='file_deleted'){
			echo '<p class="alert success"><span class="material-symbols-outlined">check_circle</span>File deleted</p>';
		}
	?>
	<div class="section-grid">
		<div>
		<?php
			// DISPLAY MESSAGES
		?>
		<section>
			<header>
				<h2><?= $parent ?></h2>
			</header>
			<article>
				<?php hello_church_folders(perch_get('id')); ?>
				<?php hello_church_files(perch_get('id'), 0, 0, 0, 0); ?>
			</article>
			<footer>
				
			</footer>
		</section>
		</div>
		<div>
			<section>
				<header>
					<h2>Upload File</h2>
				</header>
				<article>
					<p class="alert hide" id="alert"></p>
					<div>
						<label>File  <span class="required">Required</span></label>
						<input id="file" type="file" name="file" required="true" />
					</div>
					<input type="hidden" name="folderID" id="folderID" value="<?= perch_get('id') ?>" />
					<input type="hidden" name="contactID" id="contactID" value="0" />
					<input type="hidden" name="groupID" id="groupID" value="0" />
					<input type="hidden" name="eventID" id="eventID" value="0" />
					<input type="hidden" name="eventDate" id="eventDate" value="0000-00-00" />
				</article>
				<footer>
					<button id="upload" class="button primary">Upload</button>
				</footer>
			</section>
			<section>
				<header>
					<h2>New Folder</h2>
				</header>
				<?php hello_church_form('add_folder.html'); ?>
			</section>
			<?php
				if(perch_get('id')>0){
			?>
			<section>
				<header>
					<h2>Rename Folder</h2>
				</header>
				<?php hello_church_form('update_folder.html'); ?>
			</section>
			<?php
				if(!folder_has_children(perch_get('id'))){
			?>
			<div class="panel">
				<h3>More Options</h3>
				<p><a href="/documents/delete-folder/?id=<?= perch_get('id') ?>" class="warning">Delete folder</a></p>
			</div>
			<?php
				}
			?>
			</div>
			<?php
				}
			?>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>