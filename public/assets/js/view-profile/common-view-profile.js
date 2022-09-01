
/* Scroll guest signed book to top */
function signGuestBook(){
    $('#pills-tab #pills-voiceNotes-tab').click();
    $("html, body").animate({ scrollTop: 500 }, 1000);
} 


/* Load voice note model */
function recordVoiceNote() {
    /* Stop audio if playing */
    stopAudio();
    $('#voiceRecord').modal('show');

    $.ajax({
        type: "GET",
        data: { profile_id:profile_id },
        url: voiceNoteRecordingModelUrl,
        success: function (data) {
           
            if((data.status)){
                location.reload('profile');
            }
            if(data.success){
                $('#loadVoiceNoteModel').html(data.html);
                
            }else{
                _toast.error(data.message);
                $('#loadVoiceNoteModel').modal('hide');
            }

        },
    });
}

// view photos and videos modal show
function viewPhotos() {
    $('#viewPhotos').modal('show');
}

function viewVideos() {
    $('#viewVideos').modal('show');
}

// view photos and videos slider function
$('#viewPhotosSlider, #viewVideosSlider').carousel({
    touch:false,
})	


/* Remove uploaded images.  */
$(document).on('click', '.deleteImageRow', function(e) {
    e.preventDefault();
    var id =  $(this).attr('delete-image-row');

    $.ajax({
        url: removeUploadMediaUrl,
        type: 'post', 
        data: { id: id, profile_id: profile_id, _token: $('meta[name="_token"]').attr('content') },
        dataType: 'JSON',
        success: function (data)
        {
            
            if (data.success) {
                var dataIndex =  $("#deleteImageRow"+id).parent().parent().attr('data-index');
                $("#deleteImageRow"+id).parent().parent().remove()
                /* Remove image from array  */
                FILE_LIST.splice(dataIndex, 1);
                previewImages();  
                _toast.success(data.message);         
            }else {
                _toast.error(data.message) 
            }
            
        }, error: function (err) {
            var errors = jQuery.parseJSON(err.responseText);
            _toast.error(errors.message)
        },
    });
    
});

/* Delete uploaded profile video .  */
$(document).on('click', '.deleteVideoRow', function(e) {

    e.preventDefault();
    var id =  $(this).attr('delete-video-row');

    $.ajax({
        url: removeUploadMediaUrl,
        type: 'post', 
        data: {id : id, profile_id: profile_id, _token : $('meta[name="_token"]').attr('content') },
        dataType: 'JSON',
        success: function (data)
        {
            if(data.success) {
                var dataIndex =  $("#deleteVideoRow"+id).parent().parent().attr('data-index');
                $("#deleteVideoRow"+id).parent().parent().remove()
                /* Remove video from array  */
                FILE_LIST_VIDEO.splice(dataIndex, 1);
                previewVideo();      
                _toast.success(data.message);         
            }else {
                _toast.error(data.message) 
            }
            
        }, error: function (err) {
            var errors = jQuery.parseJSON(err.responseText);
            _toast.error(errors.message)
        },
    });
    
});

/* Delete uploaded profile voice note. */ 
$(document).on('click', '.deleteVoiceNote', function(e) {
    e.preventDefault();
    
    var id =  $(this).attr('id');         
    var attrId =  $(this).attr('deletevoicenoteindex');

    $.ajax({
        url: removeUploadMediaUrl,
        type: 'post', 
        data: {id : attrId, _token : $('meta[name="_token"]').attr('content') },
        dataType: 'JSON',
        success: function (data){

            if(data.success){
                var audioDataIndex =  $("#"+id).parent().parent().attr('data-audio-index');
                $("#"+id).parent().parent().remove();
                /* Remove audio from array  */
                FILE_LIST_AUDIO.splice(audioDataIndex, 1);
                previewAudio();   
                _toast.success(data.message);         
            }else{
                _toast.error(data.message) 
            }
            
        }, error: function (err) {
            var errors = jQuery.parseJSON(err.responseText);
            _toast.error(errors.message)
        },
    });
    
});

/* Remove article image  */
$(document).on('click', '.deleteArticleRow', function(e) { 
    e.preventDefault();
    var id =  $(this).attr('deleteArticleIndex');

    if(id){ 

        $.ajax({
            url: removeArticleUrl,
            type: 'post', 
            data: {id : id, _token : $('meta[name="_token"]').attr('content') },
            dataType: 'JSON',
            success: function (data){

                if(data.success){
                    $("#deleteArticleIndex"+id).parent().parent().remove();
                    _toast.success(data.message);         
                }else{
                    _toast.error(data.message) 
                }
                
            }, error: function (err) {
                var errors = jQuery.parseJSON(err.responseText);
                _toast.error(errors.message)
            },
        });

    }else{
        $(this).parent().parent().remove();
    }
       
});

/* On journey click stop audio  */
$(document).ready(function() {
    $('#pills-basicinfo-tab').click(function(){
        stopAudio();
    });
}); 
