@if(!empty($getProfileDetail) && count($getProfileDetail) > 0)
    @foreach($getProfileDetail as $audioRow)
    <div class="voiceNotes_info d-flex position-relative">
        <div class="profile @if(empty($audioRow->image)) bg-primary @endif">
            @if(!empty($audioRow->image))  <img src="{{ getUploadMedia($audioRow->image) }}" alt="image"> @else {{ ucFirst($audioRow->first_name[0].ucFirst(@$audioRow->last_name[0])) }} @endif
        </div>
        <div class="caption d-flex align-items-center">
            <div class="caption_top">
                <div class="row align-items-center mb-2 no-gutters">
                    <div class="col-sm-11">
                        <div class="title">
                            <h5 class="font-bd text-truncate mb-0">{{ $audioRow->caption}}</h5>
                            <span
                                class="font-rg d-none d-sm-inline-block">{{ getConvertedDate($audioRow->created_at, 2) }}</span> <span
                                class="font-rg d-inline-block d-sm-none"></span>
                        </div>
                    </div>
                    <div class="col-sm-1 text-right">
                        <span class="duration">{{ $audioRow->duration}}</span>
                    </div>
                </div>
                <div class="audioBar ">
                    <div class="barProgress" id="barProgressvoice{{ $audioRow->id}}"></div>
                    <audio id="voice{{ $audioRow->id}}" 
                        src="{{ getUploadMedia($audioRow->media) }}"></audio>
                </div>
            </div>
        </div>
        <a href="javascript:void(0);" onClick="toggleAudioPlay()"
            class="videoPlay rounded-circle" data-action="play" >
            <em class="icon-play-button" id="playVoiceNote-{{ $audioRow->id}}" play-voice-note="{{ $audioRow->id}}" ></em>
        </a>
    </div>
    @endforeach
@endif

<script>

    /* Audio player js */
    var audioTimer;
    var audioPercent = 0;
    var currentAudio = null;
    var btn = null;
   
 
    var advance = function(duration, element ,id, btn) {
        var progress = document.getElementById("barProgress"+id);
        increment = 10 / duration
        audioPercent = Math.min(increment * element.currentTime * 10, 100);
        progress.style.width = audioPercent + '%'
        if(audioPercent > 99){
            btn.classList.remove('active'); 
        }
        startTimer(duration, element ,id, btn);
    }
    var startTimer = function(duration, element ,id, btn) {
        if (audioPercent < 100) {
            audioTimer = setTimeout(function() {
                advance(duration, element ,id, btn)
            }, 100);
        }
    }

    function toggleAudioPlay(e) {
        e = e || window.event;
        var btn = e.target;
        
        var getId = $(e.target).attr("play-voice-note");
     
        var audio = document.getElementById("voice"+getId);

        audio.addEventListener("playing", function(_event) {
            var id = audio.id;
             
            var duration = _event.target.duration;
            advance(duration, audio ,id, btn);
        });

        audio.addEventListener("pause", function(_event) {
            clearTimeout(audioTimer);
        });
        
        if(currentAudio != null && audio != currentAudio){
     
            currentAudio.pause();
            clearTimeout(currentAudio);
            isPlaying = false;

            $(".icon-play-button").removeClass('active');
            $("#playVoiceNote-"+getId).addClass('active');

            audio.play();
            isPlaying = true;
            currentAudio = audio;
            
        }else{
            if (!audio.paused) {
                // btn.classList.remove('active');
                // audio.pause();
                // isPlaying = false;
                $("#playVoiceNote-"+getId).removeClass('active');
                audio.pause();
                isPlaying = false;
                
            } else {
                // btn.classList.add('active');
                // audio.play();
                // isPlaying = true;
                $("#playVoiceNote-"+getId).addClass('active');
                audio.play();
                isPlaying = true;
                currentAudio = audio;
            }
        }   
    }
</script>
