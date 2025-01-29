function draggable_sortable(){
	$( ".sortable" ).sortable({
		revert: true
	});
	$( ".draggable" ).draggable({
		connectToSortable: ".sortable",
		revert: "invalid"
	});
}

$('.add-to-email').click(function(){
	let type = $(this).data('type');
	let random = Math.floor(Math.random() * 1000001);
	
	if(type=='heading'){
		$('.email-container').append('<div class="plan-item heading draggable"><label>Heading</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="heading_' + random + '" placeholder="Heading" /><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='text'){
		$('.email-container').append('<div class="plan-item text draggable"><label>Text</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="text_' + random + '" placeholder="Type something..."></textarea><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='youtube'){
		$('.email-container').append('<div class="plan-item youtube draggable"><label>YouTube</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><textarea name="youtube_' + random + '" placeholder="<iframe..."></textarea><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='bible'){
		$('.email-container').append('<div class="plan-item bible draggable"><label>Bible Passage</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><input type="text" name="bible_' + random + '" placeholder="John 3:16" /><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='link'){
		$('.email-container').append('<div class="plan-item link draggable"><label>Button</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><div><input type="text" name="link_' + random + '_text" placeholder="Button Text" value="Click Here" /><input type="text" class="no-border-top" name="link_' + random + '_url" placeholder="https://hellochurch.tech" /></div><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>');
	}
	if(type=='image'){
		$('.email-container').append('<div class="plan-item image draggable"><label>Image</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="image_' + random + '" id="image_' + random +'"></select><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>');
		populate_select('image_' + random, 'image');
	}
	if(type=='file'){
		$('.email-container').append('<div class="plan-item file draggable"><label>File</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="file_' + random + '" id="file_' + random +'"></select><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>');
		populate_select('file_' + random, 'file');
	}
	if(type=='event'){
		$('.email-container').append('<div class="plan-item event draggable"><label>Event</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="event_' + random + '" id="event_' + random +'"></select><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>');
		populate_select('event_' + random, 'event');
	}
	if(type=='plan'){
		$('.email-container').append('<div class="plan-item plan draggable"><label>Plan</label><a href=""><span class="material-symbols-outlined">drag_indicator</span></a><select name="plan_' + random + '" id="plan_' + random +'"></select><a href="javascript:;" class="delete-from-email warning"><span class="material-symbols-outlined">delete</span></a></div>');
		populate_select('plan_' + random, 'plan');
	}
	
	draggable_sortable();
	save_email();
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

$('body').on('click', 'a.delete-from-email', function() {
	$(this).parent().remove();
	save_email();
});

function handleSubmit(event) {
	
	event.preventDefault();
	
	$('.save-email').prop("value", "Saving...");
	
	let pEmail = $('.email-id').val();
	
	const data = new FormData(event.target);
	
	// Do a bit of work to convert the entries to a plain JS object
	const value = Object.fromEntries(data.entries());
	
	var json = JSON.stringify(value);
  
  	$.post( "/process/save-email", { emailID: pEmail, email: json }).done(function( data ) {
		setTimeout(function(){	
			$('.save-email').prop("value", "Saved!");
			preview_email();
		},200);
		setTimeout(function(){	
			$('.save-email').prop("value", "Save Email");
		},2000);
	});
}

const email_form = document.getElementById('form-email');
email_form.addEventListener('submit', handleSubmit);

$(document).ready(function(){
	$( ".sortable" ).sortable({
		revert: true
	});
	$( ".draggable" ).draggable({
		connectToSortable: ".sortable",
		revert: "invalid",
	});	
	preview_email();
});

function preview_email(){
	
	$('.email-preview').html('');
	
	let pEmail = $('.email-id').val();

	$.get( "/process/get-email", { emailID: pEmail }).done(function( data ) {
		$('.email-preview').html(data);
	});
}

function save_email(){
	
	$("input[type=submit].save-email").click();
	
}

function send_test(){
	
	let pID = $('#email_id').val();
	let pRecipient = $('#test_recipient').val();
	$('.loading').toggleClass('show');
	$.post( "/communication/send-email", { recipient: pRecipient, email_id: pID, email_test: true }).done(function( data ) {
		$('.loading p').text('Sent - Please Check Your Inbox' + data);
		//setTimeout(function() { $('.loading').toggleClass('show');$('.loading p').text('Loading'); }, 2000);
	});
	
}

function send_email(){
	
	let pID = $('#email_id').val();
	$('.loading').toggleClass('show');
	$.post( "/communication/send-email", $( "#send_email" ).serialize()).done(function( data ) {
		$('.loading p').text('Sent!');
		setTimeout(function() { 
			$('.loading').toggleClass('show');
			$('.loading p').text('Loading'); 
		}, 2000);
		location.href = '/communication/view-email?id=' + pID + '&msg=sent';
	});
	
}