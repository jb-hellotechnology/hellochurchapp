	<script src="/assets/js/jquery.3.7.1.min.js"></script>
	<script src="/assets/js/tagify/tagify.js"></script>
	<script>
	$('button.menu').click(function(){
		$('.main-nav-container').toggleClass('show');
	});
	var input = document.querySelector('.tagify');
	new Tagify(input, {
	    // options here
	});
	function resetPagination(){
		console.log(1);
		$("#page").val($("#page option:first").val());
	}
	$('input[type=checkbox].contact_select').click(function(){
		if($("input:checkbox.contact_select:checked").length > 0){
			$('.footer-form').addClass('show');		
		}else{
			$('.footer-form').removeClass('show');
		}
	});
	function confirm_contactDelete(){
		let text = "Are you sure you want to delete these contacts? This cannot be un-done.";
		if(confirm(text) == true) {
			var checked_contacts = $('.contact_select:checked').map(function () {
			  return $(this).data("contact");
			}).toArray();
			$.post( "/process/delete-contacts", { contacts: checked_contacts }, function( data ) {
				alert("Contacts Deleted");
				window.url = "/contacts";
			});
		}
	}
	function confirm_addTag(){
		let text = "Are you sure you want to tag these contacts?";
		if(confirm(text) == true) {
			var checked_contacts = $('.contact_select:checked').map(function () {
				return $(this).data("contact");
			}).toArray();
			var pTag = $('#tag').val();
			$.post( "/process/tag-contacts", { contacts: checked_contacts, tag: pTag }, function( data ) {
				console.log(data);
				alert("Contacts Tagged");
				window.url = "/contacts";
			});
		}
	}
	</script>
</body>
</html>