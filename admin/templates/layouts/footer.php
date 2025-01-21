	<div class="loading">
		<div>
			<p>Please Wait</p>
			<span class="material-symbols-outlined">autorenew</span>
		</div>
	</div>
	<!-- Brevo Conversations {literal} -->
	<script>
	    (function(d, w, c) {
	        w.BrevoConversationsID = '670cfad6dbb2b99a030ff718';
	        w[c] = w[c] || function() {
	            (w[c].q = w[c].q || []).push(arguments);
	        };
	        var s = d.createElement('script');
	        s.async = true;
	        s.src = 'https://conversations-widget.brevo.com/brevo-conversations.js';
	        if (d.head) d.head.appendChild(s);
	    })(document, window, 'BrevoConversations');
	</script>
	<!-- /Brevo Conversations {/literal} -->
	
	<script src="/assets/js/jquery.3.7.1.min.js"></script>
	<script src="/assets/js/jquery-ui.1.14.0.js"></script>
	<script src="/assets/js/tagify/tagify.js"></script>
	<script src="/assets/js/fullcalendar/dist/index.global.min.js"></script>
	<script src="/assets/js/plan/plan.js?v=<?= rand() ?>"></script>
	<script src="/assets/js/email/email.js?v=<?= rand() ?>"></script>
	<script src="/assets/js/upload/upload.js?v=<?= rand() ?>"></script>
	<script src="/assets/js/upload-audio/upload.js?v=<?= rand() ?>"></script>
	<script src="/assets/js/upload-podcast-image/upload.js?v=<?= rand() ?>"></script>
	<script src="/assets/redactor/redactor.min.js"></script>
	
	<script>
	$('button.menu').click(function(){
		$('.main-nav-container').toggleClass('show');
	});
	$('button.account-nav-button').click(function(){
		$('.account-nav').toggleClass('show');
	});
	
	var tagify = document.querySelector('.tagify');
	new Tagify(tagify, {});
	
	var roles = document.querySelector('.roles');
	new Tagify(roles, {
		enforceWhitelist: true,
	    whitelist : <?= hello_church_roles_tagify() ?>
	});
	
	var venues = document.querySelector('.venues');
	new Tagify(venues, {
		enforceWhitelist: true,
	    whitelist : <?= hello_church_venues_tagify() ?>
	});
	
	var groups = document.querySelector('.groups-tagify');
	new Tagify(groups, {
		enforceWhitelist: true,
	    whitelist : <?= hello_church_groups_tagify() ?>,
	    dropdown: {
            maxItems: 20,           // <- mixumum allowed rendered suggestions
            classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
            enabled: 0,             // <- show suggestions on focus
            closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
        }
	});
	
	var contacts = document.querySelector('.contacts-tagify');
	new Tagify(contacts, {
		enforceWhitelist: true,
	    whitelist : <?= hello_church_contacts_tagify() ?>,
	    dropdown: {
            maxItems: 20,           // <- mixumum allowed rendered suggestions
            classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
            enabled: 0,             // <- show suggestions on focus
            closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
        }
	});
	
	function resetPagination(){
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
				window.location.href = "/contacts";
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
				alert("Contacts Tagged");
				window.location.href = "/contacts";
			});
		}
	}
	
	function searchContacts(){
		let pQ = $('#q').val();
		let pID = $('#q').data('group-id');
		if(pQ.length>2){
			$.post( "/process/search-contacts", { q: pQ, groupID: pID }, function( data ) {
				if(data){
					$('.results').html(data).show();
				}
			});
		}else{
			$('.results').hide();
		}
	}
	
	function searchRoleContacts(pRole){
		let pQ = $('#'+pRole+' .q').val();
		let pID = $('#'+pRole+' .q').data('event-id');
		let pDate = $('#'+pRole+' .q').data('event-date');
		let pRoleID = $('#'+pRole+' .q').data('event-role');
		if(pQ.length>2){
			$.post( "/process/search-role-contacts", { q: pQ, eventID: pID, date: pDate, roleID: pRoleID }, function( data ) {
				if(data){
					$('#'+pRole+' .results').html(data).show();
				}
			});
		}else{
			$('#'+pRole+' .results').hide();
		}
	}
	
	$('input').blur(function(){
		$('.results').delay(1000).fadeOut();
	});
	
	$('#currency').change(function(){
		var currency = $("#currency option:selected").val();
		$('.currency').hide();
		$('.currency.'+currency).show();
	})

	$R('.redactor', {
	  "buttons": ["format", "bold", "italic", "lists", "link"],
	  "linkNewTab": true,
	  "toolbarFixed": true,
	  "formatting": ["p", "h2", "h3"]
	});
    </script>
</body>
</html>