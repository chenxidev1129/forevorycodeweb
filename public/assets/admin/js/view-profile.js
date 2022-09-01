/* Stop current audio */ 
function stopAudio(){
    $(".icon-play-button").removeClass('active');

    $('audio').each(function(){
        this.pause(); // Stop playing
        this.currentTime = 0; // Reset time
    }); 
}

/* Crop model */
$('#cropper-modal').on('hidden.bs.modal', function (event) {

    setTimeout(function(){ 
        $('body').removeClass('modal-open');
    }, 1000);

});


/* view profile journey */
function loadProfileJourney() {

    $('#viewAllJourney').modal('show');
    $.ajax({
        type: "GET",
        data: { profile_id: profile_id },
        url: loadAdminProfileJournyUrl,
        beforeSend: function() {
            $('#viewAllJourneyDetail').html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
        },
        success: function (response) {

            if(response.success){
                $('#viewAllJourneyDetail').html(response.data.journey);
            }else{
                _toast.error(response.message); 
            }

        },
    });
}

/* Load profile media photos */
function loadProfileMediaPhotos() {
    stopAudio();
    $.ajax({
        url: loadProfileMediaPhotoAdminUrl,
        type: "GET", 
        data: { profile_id : profile_id },
        dataType: 'JSON',
        success: function (response)
        {
            if(response.success) {  
                $('#loadProfileMediaPhotos').html(response.html);  
            }else {
                _toast.error(data.message) 
            }

        }
    });     
}

/* Load profile media video */
function loadProfileMediaVideo() {
    stopAudio();
    $.ajax({
        type: "GET",
        data: { profile_id: profile_id },
        url: loadProfileMediaVideoAdminUrl,
        success: function (response) {

            if(response.success){
                $('#loadProfileMediaVideo').html(response.html);
            }else{
                _toast.error(response.message);
            }

        },
    });
}    


/* Load profile media audio */
var  page_num_load = 1;

function loadProfileMediaAudio() {

    stopAudio();
    page_num_load = 1;
    if(profile_id > 0){
        /* Show load more button */
        $.ajax({
            type: "GET",
            data: { profile_id: profile_id, limit: 3, page: page_num_load },
            url: loadProfileMediaAudioAdminUrl,
            success: function (response) {

                if(response.success){

                    /* Check if data is available */
                    if(response.html != ''){
                        /* remove default voice note click */ 
                        $(".viewMoreVoiceNotes").attr('onclick','loadMoreAudio()');
                        $('#voiceNotesList').html(response.html);
                        
                        if(parseInt(response.last_page)  > 1){
                           $(".viewMoreVoiceNotes").show();
                        }else{
                            $(".viewMoreVoiceNotes").hide();
                        }

                    }else{
                        $(".viewMoreVoiceNotes").attr('onclick','loadMoreDefaultAudio()');
                        $("#voiceNotesList").html(defaultVoiceNote);
                    }

                }else{
                    _toast.error(response.message);
                }
            },
        });
    }else{

        $(".viewMoreVoiceNotes").attr('onclick','loadMoreDefaultAudio()');
        /* Added default content for voice note */
        $("#voiceNotesList").html(defaultVoiceNote);
    }
}

/* Load more voice note  */ 
function loadMoreAudio() {
   
    stopAudio();
    /* Increment page count on every click*/
    page_num_load += 1;
    $.ajax({
        type: "GET",
        data: { profile_id:profile_id, limit: 3, page: page_num_load },
        url: loadProfileMediaAudioAdminUrl,
        beforeSend: function() {
            /* Added loader  */
            $('.voiceNotes_body').css('padding-right', '5px');
            $('#voiceNotesList').append('<div class="pageLoader"><span class="spinner-border"></span></div>');
        },
        success: function (response) {

            if(response.success){
                /* Fade out loader */
                $('#voiceNotesList .pageLoader').fadeOut();
                /* Scroll down */
                $("#voiceNotesList").animate({ scrollTop: $(document).height() }, 2000);
                $('#voiceNotesList').append(response.html);
                
                /* Hide load more button id no more data available */
                if (parseInt(response.last_page) <= parseInt(page_num_load)) {
                    $(".viewMoreVoiceNotes").hide();
                }

            }else{
                _toast.error(response.message);
            }

        },
    });
}

/* Load default voice note */
function loadMoreDefaultAudio(){
   
    $('#viewMoreVoiceNotes').hide();
    $('.voiceNotes_body').css('padding-right', '5px');
    $('#voiceNotesList').append('<div class="pageLoader"><span class="spinner-border"></span></div>');
    $('#voiceNotesList .pageLoader').fadeOut('1500');
    
    setTimeout(function() {

        $("#voiceNotesList").animate({ scrollTop: $(document).height() }, 2000);
        $('#voiceNotesList').append(loadMoreDefaultAudioView);

    }, 800);

}

/* audio player */
var timer;
var percent = 0;

var adminDefaultAdvance = function(duration, element, id, btn) {
    var progress = document.getElementById("barProgress");
    increment = 10 / duration
    percent = Math.min(increment * element.currentTime * 10, 100);
    progress.style.width = percent + '%'
    if(percent > 99){
        btn.classList.remove('active'); 
    }
    adminDefaultStartTimer(duration, element ,id, btn);
}

var adminDefaultStartTimer = function(duration, element ,id, btn) {
    if (percent < 100) {
        timer = setTimeout(function() {
            adminDefaultAdvance(duration, element ,id, btn)
        }, 100);
    }
}

function togglePlayDefaultAudio(e) {
    e = e || window.event;
    var btn = e.target;

    var audio = document.getElementById("defaultAudio");

    audio.addEventListener("playing", function(_event) {
        var id = audio.id;
        
        var duration = _event.target.duration;
        adminDefaultAdvance(duration, audio ,id, btn);
    });

    audio.addEventListener("pause", function(_event) {
        clearTimeout(timer);
    });

    if (!audio.paused) {
        btn.classList.remove('active');
        audio.pause();
        isPlaying = false;
    } else {
        btn.classList.add('active');
        audio.play();
        isPlaying = true;
    }
}

var articlePage = 1;
/* Hide read more article button */
$(".moreStories").hide();
/* Stories & article load view */

function loadProfileStoriesArticle() {
    articlePage = 1;
    
    if(profile_id > 0){
        $.ajax({
            type: "GET",
            data: { profile_id: profile_id, limit: 5, page: articlePage },
            url: loadProfileStoriesArticleAdminUrl,
            success: function (response) {

                if(response.success){
                    
                    if(response.html != ''){
                        $('#storiesArticleViewList').html(response.html);
                    }else{
                        $('#storiesArticleViewList').html(defaultStoriesArticle);
                    }

                    /* Show hide load more article button */
                    if(parseInt(response.last_page)  > 1){
                        $(".moreStories").show();
                    }else{
                        $(".moreStories").hide();
                    }

                }else{
                    _toast.error(response.message);
                }

            },
        });           
    }else{
        $("#storiesArticleViewList").html(defaultStoriesArticle);
    }

}

/* Load more stories article  */ 
function loadMoreStoriesArticle() {
    articlePage += 1;
    $.ajax({
        type: "GET",
        data: { profile_id: profile_id, limit: 5, page: articlePage },
        url: loadMoreStoriesArticleAdminUrl,
        beforeSend: function() {
            /* Added loader  */
            $('.stories_list').css('padding-right', '5px');
            $('#moreStories').append('<div class="pageLoader"><span class="spinner-border"></span></div>');
        },
        success: function (response) {

            if(response.success){
                $('#moreStories .pageLoader').fadeOut('1500');
                $(".list-unstyled").animate({ scrollTop: $(document).height() }, 2000);
                $('.stories_list ul li:last-child').after(response.html);
                
                /* Hide load more button id no more data available */
                if (parseInt(response.last_page) <= parseInt(articlePage)) {
                    $(".moreStories").hide();   
                }

            }else{
                _toast.error(response.message);
            }

        },
    });
}    
            
var guestBookPage = 1;
/* Hide load more guest book */
loadProfileGuestBook();

/* Guest book view */
function loadProfileGuestBook() {
    stopAudio()
    $(".loadMoreGuestBook").hide();
    guestBookPage = 1;
    if(profile_id > 0){
        
        $.ajax({
            type: "GET",
            data: { profile_id: profile_id, limit: 10, page: guestBookPage },
            url: loadProfileGuestBookAdminUrl,
            success: function (response) {

                if(response.success){
                    
                    if(response.html != ''){
                        $('#loadProfileGuestBook').html(response.html);
                    }else{
                        $(".loadMoreGuestBook").hide();
                        $('#loadProfileGuestBook').html("No guests have signed the guest book yet<p> Guest sign your guest book by scanning your QR code or going to your loved one's profile directly guest need to sign in or create a new account.</p>");
                    }
                    
                    /* Show hide load more guset book */
                    if(parseInt(response.last_page)  > 1){
                        $(".loadMoreGuestBook").show();
                    }else{
                        $(".loadMoreGuestBook").hide();
                    }

                }else{
                    _toast.error(response.message);
                }

            },
        });   

    }else{
        $("#loadProfileGuestBook").html(defaultGuestBook);
    }
}

/* Load more stories article  */ 
function loadMoreGuestBook() {   
    stopAudio();     
    guestBookPage += 1;
    $.ajax({
        type: "GET",
        data: { profile_id: profile_id, limit: 10, page: guestBookPage },
        url: loadProfileGuestBookAdminUrl,
        beforeSend: function() {
            $('.loadMore').append('<div class="pageLoader"><span class="spinner-border"></span></div>');
        },
        success: function (response) {
            
            if(response.success){
                
                $('.loadMore .pageLoader').fadeOut('1500');
                $("ul .list-unstyled").animate({ scrollTop: $(document).height() }, 2000);
                $('.guestBook ul li:last-child').after(response.html);
                
                /* Hide load more button if no more data available */
                if (parseInt(response.last_page) <= parseInt(guestBookPage)) {
                    $(".loadMoreGuestBook").hide();
                }

            }else{
                _toast.error(response.message);
            }

        },
    });
}    

/* Load gravesite detail */
loadGravesiteDetail();
/* Load gravesite detail page */
function loadGravesiteDetail() {
    $.ajax({
        url: loadGraveSiteDetailAdminUrl,
        type: "GET", 
        data: { profile_id: profile_id },
        dataType: 'JSON',
        success: function (response)
        {
            
            if(response.success){      
                $('#loadGravesiteDetail').html(response.html);
            }else{
                _toast.error(response.message) 
            }

        }
    });
}

/* view all prayers */
function viewAllPrayers() {
    stopAudio();
    $('#viewAllPrayers').modal('show');
    $.ajax({
        type: "GET",
        data: { profile_id: profile_id },
        url: loadViewAllPrayersAdminUrl,
        beforeSend: function() {
            $('#viewAllPrayersDetail').html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
        },
        success: function (response) {
            
            if(response.success){
                $('#viewAllPrayersDetail').html(response.html);
            }else{
                _toast.error(response.message);
                $('#viewAllPrayers').modal('hide');
            }

        },
    });
}

/* Load get profile options */
function editProfile() {
    stopAudio();
    $.ajax({
        url: loadEditProfileWindowAdminUrl,
        type: "get", 
        data: { profile_id: profile_id },
        dataType: 'JSON',
        success: function (response)
        {
            if(response.success){
                 
                $('aside.editProfile').html(response.data);
                /* Scroll edit profile side bar to top */ 
                $("#rightSidebar_body").animate({ scrollTop: 0 }, "slow");
                $('aside.editProfile').addClass('open');
                $('body').addClass('overflow-hidden');
                $('body').append('<div class="rightSidebar-overlay"></div>');
                // Show loved one text into detail page
                $("#onChangeLovedOneName").text($("#lovedProfileName").val());
                $('#pills-basicinfo-tab').tab('show');
                // Show birth and death date 
                var birthDate =  $("#birthDate").val();
                var deathDate =  $("#deathDate").val();
                $("#onChangeBirthDeathDate").text(birthDate  +' - '+  deathDate +'| Beloved Dad and Grandfather');    
                // Show short detail 
                $("#onChangeBirthDeathDate").text(birthDate  +' - '+  deathDate +' | ' +$("#onChangeShortDescription").val());
                
            }else{
                _toast.error(response.message) 
            }

        }
    });
}

/* Generate qr code */
function qrCode(){
    stopAudio();
    $.ajax({
        url: generateQrCodeAdminUrl,
        type: "get", 
        data: { profile_id: profile_id },
        dataType: 'JSON',
        success: function (response)
        {
            
            if(response.success){
                $('#qrCode .modal-body').html(response.data);
                $('#qrCode').modal('show');
            }else{
                _toast.error(response.message) 
            }

        }
    });
}

/* Maile qr code */
function getAnotherSticker(){
    $('#qrCode').modal('hide');
    
    bootbox.confirm({
    message: 'To get another sticker please reach out to Forevory at info@forevory.com',
        centerVertical:true,
        buttons: {
            cancel: {
                label: 'Cancel',
                className: 'btn btn-outline-primary ripple-effect'
            },
            confirm: {
                label: 'Okay',
                className: 'btn btn-primary ripple-effect'
            }
           
        },
        callback: function (result) {
          if(result){
            document.location = "mailto:info@forevory.com"
          }else{
            $('#qrCode').modal('show');
          }
        }
    });

}


/* view photos and videos modal show */
function viewPhotos() {
    $('#viewPhotos').modal('show');
}
/* view photos and videos modal show */
function viewVideos() {
    $('#viewVideos').modal('show');
}

/* view photos and videos slider function */
$('#viewPhotosSlider, #viewVideosSlider').carousel({
    touch:false,
})	

/* Remove uploaded images.  */
$(document).on('click', '.deleteImageRow', function(e) {
    e.preventDefault();
    var id =  $(this).attr('delete-image-row');

    $.ajax({
        url: removeUploadMediaAdminUrl,
        type: 'post', 
        data: { id: id, profile_id: profile_id, _token: $('meta[name="_token"]').attr('content') },
        dataType: 'JSON',
        success: function (data)
        {
            
            if(data.success){
                var dataIndex =  $("#deleteImageRow"+id).parent().parent().attr('data-index');
                $("#deleteImageRow"+id).parent().parent().remove()
                /* Remove image from array  */
                FILE_LIST.splice(dataIndex, 1);
                previewImages();  
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

/* Delete uploaded profile video .  */
$(document).on('click', '.deleteVideoRow', function(e) {
    
    e.preventDefault();
    var id =  $(this).attr('delete-video-row');
    
    $.ajax({
        url: removeUploadMediaAdminUrl,
        type: 'post', 
        data: { id: id, profile_id: profile_id, _token: $('meta[name="_token"]').attr('content') },
        dataType: 'JSON',
        success: function (data)
        {
            
            if(data.success){
                var dataIndex =  $("#deleteVideoRow"+id).parent().parent().attr('data-index');
                $("#deleteVideoRow"+id).parent().parent().remove()
                /* Remove video from array  */
                FILE_LIST_VIDEO.splice(dataIndex, 1);
                previewVideo();      
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

/* Delete uploaded profile voice note. */ 
$(document).on('click', '.deleteVoiceNote', function(e) {
    e.preventDefault();
    var id =  $(this).attr('id');         
    var attrId =  $(this).attr('deletevoicenoteindex');

    $.ajax({
        url: removeUploadMediaAdminUrl,
        type: 'post', 
        data: { id: attrId, _token: $('meta[name="_token"]').attr('content') },
        dataType: 'JSON',
        success: function (data)
        {
            
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

 /* Function used to remove article image  */
 $(document).on('click', '.deleteArticleRow', function(e) { 
   
    e.preventDefault();
    var id =  $(this).attr('deleteArticleIndex');

    if(id){ 

        $.ajax({
            url: removeArticleAdminUrl,
            type: 'post', 
            data: { id: id, _token: $('meta[name="_token"]').attr('content') },
            dataType: 'JSON',
            success: function (data)
            {
                
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