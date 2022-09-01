/* Stop current audio */ 
function stopAudio(){
    
    $(".icon-play-button").removeClass('active');
    $('audio').each(function(){
        this.pause(); // Stop playing
        this.currentTime = 0; // Reset time
    }); 
}

/* View all plan */
function viewPlan(id='') {

    $('#viewPlan').modal('show');
    $.ajax({
        type: "GET",
        data: { id:id },
        url: renewSubscriptionUrl,
        success: function (data) {

            if(data.success){
                $('#loadSubscriptionPlan').html(data.html);
            }else{
                _toast.error(data.message);
                $('#loadSubscriptionPlan').modal('hide');
            }

        },error: function (err) {
            var errors = jQuery.parseJSON(err.responseText);
            _toast.error(errors.message)
        },
    });

}

$('#cropper-modal').on('hidden.bs.modal', function (event) {

    setTimeout(function(){ 
        $('body').removeClass('modal-open');
    }, 1000);
    
});


/* Load gravesite detail */
loadGravesiteDetail();

/* Load gravesite detail page */
function loadGravesiteDetail() {

    $.ajax({
        url: loadGraveSiteDetailUrl,
        type: "GET", 
        data: {profile_id : profile_id},
        dataType: 'JSON',
        success: function (response)
        {
            if(response.success) {      
                $('#loadGravesiteDetail').html(response.html);   
            }else {
                _toast.error(response.message) 
            }
        }
    });
}

/* Load profile media photos */
function loadProfileMediaPhotos() {
    /* Stop playing audio */
    stopAudio();
    
    $.ajax({
        url: loadProfileMediaPhotoUrl,
        type: "GET", 
        data: {profile_id : profile_id},
        dataType: 'JSON',
        success: function (response){

            if (response.success) {  
            
                if((response.status)){
                    location.reload('profile');
                }
                $('#loadProfileMediaPhotos').html(response.html);
                
            } else {
                _toast.error(data.message) 
            }

        }
    });
}

/* View all prayers */
function viewAllPrayers() {
    
    stopAudio();

    $('#viewAllPrayers').modal('show');
    $.ajax({
        type: "GET",
        data: { profile_id: profile_id },
        url: loadViewAllPrayersUrl,
        beforeSend: function() {
            $('#viewAllPrayersDetail').html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
        },
        success: function (response) { 

            if((response.status)){
                location.reload('profile');
            }

            if(response.success){
                $('#viewAllPrayersDetail').html(response.html);
            }else{
                _toast.error(response.message);
                $('#viewAllPrayers').modal('hide');
            }

        },
    });
}

/* View profile journey */
function loadProfileJourney() {

    $('#readMoreModal').modal('show');
    $.ajax({
        type: "GET",
        data: {profile_id:profile_id},
        url: loadProfileJourneyUrl,
        beforeSend: function() {
            $('#viewAllJourneyDetail').html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
        },
        success: function (response) {

            if((response.status)){
                location.reload('profile');
            }

            if(response.success){   
                $('#viewAllJourneyDetail').html(response.data.journey);
            }else{
                _toast.error(response.message);
                
            }

        },
    });
}

/* Load profile media video */
function loadProfileMediaVideo() {
    
    stopAudio();

    $.ajax({
        type: "GET",
        data: {profile_id:profile_id},
        url: loadProfileMediaVideoUrl,
        success: function (response) {

            if(response.success){
               
                if((response.status)){
                    location.reload('profile');
                }
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
    
    page_num_load = 1;
    if(profile_id > 0){

        $.ajax({
            type: "GET",
            data: {profile_id:profile_id,limit: 3, page: page_num_load},
            url: loadProfileMediaAudioUrl,
            success: function (response) {

                if(response.success){
                    
                    if((response.status)){
                        location.reload('profile');
                    }

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
    
    /* Increment page count on every click*/
    stopAudio();
    page_num_load += 1;
  
    $.ajax({
        type: "GET",
        data: {profile_id: profile_id, limit: 3, page: page_num_load},
        url: loadProfileMediaAudioUrl,
        beforeSend: function() {
            /* Added loader  */
            $('.voiceNotes_body').css('padding-right', '5px');
            $('#voiceNotesList').append('<div class="pageLoader"><span class="spinner-border"></span></div>');
        },
        success: function (response) {

            if(response.success){
                
                if((response.status)){
                    location.reload('profile');
                }  

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
        $('#voiceNotesList').append(laodMoreDefaultVoiceNote);
    }, 800);

}


/* Audio player */
var timer;
var percent = 0;

var defaultAdvance = function(duration, element, id, btn) {

    var progress = document.getElementById("barProgress");
    increment = 10 / duration
    percent = Math.min(increment * element.currentTime * 10, 100);
    progress.style.width = percent + '%'

    if(percent > 99){
        btn.classList.remove('active'); 
    }

    defaultStartTimer(duration, element ,id, btn);
}

var defaultStartTimer = function(duration, element ,id, btn) {

    if (percent < 100) {
        timer = setTimeout(function() {
            defaultAdvance(duration, element ,id, btn)
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
        defaultAdvance(duration, audio ,id, btn);
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
    
    stopAudio();
    articlePage = 1;
    
    if(profile_id > 0){

        $.ajax({
            type: "GET",
            data: {profile_id:profile_id, limit: 5, page: articlePage},
            url: loadProfileStoriesArticleUrl,
            success: function (response) {

                if(response.success){

                    if((response.status)){
                        location.reload('profile');
                    }

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
        data: {profile_id:profile_id, limit: 5, page: articlePage},
        url: loadMoreProfileStoriesArticleUrl,
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

loadProfileGuestBook();

/* Guest book view */
function loadProfileGuestBook() {
    
    $(".loadMoreGuestBook").hide();
    guestBookPage = 1;

    if(profile_id > 0){

        $.ajax({
            type: "GET",
            data: { profile_id:profile_id, limit: 10, page: guestBookPage },
            url: loadProfileGuestBookUrl,
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
    
    guestBookPage += 1;
    $.ajax({
        type: "GET",
        data: {profile_id:profile_id, limit: 10, page: guestBookPage},
        url: loadProfileGuestBookUrl,
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
