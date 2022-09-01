@extends('user.layouts.app')
@section('content')
@section('title', 'View Profile')

<link rel="stylesheet" href="{{ url('assets/css/green-audio-player.min.css') }}" />
<!-- Main -->
<main class="main-content viewProfile">
    <!-- banner -->
    <section class="banner p-30">
        <div class="container">
            <div class="banner_content d-sm-flex align-items-sm-center justify-content-sm-between mb-24">
                <div class="left">
                    <h1 class="h34 font-nbd my-24 mt-0" id="onChangeLovedOneName">@if(!empty($getProfile->profile_name)){{$getProfile->profile_name }}@else{{"Ralph “Raphy” Sarris"}}@endif</h1>
                    <p class="mb-0 h15 font-bd" id="onChangeBirthDeathDate">@if(!empty($getProfile->date_of_birth)){{ getConvertedDate($getProfile->date_of_birth, 1) }}@else{{'10/7/1941'}}@endif - @if(!empty($getProfile->date_of_death)){{ getConvertedDate($getProfile->date_of_death, 1) }}@else{{'22/11/2006'}}@endif <span class="d-none d-sm-inline-block">|</span> @if(!empty($getProfile->short_description)){{$getProfile->short_description}}@else{{'Best Brother'}}@endif</p>
                </div>
                
                @if(!empty($getProfile))

                    @if($getProfile->user_id == @Auth::guard(request()->guard)->user()->id)
                    <div class="right ">
                       
                        @if($profileStatus != 'expired' && $profileStatus != 'inactive' )  
                            <a href="javascript:void(0);" id="qrBtn" class="btn btn-primary ripple-effect" onclick="qrCode()">@lang('message.share_qr')</a>
                        @endif

                        @if($profileStatus == 'active')
                            <a href="javascript:void(0);" onclick="editProfile()" class="btn btn-primary ripple-effect">@lang('message.edit_profile')</a>
                        @elseif($profileStatus == 'expired')
                            <a href="javascript:void(0);" onclick="viewPlan('{{ @$getProfile->ProfileSubscription[0]->id }}')" class="btn btn-primary ripple-effect">@lang('message.renew_subscription_button')</a>
                        @endif
                    </div>
                    @else
                    <div class="right text-sm-right">
                        <p class="mb-0">@lang('message.leave_loved_one_voice_note')</p>
                        <a href="javascript:void(0);" onclick="signGuestBook()" class="btn btn-primary ripple-effect mt-2">@lang('message.sign_guest_book_button')</a>
                    </div>
                    @endif

                @else
                    <div class="right ">
                        @if($profileStatus == 'NA')
                        <a href="javascript:void(0);" onclick="getSubcriptions()" class="btn btn-primary ripple-effect">@lang('message.start_free_trial_button')</a>
                        @endif
                    </div>
                @endif
                
            </div>
            <div class="banner_img overflow-hidden position-relative">
                @if(!empty($getProfile->banner_image)) 
                    <img src="{{ getUploadMedia($getProfile->banner_image) }}" class="img-fluid profileBannerImage" alt="Profile-banner">
                    @else
                    <img src="{{ url('assets/images/view-profile/profile-banner.jpg') }}" class="img-fluid profileBannerImage" alt="Profile-banner">
                @endif
                
                <div class="profileImg">
                @if(!empty($getProfile->profile_image)) 
                    <img src="{{ getUploadMedia($getProfile->profile_image) }}" class="profileImage"  alt="ralph"> 
                    @else
                    <img src="{{ url('assets/images/view-profile/ralph.png') }}" class="profileImage" alt="ralph">
                @endif
                </div>
            </div>
        </div>
    </section>
    <!-- profile info -->
    <section class="profilInfo">
        <div class="container">
            <div class="row row-xl">
                <!-- guest book -->
                <div class="col-lg-4 profilInfo_right order-lg-2">
                    <div class="guestBook bg-white">
                        <h2 class="h34 font-nbd">@lang('message.guest_book')</h2>
                        <ul class="list-unstyled" id="loadProfileGuestBook"> 

                        </ul>
                        
                        <div class="loadMore text-center" onclick="loadMoreGuestBook()">
                            <a href="javascript:void(0);" id="guestLoad" class="btn btn-outline-primary ripple-effect loadMoreGuestBook">@lang('message.load_more_button')</a>
                        </div>
                    </div>
                </div>
                <!-- memories -->
                <div class="col-lg-8 profilInfo_left order-lg-1">
                    <div class="memories">
                        <h3 class="h28 font-nbd mb-34">@lang('message.memories')</h3>
                        <div class="customTabs">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-basicinfo-tab" data-toggle="pill" href="#pills-basicinfo" role="tab" aria-controls="pills-basicinfo" aria-selected="true">@if(!empty($getProfile->profile_name)){{ $getProfile->profile_name }}@else Ralph’s @endif Journey</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-photos-tab" onclick="loadProfileMediaPhotos()" data-toggle="pill" href="#pills-photos" role="tab" aria-controls="pills-photos" aria-selected="false">@lang('message.photos')</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-videos-tab" onclick="loadProfileMediaVideo()" data-toggle="pill" href="#pills-videos" role="tab" aria-controls="pills-videos" aria-selected="false">@lang('message.videos')</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-voiceNotes-tab" onclick="loadProfileMediaAudio()" data-toggle="pill" href="#pills-voiceNotes" role="tab" aria-controls="pills-voiceNotes" aria-selected="false">@lang('message.voice_note')</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-stories-tab" data-toggle="pill" href="#pills-stories" role="tab" aria-controls="pills-stories" onclick="loadProfileStoriesArticle()" aria-selected="false">@lang('message.stories_article')</a>
                            </li>
                        </ul>
                        </div>
                        <div class="tab-content" id="pills-tabContent">
                            <!-- journey -->
                            <div class="tab-pane fade show active" id="pills-basicinfo" role="tabpanel"
                                    aria-labelledby="pills-basicinfo-tab">
                                <div class="basicContent">
                                    @if(!empty($getProfile->journey))
                                    @php  
                                    $string = preg_replace('/\s+/', ' ', trim($getProfile->journey));
                                    @endphp     
                                    @if(strlen($string) > 350) {{ str_replace('&nbsp;', ' ', strip_tags(\Illuminate\Support\Str::limit($getProfile->journey,  350, $end = '... '))) }} @else {!! $getProfile->journey !!}  @endif
                                    @else
                                       <p>Our beloved Ralph Sarris, age 70, resident of Austin, was born into Eternal
                                            Life on Thursday, October 29, 2020. He is reunited with his parents, Raymond
                                            and Sally Gomez Sarris; his brother, Donald Sarris his sister, Roseanna
                                            Sarris. Ralph is survived by his son, grandsons, and grandaugthers.
                                        </p>
                                        <p>Ralph was born in Brooklyn, New York, to Greek immigrant parents, Themis (née
                                            Katavolos) and George Andrew Sarris, and grew up in Ozone Park, Queens.[2]
                                            After attending John Adams High School in South Ozone Park (where he
                                            overlapped with Jimmy Breslin), he graduated from Columbia University in
                                            1951 and then served for three years in the Army Signal Corps before moving
                                            to Paris for a year, where he befriended Jean-Luc Godard and François
                                            Truffaut. Upon returning to New York's Lower East Side, Sarris briefly
                                            pursued graduate studies at his alma mater and Teachers College, Columbia
                                            University before turning to film criticism as a vocation.</p>
                                    @endif
                                    <div class="d-sm-flex align-items-sm-center mt-4">
                                        @if(!empty($getProfile->journey) && strlen($string) > 350)
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary ripple-effect readMore" onclick="loadProfileJourney()">Read More</a>
                                        @endif
                                        <a href="{{url('family-tree/'.$profileId)}}"
                                            class="btn btn-outline-primary ripple-effect">@lang('message.view_family_tree_button')</a>
                                    </div>
                                </div>
                            </div>

                            <!-- photos tab pane -->
                            <div class="tab-pane fade" id="pills-photos" role="tabpanel" aria-labelledby="pills-photos-tab">
                            <div class="photos" id="loadProfileMediaPhotos"></div>
                            <div class="text-center text-sm-right">
                                <a href="javascript:void(0);" class="btn btn-primary ripple-effect btn-sm mt-3 viewAllPillPhotos"><em class="icon-bar-9 pr-2"></em> @lang('message.view_all_photos_button')</a>
                            </div>
                            </div>
                            <!-- videos -->
                            <div class="tab-pane fade" id="pills-videos" role="tabpanel" aria-labelledby="pills-videos-tab">
                                <div class="photos" id="loadProfileMediaVideo">

                                </div>
                                <div class="text-center text-sm-right">
                                    <a href="javascript:void(0);" class="btn btn-primary ripple-effect btn-sm mt-3 viewAllPillVideos"><em class="icon-bar-9"></em>@lang('message.view_all_video_button')</a>
                                </div>
                            </div>
                            <!-- voice notes -->
                            <div class="tab-pane fade" id="pills-voiceNotes" role="tabpanel" aria-labelledby="pills-voiceNotes-tab">
                                <div class="voiceNotes">
                                    <div class="voiceNotes_body" id="voiceNotesList">
                                    </div>
                                    <div class="d-sm-flex align-items-sm-center justify-content-sm-center voiceNotes_bottom">
                                    @if($profileStatus == 'active' && $profileId != 0)
                                        <a href="javascript:void(0);" onclick="recordVoiceNote()" class="btn btn-outline-primary ripple-effect">@lang('message.record_voice_note')</a>
                                    @endif    
                                        <a href="javascript:void(0);" class="btn btn-outline-primary ripple-effect viewMoreVoiceNotes" id="viewMoreVoiceNotes">@lang('message.view_more_voice_note_button')</a>
                                    </div>
                                </div>
                            </div>
                            <!-- stories and articale -->
                            <div class="tab-pane fade" id="pills-stories" role="tabpanel" aria-labelledby="pills-stories-tab">
                                <div class="stories" id="storiesArticleViewList">
                                   
                                </div>
                            </div>
                        </div>
                        <!-- gravesite details -->
                        <div class="gravesiteDetails" id="loadGravesiteDetail">
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@if($profileStatus == 'active' || $profileStatus == 'NA')
<!-- start free trial sidebar -->
<aside class="rightSidebar startFreeTrial">
</aside>
<div class="rightSidebar-overlay"></div>
    
 
    
<!-- edit profile sidebar -->
<aside class="rightSidebar editProfile">
</aside>
<div class="rightSidebar-overlay"></div>
  
 @endif

<!-- QR code -->
<div class="modal fade qrCode" id="qrCode" data-backdrop="static" aria-labelledby="qrCodeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon-close"></em>
                </button>
            </div>
            <div class="modal-body pt-0 text-center">
                
            </div>
        </div>
    </div>
</div>

<!-- audio record -->
<div class="modal fade voiceRecord" id="voiceRecord" data-backdrop="static" aria-labelledby="voiceRecordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <em class="icon-close"></em>
        </button>
        </div>
        <div class="modal-body text-center" id="loadVoiceNoteModel">
        <div class="startRecording">
            <h5 class="font-bd h22 mb-4">@lang('message.voice_recording')</h5>
            <p class="mb-2">@lang('message.is_mircophone_ready')</p>
            <button id="recordButton" class="btn btn-lg btn-primary ripple-effect"><em class="icon-mic mr-2"></em> @lang('message.start_recording_button')</button>
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
        </div>
    </div>
    </div>
</div>

 
<!-- view all prayers -->
<div class="modal fade viewAllPrayers" id="viewAllPrayers" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="viewAllPrayersLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header border-0">
            <h5 class="modal-title h34 font-nbd" id="viewAllPrayersLabel">@if(!empty($getProfile->profile_name)){{ $getProfile->profile_name }}@else @lang('message.ralph_sarris') @endif</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <em class="icon-close"></em>
            </button>
        </div>
        <div class="modal-body pt-0" id="viewAllPrayersDetail">

        </div>
    </div>
    </div>
</div>

<!-- read more modal -->
<div class="modal fade viewAllPrayers" id="readMoreModal" tabindex="-1" role="dialog" aria-labelledby="readMoreModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header  border-0">
                <h5 class="modal-title h28 font-nbd" >@if(!empty($getProfile->profile_name)){{ $getProfile->profile_name }}@else @lang('message.ralph_sarris') @endif</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="icon-close"></i></span>
                </button>
            </div>
            <div class="modal-body pt-0" id="viewAllJourneyDetail">
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="profile_id" class="profile_id" value="{{ $profileId }}">

<!--Start of voice note form -->
<input type="hidden" name="audio_duration" id="audioDuration">
<!--End of voice note form -->

 <!--cropper image modal-->
 <div class="modal fade modalCrop" tabindex="-1" id="cropper-modal" data-backdrop="static" data-keyboard="false" aria-labelledby="cropper-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-none">
            <div class="modal-header">
                <h5 class="modal-title">@lang('message.add_image')</h5>
                <a href="javascript:void(0);" onclick="cropperResetBtn()" class="close" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">                    
                <div class="form-group text-center">
                    <div class="upload position-relative">
                        <div id="show-image">
                            <!--set image-->
                        </div>
                    </div>
                </div>
                <input type="hidden" name="articleId" id="articleId">
                <input type="hidden" name="imageShowId" id="imageShowId">
                <input type="hidden" name="type" id="editType">
                <div class="btnRow text-center">
                    <button type="button" class="btn btn-light ripple-effect mr-2" onclick="cropperResetBtn()" id="cropper-reset-btn"> @lang('message.reset_title')</button>
                    <button type="button" class="btn ripple-effect btn-primary" onclick="saveCropperImage()" id="cropper-image-btn">@lang('message.save_title')</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- view all plans -->
<div class="modal fade viewPlan" id="viewPlan" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="viewPlanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title h34 font-nbd" id="viewPlanLabel">@lang('message.subscription_plans')</h5>

                <button type="button" id="viewPlanButton" class="close" data-dismiss="modal" aria-label="Close" >
                    <em class="icon-close"></em>
                </button>
            </div>
            <div class="modal-body pt-0" id="loadSubscriptionPlan">
                
            </div>
        </div>
    </div>
</div>
@endsection
    
@section('js')

    <script src="{{ url('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ url('assets/js/moment.min.js') }}"></script>

    <script src="https://cdn.ckeditor.com/ckeditor5/12.3.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script>
        var profile_id = "{{ $profileId }}";
        var renewSubscriptionUrl = "{{ url('renew-subscription-plan') }}";
        var loadGraveSiteDetailUrl = "{{ route('load-gravesite-detail') }}";
        var loadProfileMediaPhotoUrl = "{{ route('load-profile-media-photos') }}";
        var loadViewAllPrayersUrl = "{{ url('load-view-all-prayers') }}";
        var loadProfileJourneyUrl = "{{ url('load-profile-journey') }}";
        var loadProfileMediaVideoUrl = "{{ url('load-profile-media-video') }}";
        var loadProfileMediaAudioUrl = "{{ url('load-profile-media-audio') }}"; 
        var loadProfileStoriesArticleUrl = "{{ url('load-profile-stories-article') }}" 
        var loadMoreProfileStoriesArticleUrl= "{{ url('load-more-profile-stories-article') }}";
        var loadProfileGuestBookUrl = "{{ url('load-profile-guset-book') }}";

        /* Default voice note */
        var defaultVoiceNote = `<div class="voiceNotes_info d-flex position-relative"><div class="profile"><img src="{{ url('assets/images/user-default.jpg') }}" alt="User-img"></div><div class="caption d-flex align-items-center"><div class="caption_top"><div class="row align-items-center mb-2 no-gutters"><div class="col-sm-11"><div class="title"><h5 class="font-bd text-truncate mb-0">Talking to Angels - Sarah Sarris</h5><span class="font-rg d-none d-sm-inline-block">November 14, 2020</span> <span class="font-rg d-inline-block d-sm-none">11/14/2020</span> </div></div><div class="col-sm-1 text-right"><span class="duration">1:47</span></div></div><div class="audioBar "><div class="barProgress" id="barProgress"></div><audio id="defaultAudio" src="{{ url('assets/images/view-profile/demo.mp3')}}"></audio></div></div></div><a href="javascript:void(0);" onClick="togglePlayDefaultAudio()" class="videoPlay rounded-circle" data-action="play"><em class="icon-play-button"></em></a></div><div class="voiceNotes_info d-flex position-relative"><div class="profile"><img src="{{ url('assets/images/user-default.jpg') }}" alt="User-img"></div><div class="caption d-flex align-items-center"><div class="caption_top"><div class="row align-items-center mb-2 no-gutters"><div class="col-sm-11"><div class="title"><h5 class="font-bd text-truncate mb-0">Talking to Angels - Sarah Sarris</h5><span class="font-rg d-none d-sm-inline-block">November 14, 2020</span> <span class="font-rg d-inline-block d-sm-none">11/14/2020</span> </div></div><div class="col-sm-1 text-right"><span class="duration">1:47</span></div></div><div class="audioBar "><div class="barProgress" id="barProgress"></div><audio id="audio" src="{{ url('assets/images/view-profile/demo.mp3')}}"></audio></div></div></div><a href="javascript:void(0);" class="videoPlay rounded-circle" data-action="play"><em class="icon-play-button"></em></a></div><div class="voiceNotes_info d-flex position-relative"><div class="profile"><img src="{{ url('assets/images/user-default.jpg') }}" alt="User-img"></div><div class="caption d-flex align-items-center"><div class="caption_top"><div class="row align-items-center mb-2 no-gutters"><div class="col-sm-11"><div class="title"><h5 class="font-bd text-truncate mb-0">Talking to Angels - Sarah Sarris</h5><span class="font-rg d-none d-sm-inline-block">November 14, 2020</span> <span class="font-rg d-inline-block d-sm-none">11/14/2020</span> </div></div><div class="col-sm-1 text-right"><span class="duration">1:47</span></div></div><div class="audioBar "><div class="barProgress" id="barProgress"></div><audio id="audio" src="{{ url('assets/images/view-profile/demo.mp3')}}"></audio></div></div></div><a href="javascript:void(0);" class="videoPlay rounded-circle" data-action="play"><em class="icon-play-button"></em></a></div>`;
        
        var laodMoreDefaultVoiceNote = `<div class="voiceNotes_info d-flex position-relative"><div class="profile"><img src="{{ url('assets/images/user-default.jpg') }}" alt="User-img"></div><div class="caption d-flex align-items-center"><div class="caption_top"><div class="row align-items-center mb-2 no-gutters"><div class="col-sm-11"><div class="title"><h5 class="font-bd text-truncate mb-0">Happy Birthday - Sarah Sarris</h5><span class="font-rg d-none d-sm-inline-block">November 14, 2020</span> <span class="font-rg d-inline-block d-sm-none">11/14/2020</span></div></div><div class="col-sm-1 text-right"><span class="duration">1:47</span></div></div><div class="audioBar "><div class="barProgress" id="barProgress"></div><audio id="audio" src="{{ url('assets/images/view-profile/demo.mp3')}}"></audio></div></div></div><a href="javascript:void(0);" class="videoPlay rounded-circle" data-action="play"><em class="icon-play-button"></em></a></div>`;

        var defaultStoriesArticle = `<div class="row row-l"><div class="col-sm-6"><div class="stories_top"><img src="{{ url('assets/images/view-profile/photo01.jpg') }}" class="img-fluid " alt="Article"><h3 class="h28 font-nbd">Remembering Foxtrot Squad and the battle of the cliff Daniel Sarris</h3><p class="h13 by">By Christine Sarris</p><a href="{{ url('read-more-stories-article/0') }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a></div></div><div class="col-sm-6"><div class="stories_list"><ul class="list-unstyled"><li class="media"><div class="media-body"><h5 class="h20 font-nbd mb-0">First trip to Myrtle Beach for dad’s 35th Birthday </h5><span class="date font-bd h15">September 5, 2020</span><p class="h13 by">By Christine Sarris</p><a href="{{ url('read-more-stories-article/0') }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a></div><img src="{{ url('assets/images/view-profile/photo01.jpg') }}" class="img-fluid " alt="Article"></li><li class="media"><div class="media-body"><h5 class="h20 font-nbd mb-0">First trip to Myrtle Beach for dad’s 35th Birthday </h5><span class="date font-bd h15">September 5, 2020</span><p class="h13 by">By Christine Sarris</p><a href="{{ url('read-more-stories-article/0') }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a></div><img src="{{ url('assets/images/view-profile/photo02.jpg') }}" class="img-fluid " alt="Article"></li><li class="media"><div class="media-body"><h5 class="h20 font-nbd mb-0">First trip to Myrtle Beach for dad’s 35th Birthday </h5><span class="date font-bd h15">September 5, 2020</span><p class="h13 by">By Christine Sarris</p><a href="{{ url('read-more-stories-article/0') }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a></div><img src="{{ url('assets/images/view-profile/photo02.jpg') }}" class="img-fluid " alt="Article"></li><li class="media"><div class="media-body"><h5 class="h20 font-nbd mb-0">First trip to Myrtle Beach for dad’s 35th Birthday </h5><span class="date font-bd h15">September 5, 2020</span><p class="h13 by">By Christine Sarris</p><a href="{{ url('read-more-stories-article/0') }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a></div><img src="{{ url('assets/images/view-profile/photo02.jpg') }}" class="img-fluid " alt="Article"></li></ul></div></div></div>`;

        var defaultGuestBook = `<li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest01.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest02.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest03.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest04.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest05.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest06.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest07.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest08.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest09.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest10.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest11.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li>`;
    </script>
    <script src="{{ url('assets/js/view-profile/view-profile-before-subscription.js') }}"></script>
        
    {{-- Start for get subscription --}}
    @if(@$profileStatus == 'NA')
    
    <script>
        var loadSubscriptionWindow = "{{ route('load-subcription-window') }}";
    </script>
    <script src="{{ url('assets/js/subscription/subscription.js') }}"></script>

    @endif
    {{-- End for get subscription --}}


    {{-- Start for edit profile --}}
    @if(@$profileStatus == 'active')
        
        @if($getProfile->user_id == @Auth::guard(request()->guard)->user()->id)

        <script>
            var loadEditProfileWindowUrl = "{{ route('load-edit-profile-window') }}";
            var generateProfileQrCodeUrl = "{{ route('generate-profile-qrcode') }}";

        </script>
        <script src="{{ url('assets/js/view-profile/view-profile-after-subscription.js') }}"></script>

        @else
        <script>
            $(document).ready(function(){
                $('.rightSidebar-overlay').remove();
                $('body').removeClass('overflow-hidden');
            });
        </script>
        @endif

    @endif

    {{-- End for edit profile --}}
    @if(@$profileStatus == 'expired')

        <script>
            $(document).ready(function(){
                $('.rightSidebar-overlay').remove();
                $('body').removeClass('overflow-hidden');
            });
        </script>

    @endif
    <script>
        var voiceNoteRecordingModelUrl = "{{ url('voice-recording-model') }}";
        var removeUploadMediaUrl = "{{ route('remove-upload-media')}}";
        var removeArticleUrl = "{{ route('remove-article') }}";
    </script>
    <script src="{{ url('assets/js/view-profile/common-view-profile.js') }}"></script>

@endsection