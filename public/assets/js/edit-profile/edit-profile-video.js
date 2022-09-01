/* Drag and drop multiple video */
var j = 1;
var INPUT_FILE_VIDEO = document.querySelector('.upload-files-video');
var FILES_LIST_CONTAINER_VIDEO = document.querySelector('#videosList');
var FILE_LIST_VIDEO = [];

var multipleEventsVideo = (element, eventNames, listener) => {
    var events = eventNames.split(' ');
    
    events.forEach(event => {
    element.addEventListener(event, listener, false);
    });
};

var previewVideo = () => {
    FILES_LIST_CONTAINER_VIDEO.innerHTML = '';

    if (FILE_LIST_VIDEO.length > 0) {
        FILE_LIST_VIDEO.forEach((addedFile, index) => {

        if(addedFile.id) {
                var content = `<div class="photoRow commonBox media align-items-center" data-index="${index}"><div class="pageLoader position-absolute d-none"><span class="spinner-border"></span></div><label class="photoRow_img mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center" title="${addedFile.name}"><div class="text-center d-none"><em class="icon-close"></em></div><input type="text" name="video_position[]" value="${addedFile.id}"><input type="file" onchange="updateUploadVideo((this), ${addedFile.id});"  accept="video/*" style="z-index: 1;"><div id="updateUploadVideo${addedFile.id}"><video class="form__image" controls="false"><source src="${addedFile.url}" type=video/mp4></video></div><span class="videoDuration" id="videoDuration_${addedFile.id}">${addedFile.videoDuration}</span></label><div class="media-body ml-sm-3"><div class="form-group"><label>Video Caption</label><input type="text" name="video_caption[${addedFile.id}]" value="${addedFile.caption}" class="form-control videoCaption" placeholder="Enter Video Caption"></div><div class="form-group" id="videoPreviewUrl${addedFile.id}"><a href="${addedFile.url}" data-fancybox="videos${index}" class="btn btn-outline-primary ripple-effect w-100">Preview Video</a></div></div><div class="action d-flex align-items-center"><a href="#" class="delete deleteRow deleteVideoRow" id="deleteVideoRow${addedFile.id}" delete-video-row="${addedFile.id}"> <em class="icon-delete"></em></a><a href="javascript:void(0);" class="bar"><em class="icon-bar"></em></a></div></div>`;
            } else {
                var content = `<div id="${addedFile.videoBoxId}" class="photoRow commonBox media align-items-center" data-index="${index}"><div class="pageLoader position-absolute"><span class="spinner-border"></span></div><label class="photoRow_img mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center" title="${addedFile.name}"><div class="text-center d-none"><em class="icon-close"></em></div><input type="text" name="video_position[]" value=""><input type="file" onchange="updateUploadVideo((this), ${addedFile.id});" accept="video/*"  style="z-index: 1;"><div id="updateUploadVideo${addedFile.id}"><video class="form__image" controls="false"><source id="updateUploadVideo${index}" src="${addedFile.url}" type=video/mp4></video></div><span class="videoDuration">${addedFile.videoDuration}</span></label><div class="media-body  ml-sm-3"><div class="form-group"><label>Video Caption</label><input type="text" name="video_caption[${index}]" value="${addedFile.caption}" class="form-control videoCaption" placeholder="Enter Video Caption"></div><div class="form-group" id="videoPreviewUrl${addedFile.id}"><a href="${addedFile.url}" data-fancybox="videos${index}" class="btn btn-outline-primary ripple-effect w-100">Preview Video</a></div></div><div class="action d-flex align-items-center"><a href="#" class="delete deleteRow deleteVideoRow" id="deleteVideoRow${index}" delete-video-row="${index}"> <em class="icon-delete"></em></a><a href="javascript:void(0);" class="bar"> <em class="icon-bar"></em></a></div></div>`;
            }

            FILES_LIST_CONTAINER_VIDEO.insertAdjacentHTML('beforeEnd', content);
        });
    } else {
    
        INPUT_FILE_VIDEO.value= "";
    }
}

var fileUploadVideo = () => {
    if (FILES_LIST_CONTAINER_VIDEO) {
        
        INPUT_FILE_VIDEO.addEventListener('change', () => {
            /*File uploading message on button submit */
            $("#editProfileDetailButton").attr('onclick', 'editProfileInProgress()');

            var files = [...INPUT_FILE_VIDEO.files];
            /*Get numebr of files */
            var videoFileCount = files.length;
        
            files.forEach(file => {
                var fileURL = URL.createObjectURL(file);
                var fileName = file.name;
                
                if (!file.type.match("video/")){
                _toast.error(file.name + " is not an video");
                
                }else{

                    var videoBoxContainId = 'content-video'+j;
                    /* Default array of video */ 
                    const uploadedFilesVideo = {
                        name: fileName,
                        url: fileURL,
                        id: '',
                        caption : '',
                        videoBoxId: videoBoxContainId,
                        videoDuration : '00:00',
        
                    };
                    /* Push default value to show */
                    FILE_LIST_VIDEO.push(uploadedFilesVideo);
                    previewVideo();

                    var videoform = new FormData();
                
                    videoform.append('video', file);
                    videoform.append('profile_id', profile_id);

                    $.ajax({
                        headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                        url: uploadMediaVideoUrl,
                        method:"POST",
                        data:videoform,
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(response)
                        { 
                            if(response.success){
                                    /*Decrement file count*/ 
                                    videoFileCount--;
                                    /*If all video are uploaded */ 
                                    if(videoFileCount == 0){
                                        $("#editProfileDetailButton").attr('onclick', 'editProfileDetail()');
                                    }
                                    // Update video position 
                                    $('#videosList #'+videoBoxContainId+' .photoRow_img input[name="video_position[]"]').val(response.data.id);
                                    // Update id to delete file
                                    $('#videosList #'+videoBoxContainId+' .action .deleteRow').attr('id', 'deleteVideoRow'+response.data.id);
                                    $('#videosList #'+videoBoxContainId+' .action .deleteRow').attr('delete-video-row', response.data.id);

                                    var removeVideoIndex = FILE_LIST_VIDEO.indexOf(uploadedFilesVideo);
                                    
                                    FILE_LIST_VIDEO.splice(removeVideoIndex, 1);
                                    
                                    // Add updated video detail to array.
                                    const updateVideoFiles = {
                                        name: fileName,
                                        url: response.data.media_with_url,
                                        thumb: response.data.media_thumbnail,
                                        id: response.data.id,
                                        caption : '',
                                        videoBoxId: '',
                                        videoDuration : response.data.duration
                                    };
                                
                                    FILE_LIST_VIDEO.push(updateVideoFiles);
                                    previewVideo();
            
                            }
                            
                        }, error: function (err) {
                            var errors = jQuery.parseJSON(err.responseText);
                            _toast.error(errors.message)
                        },
                    })
                    j++;
                } 
            });
        }); 
    }
};

fileUploadVideo();

/* Show uploaded video */

if(videoArray.length > 0 ){
    $.each(videoArray, function (i, elem) {
        if(elem.type == 'video'){
            
            var videoDuration = '';
            var caption = '';
            if(elem.caption){
                caption = elem.caption;
            }
            if(elem.duration){
                videoDuration = elem.duration;
            }
            const videoData = {
                name: elem.media,
                url: elem.media_with_url,
                thumb: elem.media_thumbnail,
                caption : caption,
                id: elem.id,
                videoDuration : videoDuration,
        
            };
            FILE_LIST_VIDEO.push(videoData);
        }
    });
    previewVideo();
}

/* Update uploaded video  */
function updateUploadVideo(inputVideo , id) {
    if (inputVideo.files && inputVideo.files[0]) {
        document.getElementById('videoDuration').value = '';
        if(inputVideo.files[0].type.match("video/")){

            var fileURL = URL.createObjectURL(inputVideo.files[0]);
            /* Created form instance */
            var formData = new FormData();
            formData.append('video', inputVideo.files[0]);
            formData.append('profile_id', profile_id);
            formData.append('id', id);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: updateMediaVideoUrl,
                type: "POST",
                data: formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                { 
                    
                    if(response.success){
                        /* Show updated video */
                        $('#updateUploadVideo'+id).html('<video class="form__image" controls="false"><source src="'+response.data.media_with_url+'" type="video/mp4"></video>');
                        /* Update video preview url */
                        $('#videoPreviewUrl'+id+' a').attr('href', response.data.media_with_url);
                        /* show video duration */
                        $('#videoDuration_'+id).html(response.data.duration);
                    }
                    _toast.success(response.message);    
                    
                }, error: function (err) {
                    var errors = jQuery.parseJSON(err.responseText);
                    _toast.error(errors.message)
                },
            })
    
        }else{
            _toast.error('Please upload a valid video only.');
        }
    }
}   


$(document).ready(function() {

    $("#videosList").sortable({
        handle: '.bar',
        cursor: 'move',
        axis: "y",
        start: function(e,ui)
        {
           /* Refresh position only for first drag */
            $(this).sortable("refreshPositions");
        }
    });
});
