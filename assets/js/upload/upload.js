$('#upload').on('click', function() {
    var file_data = $('#file').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);  
    form_data.append('folderID', $('#folderID').val());   
    $('.loading').toggleClass('show');                       
    $.ajax({
        url: '/process/documents-upload', // <-- point to server-side PHP script 
        dataType: 'text',  // <-- what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(php_script_response){
            if(php_script_response=='Success'){
	            location.reload(); 
            }else{
	            alert('Error: ' + php_script_response);
            }
            $('.loading').toggleClass('show'); 
        }
    });
});