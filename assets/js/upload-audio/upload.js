$('#upload_audio').on('click', function(event) {
	event.preventDefault();
    var file_data = $('#form1_audioFile').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);  
    form_data.append('audioName', $('#form1_audioName').val()); 
    form_data.append('audioDate', $('#form1_audioDate').val()); 
    form_data.append('audioDescription', $('#form1_audioDescription').val());   
    form_data.append('audioSpeaker', $('#form1_audioSpeaker').val()); 
    form_data.append('audioSeries', $('#form1_audioSeries').val()); 
    form_data.append('audioBible', $('#form1_audioBible').val()); 
    $('.loading').toggleClass('show');                       
    $.ajax({
        url: '/process/audio-upload', // <-- point to server-side PHP script 
        dataType: 'text',  // <-- what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(php_script_response){
            if(php_script_response=='Success'){
	            window.location.href = "/media";
            }else{
	            alert('Error: ' + php_script_response);
            }
            $('.loading').toggleClass('show'); 
        }
    });
});