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
	
	draggable_sortable();
	save_email();
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
	$.post( "/communication/send-email", { recipient: pRecipient, email_id: pID }).done(function( data ) {
		$('.loading p').text('Sent - Please Check Your Inbox');
		setTimeout(function() { $('.loading').toggleClass('show');$('.loading p').text('Loading'); }, 2000);
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