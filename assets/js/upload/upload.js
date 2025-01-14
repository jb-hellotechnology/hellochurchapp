$('#upload').on('click', function() {
    var file_data = $('#file').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);  
    form_data.append('folderID', $('#folderID').val());
    form_data.append('contactID', $('#contactID').val());
    form_data.append('groupID', $('#groupID').val());
    form_data.append('eventID', $('#eventID').val()); 
    form_data.append('eventDate', $('#eventDate').val());  
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
	            $('#alert').html('<span class="material-symbols-outlined">check_circle</span> Success - refreshing...');
	            $('#alert').toggleClass('success hide');
	            setTimeout(function() { location.reload(); }, 2000);
            }else{
	            $('#alert').html('<span class="material-symbols-outlined">error</span>' + php_script_response);
	            $('#alert').toggleClass('error hide');
            }
            $('.loading').toggleClass('show'); 
        }
    });
});