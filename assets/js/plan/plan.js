function draggable_sortable(){
	$( ".sortable" ).sortable({
		revert: true
	});
	$( ".draggable" ).draggable({
		connectToSortable: ".sortable",
		revert: "invalid"
	});
}

$('.add-to-plan').click(function(){
	let type = $(this).data('type');
	let random = Math.floor(Math.random() * 1000001);
	
	if(type=='heading'){
		$('.plan-container').append('<div class="plan-item heading draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="heading_' + random + '" placeholder="Heading" /><a href="javascript:;" class="delete-from-plan warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='text'){
		$('.plan-container').append('<div class="plan-item text draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="text_' + random + '" placeholder="Type something..."></textarea><a href="javascript:;" class="delete-from-plan warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='youtube'){
		$('.plan-container').append('<div class="plan-item youtube draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="youtube_' + random + '" placeholder="<iframe..."></textarea><a href="javascript:;" class="delete-from-plan warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='bible'){
		$('.plan-container').append('<div class="plan-item bible draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="bible_' + random + '" placeholder="John 3:16" /><a href="javascript:;" class="delete-from-plan warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='link'){
		$('.plan-container').append('<div class="plan-item link draggable"><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="link_' + random + '" placeholder="https://hellochurch.tech" /><a href="javascript:;" class="delete-from-plan warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	
	draggable_sortable();
	save_plan();
});

$('body').on('click', 'a.delete-from-plan', function() {
	$(this).parent().remove();
});

function handleSubmit(event) {
	
	event.preventDefault();
	
	$('.save-plan').prop("value", "Saving...");
	
	let pPlan = $('.plan-id').val();
	let pDate = $('.plan-date').val();
	let pTime = $('.plan-time').val();
	
	const data = new FormData(event.target);
	
	// Do a bit of work to convert the entries to a plain JS object
	const value = Object.fromEntries(data.entries());
	
	var json = JSON.stringify(value);
  
  	$.post( "/process/save-plan", { planID: pPlan, date: pDate, time: pTime, plan: json }).done(function( data ) {
		setTimeout(function(){	
			$('.save-plan').prop("value", "Saved!");
		},200);
		setTimeout(function(){	
			$('.save-plan').prop("value", "Save Plan");
		},2000);
	});
}

const form = document.getElementById('form-plan');
form.addEventListener('submit', handleSubmit);

$(document).ready(function(){
	$( ".sortable" ).sortable({
		revert: true
	});
	$( ".draggable" ).draggable({
		connectToSortable: ".sortable",
		revert: "invalid",
	});	
	preview_plan();
});

function preview_plan(){
	
	$('.plan-preview').html('');
	
	let pPlan = $('.plan-id').val();
	let pDate = $('.plan-date').val();
	let pTime = $('.plan-time').val();
	
	$.get( "/process/get-plan", { planID: pPlan, date: pDate, time: pTime }).done(function( data ) {
		$('.plan-preview').html(data);
	});
}

function save_plan(){
	
	$("input[type=submit].save-plan").click();
	
}