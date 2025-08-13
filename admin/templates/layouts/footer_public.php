	<footer class="public">
		<p><a href="https://churchplanner.co.uk" title="Easy to use church software">Church Planner - Simple Software for Churches</a></p>
		<img src="https://churchplanner.co.uk/assets/images/church_planner.svg" alt="Church Planner" />
		<p>&copy; Hello Technology Ltd <?= date('Y') ?></p>
	</footer>
	
	<script src="/assets/js/jquery.3.7.1.min.js"></script>
	<script>
		$(document).ready(function () {
			$(document).on('click', '.button.primary', function () {
				event.preventDefault();
				if (validateCheckbox()){
					
				}
			});
		});
		
		function validateCheckbox() {
			if (!$('#form1_confirmedCorrect').is(':checked')) {
				alert("Please confirm your details are correct");
				return false;
			}else{
				return true;
			}
		}
	</script>
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
		
		$.post( "/public/send-magic-link", { c: church, e: email}).done(function( data ) {
			if(data == 'success'){
				$('#result').html('<p class="alert success"><span class="material-symbols-outlined">check_circle</span>Success - check your inbox for a sign in link</p>');
				$('input[type=submit]').hide();
			}else{
				$('#result').html('<p class="alert error"><span class="material-symbols-outlined">error</span>Error - please try again</p>');
			}
		});
		
	})
	</script>
	
	<!-- Fathom - beautiful, simple website analytics -->
	<script src="https://cdn.usefathom.com/script.js" data-site="AWHJKUYH" defer></script>
	<!-- / Fathom -->

</body>
</html>