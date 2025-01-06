	<footer>
		
	</footer>
	<script src="/assets/js/jquery.3.7.1.min.js"></script>
<script>
function searchChurches(){
	
	let query = $('#q').val();
	
	$.get( "/public/search-church", { q: query }).done(function( data ) {
		$("#results").html(data);
	});
	
}	

$(document).on("click", '#results a', function(event) { 
	let slug = $(this).data('slug');
	$('#slug').val(slug);
	$('#email').show();
})

function activateForm(){
	
	$('input[type=submit]').removeAttr('disabled');
	
}

$('#signin').click(function(){
	
	let church = $('#slug').val();
	let email = $('#email_address').val();
	
	$.get( "/public/send-magic-link", { c: church, e: email}).done(function( data ) {
		if(data == 'success'){
			$('#result').html('<p class="alert success">Success! Check your inbox for a sign in link.</p>');
			$('input[type=submit]').hide();
		}else{
			$('#result').html('<p class="alert error">Error. Something went wrong.</p>');
		}
	});
	
})
</script>
</body>
</html>