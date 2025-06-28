$(document).ready(function(){
	$( ".sortable" ).sortable({
		revert: true
	});
	$( ".draggable" ).draggable({
		connectToSortable: ".sortable",
		revert: "invalid",
	});
	
	$( ".sortable-sessions" ).sortable({
		revert: true,
		update: function () {
			$( ".sortable-sessions li" ).each(function( index ) {
				$.post( "/process/save-session-order", { sessionID: $( this ).data('session'), order: index }).done();
			});
		}
	});
	$( ".draggable-sessions" ).draggable({
		connectToSortable: ".sortable-sessions",
		revert: "invalid",
	});	
	preview_session();
});

function draggable_sortable(){
	$( ".sortable" ).sortable({
		revert: true
	});
	$( ".draggable" ).draggable({
		connectToSortable: ".sortable",
		revert: "invalid"
	});
}

$(document).ready(function() {
	$('.js-example-basic-single').select2();
});

$('.add-to-session').click(function(){
	let type = $(this).data('type');
	let random = Math.floor(Math.random() * 1000001);
	
	if(type=='heading'){
		$('.session-container').append('<div class="plan-item heading draggable"><label>Heading</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="heading_' + random + '" placeholder="Heading" /><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='text'){
		$('.session-container').append('<div class="plan-item text draggable"><label>Text</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="text_' + random + '" placeholder="Type something..."></textarea><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='youtube'){
		$('.session-container').append('<div class="plan-item youtube draggable"><label>YouTube</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="youtube_' + random + '" placeholder="<iframe..."></textarea><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='bible'){
		$('.session-container').append('<div class="plan-item bible draggable"><label>Bible Passage</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="bible_' + random + '" placeholder="John 3:16" /><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='link'){
		$('.session-container').append('<div class="plan-item link draggable"><label>Button</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><div><input type="text" name="link_' + random + '_text" placeholder="Button Text" value="Click Here" /><input type="text" class="no-border-top" name="link_' + random + '_url" placeholder="https://churchplanner.co.uk" /></div><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='image'){
		$('.session-container').append('<div class="plan-item image draggable"><label>Image</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="image_' + random + '" id="image_' + random +'" class="js-example-basic-single"></select><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>');
		populate_select('image_' + random, 'image');
	}
	if(type=='file'){
		$('.session-container').append('<div class="plan-item file draggable"><label>File</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="file_' + random + '" id="file_' + random +'" class="js-example-basic-single"></select><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>');
		populate_select('file_' + random, 'file');
	}
	if(type=='event'){
		$('.session-container').append('<div class="plan-item event draggable"><label>Event</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="event_' + random + '" id="event_' + random +'" class="js-example-basic-single"></select><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>');
		populate_select('event_' + random, 'event');
	}
	if(type=='plan'){
		$('.session-container').append('<div class="plan-item plan draggable"><label>Plan</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="plan_' + random + '" id="plan_' + random +'" class="js-example-basic-single"></select><a href="javascript:;" class="delete-from-session warning"><span class="material-symbols-outlined">delete</span></a></div>');
		populate_select('plan_' + random, 'plan');
	}
	
	draggable_sortable();
	save_session();
	
	$('.js-example-basic-single').select2();
});

function populate_select(pItem, pType){

	$.get( "/process/populate-select", { type: pType }).done(function( items ) {
		$('#' + pItem).append($('<option>', { 
	        value: 0,
	        text : 'Please Select' 
	    }));
		$.each(JSON.parse(items), function (i, item) {
		    $('#' + pItem).append($('<option>', { 
		        value: item.value,
		        text : item.text 
		    }));
		});
	});
	
}

$(document).ready(function(){
	
	$('.image-select').each(function(){
		let active = $(this).data('image');
		let id = $(this).data('id');
		$.get( "/process/populate-select", { type: 'image' }).done(function( items ) {
			$('#image_'+id).append($('<option>', { 
		        value: 0,
		        text : 'Please Select' 
		    }));
			$.each(JSON.parse(items), function (i, item) {
				if(item.value == active){
					$('#image_'+id).append($('<option>', { 
				        value: item.value,
				        text : item.text,
				        selected: true
				    }));
				}else{
					$('#image_'+id).append($('<option>', { 
				        value: item.value,
				        text : item.text,
				    }));	
				}
			});
		});
	});
	
	$('.file-select').each(function(){
		let active = $(this).data('file');
		let id = $(this).data('id');
		$.get( "/process/populate-select", { type: 'file' }).done(function( items ) {
			$('#file_'+id).append($('<option>', { 
		        value: 0,
		        text : 'Please Select' 
		    }));
			$.each(JSON.parse(items), function (i, item) {
				if(item.value == active){
					$('#file_'+id).append($('<option>', { 
				        value: item.value,
				        text : item.text,
				        selected: true
				    }));
				}else{
					$('#file_'+id).append($('<option>', { 
				        value: item.value,
				        text : item.text,
				    }));	
				}
			});
		});
	});
	
	$('.event-select').each(function(){
		let active = $(this).data('event');
		let id = $(this).data('id');
		$.get( "/process/populate-select", { type: 'event' }).done(function( items ) {
			$('#event_'+id).append($('<option>', { 
		        value: 0,
		        text : 'Please Select' 
		    }));
			$.each(JSON.parse(items), function (i, item) {
				if(item.value == active){
					$('#event_'+id).append($('<option>', { 
				        value: item.value,
				        text : item.text,
				        selected: true
				    }));
				}else{
					$('#event_'+id).append($('<option>', { 
				        value: item.value,
				        text : item.text,
				    }));	
				}
			});
		});
	});
	
	$('.plan-select').each(function(){
		let active = $(this).data('plan');
		let id = $(this).data('id');
		$.get( "/process/populate-select", { type: 'plan' }).done(function( items ) {
			$('#plan_'+id).append($('<option>', { 
				value: 0,
				text : 'Please Select' 
			}));
			$.each(JSON.parse(items), function (i, item) {
				if(item.value == active){
					$('#plan_'+id).append($('<option>', { 
						value: item.value,
						text : item.text,
						selected: true
					}));
				}else{
					$('#plan_'+id).append($('<option>', { 
						value: item.value,
						text : item.text,
					}));	
				}
			});
		});
	});
	
});

$('body').on('click', 'a.delete-from-session', function() {
	$(this).parent().remove();
	save_session();
});

function handleSubmit(event) {
	
	event.preventDefault();
	
	$('.save-session').prop("value", "Saving...");
	
	let pSession = $('.session-id').val();
	
	const data = new FormData(event.target);
	
	// Do a bit of work to convert the entries to a plain JS object
	const value = Object.fromEntries(data.entries());
	
	var json = JSON.stringify(value);
  
  	$.post( "/process/save-session", { sessionID: pSession, session: json }).done(function( data ) {
		setTimeout(function(){	
			$('.save-session').prop("value", "Saved!");
			preview_session();
		},200);
		setTimeout(function(){	
			$('.save-session').prop("value", "Save Session");
		},2000);
	});
}

const session_form = document.getElementById('form-session');
session_form.addEventListener('submit', handleSubmit);

$(document).ready(function(){
	console.log('here');
	$( ".sortable" ).sortable({
		revert: true
	});
	$( ".draggable" ).draggable({
		connectToSortable: ".sortable",
		revert: "invalid",
	});	
	preview_session();
});

function preview_session(){
	
	$('.session-preview').html('');
	
	let pSession = $('.session-id').val();

	$.get( "/process/get-session", { sessionID: pSession }).done(function( data ) {
		$('.session-preview').html(data);
	});
}

function save_session(){
	
	$("input[type=submit].save-session").click();
	
}