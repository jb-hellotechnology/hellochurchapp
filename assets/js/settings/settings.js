$(document).ready(function(){
	$( ".sortable" ).sortable({
		revert: true
	});
	$( ".draggable" ).draggable({
		connectToSortable: ".sortable",
		revert: "invalid",
	});
	
	$( ".sortable-roles" ).sortable({
		revert: true,
		update: function () {
			$( ".sortable-roles li" ).each(function( index ) {
				$.post( "/process/save-roles-order", { roleID: $( this ).data('role'), order: index }).done();
			});
		}
	});
});