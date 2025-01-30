$('input[type=url').on('focusout', function(){
	let url = $(this).val();
	
	url = url.replace("http://", 'https://');

	var pattern = /^((https|ftp):\/\/)/;
	if(!pattern.test(url)) {
		url = "https://" + url;
	}
	
	$(this).val(url);
})