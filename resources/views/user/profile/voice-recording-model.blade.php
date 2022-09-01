<div class="startRecording">
    <h5 class="font-bd h22 mb-4">@lang('message.voice_recording')</h5>
    
    <p class="mb-2">@lang('message.is_mircophone_ready')</p>
    <button id="recordButton" class="btn btn-lg btn-primary ripple-effect"><em class="icon-mic mr-2"></em> @lang('message.start_recording_button')</button>
    <p class="mt-2">@lang('message.mircophone_selected')</p>
</div>
<div class="speakNow commonSec">
    <h5 class="font-bd h22">@lang('message.speck_now')</h5>
    <em class="icon-mic icon"></em>
    <span id="time-display" class="duration"><label id="minutesTimer">00</label>:<label id="secondsTimer">00</label></span>
    <p class="tagLine mb-0">@lang('message.max_duration') <strong class="theme-color">5</strong> @lang('message.minute')</p>
    <img src="{{ url('assets/images/view-profile/audio-wave.png') }}" class="player" alt="AudioWave">
    <div class="d-flex align-items-center justify-content-center">
        <a id="stopButton" href="javascript:void(0);" class="btn btn-primary ripple-effect mr-3"><em class="icon-media-stop mr-1"></em> @lang('message.stop_button')</a>
        <!-- <a id="cancelRecordBtn" href="javascript:void(0);" class="btn btn-outline-primary ripple-effect reset"><em class="icon-reload mr-1"></em> Reset</a> -->
    </div>
</div>
<div class="yourRecording commonSec">
    <h5 class="font-bd h22">@lang('message.your_recording')</h5>
    <div id="recordingsList" class="recordPlayer mx-auto">
        <!-- <audio>
            <source src="/view-profile/demo.mp3" type="audio/mpeg">
        </audio> -->
    </div>
    <div class="d-flex align-items-center justify-content-center">
        <a id="uploadRecordAudio" href="javascript:void(0);" class="btn btn-primary ripple-effect mr-3 disabled"><em class="icon-file_upload mr-1"></em> @lang('message.upload_button')</a>
        <a href="javascript:void(0);" class="btn btn-outline-primary ripple-effect reset"><em class="icon-reload mr-1"></em> @lang('message.reset_button')</a>
    </div>
</div>
<div class="resetRecording commonSec">
    <h5 class="font-bd h22">@lang('message.reset_recording')</h5>
    <p class="my-4">@lang('message.you_want_to_start_the_recording') <br>  @lang('message.your_current_recording_will_delete')</p>
    <div class="d-flex align-items-center justify-content-center">
        <a id="resetRecordingYes" href="javascript:void(0);" class="btn btn-primary ripple-effect mr-3">@lang('message.yes')</a>
        <a href="javascript:void(0);" class="btn btn-outline-primary ripple-effect">@lang('message.no')</a>
    </div>
</div>


@include('user.profile.audio-app-js')
<script>

    $('.speakNow .btn-primary').click(function() {
        $(this).parent().parent().removeAttr('style');
        $('.yourRecording').css('display', 'block');
    });

    $('.reset').click(function() {
        $(this).parent().parent().removeAttr('style');
        $('.resetRecording').css('display', 'block');
    });

    $('.resetRecording .btn-primary').click(function() {
        $('#recordButton').attr('disabled',false);
        $('#minutesTimer, #secondsTimer').html('00');
        $('#voiceRecord #recordingsList').html('');
        $(this).parent().parent().removeAttr('style');
        $('.startRecording').css('display', 'block');
    });

    $('.resetRecording .btn-outline-primary').click(function() {
        $(this).parent().parent().removeAttr('style');
        $('.yourRecording').css('display', 'block');
    });

    $('#voiceRecord').on('hidden.bs.modal', function () {
        $('.speakNow #stopButton').click();
        stopRecording();
        $('.reset').click();
        $('.resetRecording .btn-primary').click();
    })

</script>