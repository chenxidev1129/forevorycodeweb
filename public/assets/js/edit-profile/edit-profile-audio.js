/* Upload audio on browese file */
var audioIncrement = 1;
var INPUT_FILE_AUDIO = document.querySelector('.upload-audio-files');
var FILES_LIST_CONTAINER_AUDIO = document.querySelector('#audioList');
var FILE_LIST_AUDIO = [];

var multipleEventsAudio = (element, eventNames, listener) => {
    var events = eventNames.split(' ');
    
    events.forEach(event => {
    element.addEventListener(event, listener, false);
    });
};

var previewAudio = () => {
    FILES_LIST_CONTAINER_AUDIO.innerHTML = '';

    if (FILE_LIST_AUDIO.length > 0) {
        FILE_LIST_AUDIO.forEach((addedFile, index) => {
            var contentDiv = `<div class="profile bg-primary">${addedFile.first_name}${addedFile.last_name}</div>`;
            if(addedFile.user_image != ''){
                var contentDiv = '<div class="profile"><img src="'+addedFile.user_image+'"></div>';
            }
            if(addedFile.id) {

                var content = `<div class="voiceNotes_info position-relative" data-audio-index="${index}"><div class="pageLoader position-absolute d-none"><span class="spinner-border"></span></div><div class="d-flex">${contentDiv}<div class="caption d-flex align-items-center"><div class="caption_top"><div class="row align-items-center mb-2 no-gutters"><div class="col-sm-11"><div class="title"><h5 class="font-bd text-truncate mb-0">${addedFile.captionText}</h5><span class="font-rg d-none d-sm-inline-block">${addedFile.uploadDate}</span><span class="font-rg d-inline-block d-sm-none">11/14/2020</span></div></div><div class="col-sm-1 text-right"><span class="duration">${addedFile.audioDuration}</span></div></div><div class="audioBar "><div class="barProgress" id="barProgressaudio${addedFile.id}"></div><audio id="audio${addedFile.id}" src="${addedFile.url}"></audio></div></div></div><a href="javascript:void(0);" class="videoPlay rounded-circle" onclick="togglePlay()"><em class="icon-play-button" id="play-${addedFile.id}" audio-play-id="${addedFile.id}"></em></a></div><div class="action d-flex align-items-center"><a href="javascript:void(0);" class="delete deleteVoiceNote"  id="deleteVoiceNoteIndex${addedFile.id}" deletevoicenoteindex="${addedFile.id}"> <em class="icon-delete"></em></a></div><div class="form-group mb-0 mt-2"><label>Voice Note Caption</label><input type="hidden" name="audio_caption_id[]" value="${addedFile.id}"> <input type="text" class="form-control audioCaption" name="audio_caption[${index}]" value="${addedFile.caption}" placeholder="Enter Voice Note Caption"></div></div>`;
            } else {
                var content = `<div id="${addedFile.boxId}" class="voiceNotes_info position-relative" data-audio-index="${index}"><div class="pageLoader position-absolute"><span class="spinner-border"></span></div><div class="d-flex">${contentDiv}<div class="caption d-flex align-items-center"><div class="caption_top"><div class="row align-items-center mb-2 no-gutters"><div class="col-sm-11"><div class="title"><h5 class="font-bd text-truncate mb-0">${addedFile.captionText}</h5><span class="font-rg d-none d-sm-inline-block">${addedFile.uploadDate}</span><span class="font-rg d-inline-block d-sm-none">11/14/2020</span></div></div><div class="col-sm-1 text-right"><span class="duration">${addedFile.audioDuration}</span></div></div><div class="audioBar "><div class="barProgress" id="barProgressaudio${index}"></div><audio id="audio${index}" src="${addedFile.url}"></audio></div></div></div><a href="javascript:void(0);" class="videoPlay rounded-circle" onclick="togglePlay()"><em class="icon-play-button" id="play-${index}" audio-play-id="${index}"></em></a></div><div class="action d-flex align-items-center"><a href="javascript:void(0);" class="delete deleteVoiceNote" id="deleteVoiceNoteIndex${index}" deletevoicenoteindex="${index}"> <em class="icon-delete"></em></a></div><div class="form-group mb-0 mt-2 ${addedFile.boxId}"><label>Voice Note Caption</label><input type="hidden" name="audio_caption_id[]" value=""><input type="text" class="form-control audioCaption" name="audio_caption[${index}]" value="" placeholder="Enter Voice Note Caption"></div></div>`;
            }

            FILES_LIST_CONTAINER_AUDIO.insertAdjacentHTML('beforeEnd', content);
        });
    } else {

        INPUT_FILE_AUDIO.value= "";
    }
}

var AudiofileUpload = () => {
    if (FILES_LIST_CONTAINER_AUDIO) {
    
        INPUT_FILE_AUDIO.addEventListener('change', () => {
            /*File uploading message on button submit */
            $("#editProfileDetailButton").attr('onclick', 'editProfileInProgress()');
            var files = [...INPUT_FILE_AUDIO.files];
            var audioFilesCount = files.length;
            files.forEach(file => {

            var fileURL = URL.createObjectURL(file);

            /* Assign dynamic id to parent box */
            var audioBoxContainId = 'audioContent'+audioIncrement;
            
                if (file.type.match("audio/")){
                
                /* Created instance of audio  */
                    var audio = new Audio();
                    /* Once the metadata has been loaded, get the audio duration  */
                    $(audio).on("loadedmetadata", function(){
                    
                        var seconds = audio.duration;
                        var duration = moment.duration(seconds, "seconds");
                            
                        var time = "";
                        var hours = duration.hours();
                        if (hours > 0) { time = hours + ":" ; }
                            
                        time = time + duration.minutes() + ":" + duration.seconds();
                        /* Set audio duration to form input  */
                        $("#audioDuration").val(time);
                    
                    }); 

                    audio.src = fileURL;  
                    /* Wait to get updated audio duration */
                    setTimeout(function(){      
                        var audioDuration = document.getElementById('audioDuration').value
                        /* Get todate date and formate  */
                        var getDate = new Date();
                        var uploadDate = formatDate(getDate);
                        /* Add box on drop area  */
                        const uploadedAudioFiles = {
                            url: fileURL,
                            user_image : '',
                            first_name :'',
                            last_name:'',
                            caption : '',
                            captionText : '',
                            id: '',
                            boxId: audioBoxContainId,
                            audioDuration: audioDuration,
                            uploadDate : uploadDate,
                        };
                        /* Push audio array value into final array. */
                        FILE_LIST_AUDIO.push(uploadedAudioFiles);
                        previewAudio();
                        /* Created form instance. */
                        var form = new FormData();
                    
                        form.append('audio', file);
                        form.append('profile_id', profile_id);
                        form.append('duration', audioDuration);

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            url: uploadVoiceNoteUrl,
                            type: "POST",
                            data:form,
                            dataType:'JSON', 
                            contentType: false,
                            cache: false,
                            processData: false,
                            success:function(response)
                            { 
                                if(response.success){
                                    setTimeout(function(){ 
                                        /*Decrement file count */ 
                                        audioFilesCount--;
                                        /*If all voice note are uploaded */ 
                                        if(audioFilesCount == 0){
                                            $("#editProfileDetailButton").attr('onclick', 'editProfileDetail()');
                                        }
                                        /* Removed loader from box.  */
                                        $('#audioList #'+audioBoxContainId+' .pageLoader').addClass('d-none');
                                        $('#audioList #'+audioBoxContainId+' .'+audioBoxContainId+' input[name="audio_caption_id[]"]').val(response.data.id);
                                        $('#audioList #'+audioBoxContainId+' .action .delete').attr('id', 'deletevoicenoteindex'+response.data.id);
                                        $('#audioList #'+audioBoxContainId+' .action .delete').attr('deletevoicenoteindex',  response.data.id);
                                        /* Remove uploaded voice note from array. */
                                        var removeAudioIndex =  $("#"+audioBoxContainId).attr('data-audio-index');
                                        FILE_LIST_AUDIO.splice(removeAudioIndex, 1);
                                        var last_name = '';
                                        var user_image = '';
                                        if(response.data.user.last_name != null){
                                            last_name = response.data.user.last_name.charAt(0);
                                        } 
                                        if(response.data.user.image != null){
                                            user_image = response.data.user.image_url;
                                        } 
                                        /* Add voice note to array with updated id. */
                                        const updateAudioFiles = {
                                            first_name : response.data.user.first_name.charAt(0),
                                            last_name : last_name,
                                            user_image : user_image,
                                            url: fileURL,
                                            caption : '',
                                            captionText : '',
                                            id: response.data.id,
                                            boxId: '',
                                            audioDuration: audioDuration,
                                            uploadDate : uploadDate,
                                        };
                                        
                                        /* Push voice note with updated primary id. */
                                        FILE_LIST_AUDIO.push(updateAudioFiles);
                                        /* Load profile voice note on profile page */
                                        previewAudio();
                                    }, 1000);
                                }
                            }, error: function (err) {
                                var errors = jQuery.parseJSON(err.responseText);
                                _toast.error(errors.message)
                            },
                        })
                        audioIncrement++;
                        /* Final list of uploaded audio files */
                    
                    }, 500);        
                        
                }else{
                    _toast.error('File format not accepted, please upload MP3, M4A, or WAV file format');
                }     
            
            });
        }); 
    }
};

AudiofileUpload();

/* Show uploaded voice note. */

$.each(audioArray, function (i, elem) {
    if(elem.type == 'audio'){
        var caption = '';
        var captionText = '';
        var duration = '';
        if(elem.caption){
            caption = elem.caption;
            captionText = elem.caption;
            if(caption.length > 20){
                captionText = caption.substring(0,20) + '.....';
            }
            
        }
        if(elem.duration){
            duration = elem.duration;
        }
        /* Get date and formate into Month day and year. */
        var getDate = new Date(elem.created_at);
        var uploadDate = formatDate(getDate);
        var first_name = '';
        var last_name = '';
        var user_image = '';
        if(elem.user) {
            if(elem.user.first_name != null){
                first_name = elem.user.first_name.charAt(0);
            }
            if(elem.user.last_name != null){
                last_name = elem.user.last_name.charAt(0);
            } 
            if(elem.user.image != null){
                user_image = elem.user.image_url;
            } 
        }
        const audioData = {
            first_name : first_name,
            last_name : last_name,
            user_image : user_image,
            url: elem.media_with_url,
            caption : caption,
            captionText : captionText,
            id: elem.id,
            audioDuration: duration,
            uploadDate : uploadDate,
    
        };
        FILE_LIST_AUDIO.push(audioData);
    }
});
previewAudio();

/* Function used to convert date into month long day numeric and year numeric */
function formatDate(getDate){
    var options = {  year: 'numeric', month: 'long', day: 'numeric' };
    return uploadDate = getDate.toLocaleDateString("en-US", options)
}   


  /* Audio player */
  var timer;
  var percent = 0;
  var currentSideBarAudio = null;

  var advance = function(duration, element, id ,btn) {
    
      var progress = document.getElementById("barProgress"+id);
      
      increment = 10 / duration
      percent = Math.min(increment * element.currentTime * 10, 100);
      progress.style.width = percent + '%'
      if(percent > 99){
          btn.classList.remove('active'); 
      }
      startTimer(duration, element, id, btn);
  }
  
  var startTimer = function(duration, element ,id ,btn) {
      if (percent < 100) {
          timer = setTimeout(function() {
              advance(duration, element, id, btn)
          }, 100);
        
      }
  }

  function togglePlay(e) {
      e = e || window.event;
      var btn = e.target;

      var sideBarAudioId = $(e.target).attr("audio-play-id");
   
      var audio = document.getElementById("audio"+sideBarAudioId);
     
      audio.addEventListener("pause", function(_event) {
          clearTimeout(timer);
      });
     
      audio.addEventListener("playing", function(_event) {
   
          var id = audio.id;
          var duration = _event.target.duration;
          advance(duration, audio , id, btn);
          
      });

      if(currentSideBarAudio != null && audio !=currentSideBarAudio){
         
          currentSideBarAudio.pause();
          clearTimeout(currentSideBarAudio);
          isPlaying = false;

          $(".icon-play-button").removeClass('active');
          $("#play-"+sideBarAudioId).addClass('active');

          audio.play();
          isPlaying = true;
          currentSideBarAudio = audio;

      }else{

          if (!audio.paused) {
            //  btn.classList.remove('active');
              $("#play-"+sideBarAudioId).removeClass('active');
              audio.pause();
              isPlaying = false;

          } else {

              //btn.classList.add('active');
              $("#play-"+sideBarAudioId).addClass('active');
              audio.play();
              isPlaying = true;
              currentSideBarAudio = audio;
          }
      }

  }

