<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

if(!hello_church_event_owner(perch_get('id'))){
	perch_member_log_out();
	header("location:/");
}

$name = hello_church_calendar_get(perch_get('id'), 'eventName');
$description = hello_church_calendar_get(perch_get('id'), 'eventDescription');
$start = hello_church_calendar_get(perch_get('id'), 'start');
$pTime = explode(" ", $start);
$time = $pTime[1];
$pDates = explode("-", perch_get('date'));
$date = "$pDates[2]/$pDates[1]/$pDates[0]";

$plan = hello_church_get_plan(perch_get('id'), perch_get('date'), $time);

perch_layout('header');
?>
<main class="flow full">
	<?php 
		perch_pages_breadcrumbs(array(
			'include-hidden' => true,
		)); 
	?>
	<h1 class="with-button"><span><?= $name ?> <time><?= $date ?> <?= $time ?></time></span><a class="button secondary border" href="/calendar/edit-event?id=<?= perch_get('id') ?>&date=<?= perch_get('date') ?>">Back</a></h1>
	<?= $description ?>
	<div class="section-grid">
		<div>
			<form id="form-plan">
			<section>
				<header>
					<h2>Event Plan</h2>
				</header>
				<article>
					<div class="plan-container sortable">
						<?php
						$plan = json_decode($plan, true);
						//print_r($plan);
						foreach($plan as $type => $item){
					
							$typeParts = explode("_", $type);
							$type = $typeParts[0];
					
							if($type=='heading'){
								echo '<div class="plan-item heading draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="heading_'.rand().'" placeholder="Heading" value="'.$item.'" /><a href="javascript:;" class="delete-from-plan warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='text'){
								echo '<div class="plan-item text draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="text_'.rand().'" placeholder="Text">'.$item.'</textarea><a href="javascript:;" class="delete-from-plan warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='youtube'){
								echo '<div class="plan-item youtube draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="youtube_'.rand().'" placeholder="<iframe...">'.$item.'</textarea><a href="javascript:;" class="delete-from-plan warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='bible'){
								echo '<div class="plan-item bible draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="bible_'.rand().'" placeholder="John 3:16" value="'.$item.'" /><a href="javascript:;" class="delete-from-plan warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
							if($type=='link'){
								echo '<div class="plan-item link draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="link_'.rand().'" placeholder="https://hellochurch.tech" value="'.$item.'" /><a href="javascript:;" class="delete-from-plan warning"><span class="material-symbols-outlined">delete</span></a></div>';
							}
						}	
						?>
					</div>
					<div class="plan-options">
						<p><strong>Add Item:</strong></p>
						<button class="button secondary add-to-plan" data-type="heading">Heading <span class="material-symbols-outlined">title</span></button>
						<button class="button secondary add-to-plan" data-type="text">Text <span class="material-symbols-outlined">description</span></button>
						<button class="button secondary add-to-plan" data-type="youtube">YouTube Video <span class="material-symbols-outlined">youtube_activity</span></button>
						<button class="button secondary add-to-plan" data-type="bible">Bible Passage <span class="material-symbols-outlined">menu_book</span></button>
						<button class="button secondary add-to-plan" data-type="link">Button <span class="material-symbols-outlined">link</span></button>
					</div>
				</article>
				<footer>
					<input type="hidden" class="plan-id" value="<?= perch_get('id') ?>" />
					<input type="hidden" class="plan-date" value="<?= perch_get('date') ?>" />
					<input type="hidden" class="plan-time" value="<?= $time ?>" />
					<input type="submit" class="button primary save-plan" value="Save" />
				</footer>
			</section>
			</form>
		</div>
		<div>
			<section>
				<header>
					<h2>Preview</h2>
					<div>
						<button class="button small primary" onclick="javascript:preview_plan();">Update Preview</button>
					</div>
				</header>
				<article class="plan-preview flow">
					
				</article>
				<footer>
					<?php hello_church_form('download_plan_pdf.html'); ?>
					<button class="button primary" onclick="javascript:preview_plan();">Update Preview</button>
				</footer>
			</section>
		</div>
	</div>
</main>
<?php perch_layout('footer'); ?>