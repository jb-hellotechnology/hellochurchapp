$('#upload_podcast').on('click', function(event) {
	event.preventDefault();
    var file_data = $('#file').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);  
    $('.loading').toggleClass('show');                       
    $.ajax({
        url: '/process/podcast-image-upload', // <-- point to server-side PHP script 
        dataType: 'text',  // <-- what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(php_script_response){
            if(php_script_response=='Success'){
	            window.location.href = "/settings/podcast";
            }else{
	            alert('Error: ' + php_script_response);
            }
            $('.loading').toggleClass('show'); 
        }
    });
});