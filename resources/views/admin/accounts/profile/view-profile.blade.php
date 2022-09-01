@extends('admin.layouts.app')
@section('content')
@section('title', 'View Profile')

<link rel="stylesheet" href="{{ url('assets/css/cropper.css') }}" type="text/css">

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
                    <div class="right ">
                        @if($getProfile->status == 'active')
                            <a href="javascript:void(0);" id="qrBtn" class="btn btn-primary ripple-effect" onclick="qrCode()">@lang('message.share_qr')</a>
                        @endif
                        
                        @if($profileStatus == 'active')
                            <a href="javascript:void(0);" onclick="editProfile()" class="btn btn-primary ripple-effect">@lang('message.edit_profile')</a>
                        @elseif($profileStatus == 'expired')
                            <a href="javascript:void(0);" id="renewSubscription" class="btn btn-primary ripple-effect">@lang('message.plan_expired')</a>
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
                    <img src="{{ url('assets/images/view-profile/ralph.png') }}"  alt="ralph">
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
                                    @php $string = preg_replace('/\s+/', ' ', trim($getProfile->journey));
                                    @endphp     
                                    @if(strlen($string) > 350) {{ str_replace('&nbsp;', ' ', strip_tags(\Illuminate\Support\Str::limit($getProfile->journey,  350, $end = '... '))) }} @else {!! $getProfile->journey !!}  @endif
                                    <div class="d-sm-flex align-items-sm-center mt-4">
                                        @if(strlen($string) > 350)
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary ripple-effect readMore" onclick="loadProfileJourney()">Read More</a>
                                        @endif
                                        <a href="javascript:void(0);"
                                            class="btn btn-outline-primary ripple-effect">View Ralph’s Family
                                            Tree</a>
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

<!-- edit profile sidebar -->
<aside class="rightSidebar editProfile">
</aside>


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
<div class="modal fade viewAllPrayers viewAllJourney" id="viewAllJourney" tabindex="-1" role="dialog"
    aria-labelledby="viewAllJourney" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
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
@endsection
    

@section('js')

<script src="{{ url('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ url('assets/js/moment.min.js') }}"></script>
<script src="{{ url('assets/js/cropper.js') }}"></script>
<!--fancybox js  -->
<script src="{{ url('assets/js/jquery.fancybox.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/12.3.1/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script src="{{ url('assets/js/bootbox.min.js') }}"></script>
<script>
    var profile_id = "{{ $profileId }}";
    var loadAdminProfileJournyUrl = "{{ url('admin/load-profile-journey') }}";
    var loadProfileMediaPhotoAdminUrl = "{{ route('admin/load-profile-media-photos') }}";
    var loadProfileMediaVideoAdminUrl = "{{ url('admin/load-profile-media-video') }}";
    var loadProfileMediaAudioAdminUrl = "{{ url('admin/load-profile-media-audio') }}";
    var loadProfileStoriesArticleAdminUrl = "{{ url('admin/load-profile-stories-article') }}"; 
    var loadMoreStoriesArticleAdminUrl = "{{ url('admin/load-more-profile-stories-article') }}";
    var loadProfileGuestBookAdminUrl = "{{ url('admin/load-profile-guset-book') }}";
    var loadGraveSiteDetailAdminUrl = "{{ route('admin/load-gravesite-detail') }}";
    var loadViewAllPrayersAdminUrl = "{{ route('admin/load-view-all-prayers') }}"; 
    var loadEditProfileWindowAdminUrl = "{{ route('admin/load-edit-profile-window') }}";
    var generateQrCodeAdminUrl = "{{ route('admin/generate-profile-qrcode') }}";
    var removeUploadMediaAdminUrl = "{{ route('admin/remove-upload-media')}}";
    var removeArticleAdminUrl = "{{ route('admin/remove-article')}}";

    /* Default voice note */
    var defaultVoiceNote = `<div class="voiceNotes_info d-flex position-relative"><div class="profile"><img src="{{ url('assets/images/user-default.jpg') }}" alt="User-img"></div><div class="caption d-flex align-items-center"><div class="caption_top"><div class="row align-items-center mb-2 no-gutters"><div class="col-sm-11"><div class="title"><h5 class="font-bd text-truncate mb-0">Talking to Angels - Sarah Sarris</h5><span class="font-rg d-none d-sm-inline-block">November 14, 2020</span> <span class="font-rg d-inline-block d-sm-none">11/14/2020</span> </div></div><div class="col-sm-1 text-right"><span class="duration">1:47</span></div></div><div class="audioBar "><div class="barProgress" id="barProgress"></div><audio id="defaultAudio" src="{{ url('assets/images/view-profile/demo.mp3')}}"></audio></div></div></div><a href="javascript:void(0);" onClick="togglePlayDefaultAudio()" class="videoPlay rounded-circle" data-action="play"><em class="icon-play-button"></em></a></div><div class="voiceNotes_info d-flex position-relative"><div class="profile"><img src="{{ url('assets/images/user-default.jpg') }}" alt="User-img"></div><div class="caption d-flex align-items-center"><div class="caption_top"><div class="row align-items-center mb-2 no-gutters"><div class="col-sm-11"><div class="title"><h5 class="font-bd text-truncate mb-0">Talking to Angels - Sarah Sarris</h5><span class="font-rg d-none d-sm-inline-block">November 14, 2020</span> <span class="font-rg d-inline-block d-sm-none">11/14/2020</span> </div></div><div class="col-sm-1 text-right"><span class="duration">1:47</span></div></div><div class="audioBar "><div class="barProgress" id="barProgress"></div><audio id="audio" src="{{ url('assets/images/view-profile/demo.mp3')}}"></audio></div></div></div><a href="javascript:void(0);"  class="videoPlay rounded-circle" data-action="play"><em class="icon-play-button"></em></a></div><div class="voiceNotes_info d-flex position-relative"><div class="profile"><img src="{{ url('assets/images/user-default.jpg') }}" alt="User-img"></div><div class="caption d-flex align-items-center"><div class="caption_top"><div class="row align-items-center mb-2 no-gutters"><div class="col-sm-11"><div class="title"><h5 class="font-bd text-truncate mb-0">Talking to Angels - Sarah Sarris</h5><span class="font-rg d-none d-sm-inline-block">November 14, 2020</span> <span class="font-rg d-inline-block d-sm-none">11/14/2020</span> </div></div><div class="col-sm-1 text-right"><span class="duration">1:47</span></div></div><div class="audioBar "><div class="barProgress" id="barProgress"></div><audio id="audio" src="{{ url('assets/images/view-profile/demo.mp3')}}"></audio></div></div></div><a href="javascript:void(0);" class="videoPlay rounded-circle" data-action="play"><em class="icon-play-button"></em></a></div>`;

    var loadMoreDefaultAudioView = `<div class="voiceNotes_info d-flex position-relative"><div class="profile"><img src="{{ url('assets/images/user-default.jpg') }}" alt="User-img"></div><div class="caption d-flex align-items-center"><div class="caption_top"><div class="row align-items-center mb-2 no-gutters"><div class="col-sm-11"><div class="title"><h5 class="font-bd text-truncate mb-0">Happy Birthday - Sarah Sarris</h5><span class="font-rg d-none d-sm-inline-block">November 14, 2020</span> <span class="font-rg d-inline-block d-sm-none">11/14/2020</span></div></div><div class="col-sm-1 text-right"><span class="duration">1:47</span></div></div><div class="audioBar "><div class="barProgress" id="barProgress"></div><audio id="audio" src="{{ url('assets/images/view-profile/demo.mp3')}}"></audio></div></div></div><a href="javascript:void(0);" class="videoPlay rounded-circle" data-action="play"><em class="icon-play-button"></em></a></div>`;

    var defaultStoriesArticle = `<div class="row row-l"><div class="col-sm-6"><div class="stories_top"><img src="{{ url('assets/images/view-profile/photo01.jpg') }}" class="img-fluid " alt="Article"><h3 class="h28 font-nbd">Remembering Foxtrot Squad and the battle of the cliff Daniel Sarris</h3><p class="h13 by">By Christine Sarris</p><a href="{{ route('admin/read-more-stories-article', [0]) }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a></div></div><div class="col-sm-6"><div class="stories_list"><ul class="list-unstyled"><li class="media"><div class="media-body"><h5 class="h20 font-nbd mb-0">First trip to Myrtle Beach for dad’s 35th Birthday </h5><span class="date font-bd h15">September 5, 2020</span><p class="h13 by">By Christine Sarris</p><a href="{{ route('admin/read-more-stories-article', [0]) }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a></div><img src="{{ url('assets/images/view-profile/photo01.jpg') }}" class="img-fluid " alt="Article"></li><li class="media"><div class="media-body"><h5 class="h20 font-nbd mb-0">First trip to Myrtle Beach for dad’s 35th Birthday </h5><span class="date font-bd h15">September 5, 2020</span><p class="h13 by">By Christine Sarris</p><a href="{{ route('admin/read-more-stories-article', [0]) }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a></div><img src="{{ url('assets/images/view-profile/photo02.jpg') }}" class="img-fluid " alt="Article"></li><li class="media"><div class="media-body"><h5 class="h20 font-nbd mb-0">First trip to Myrtle Beach for dad’s 35th Birthday </h5><span class="date font-bd h15">September 5, 2020</span><p class="h13 by">By Christine Sarris</p><a href="{{ route('admin/read-more-stories-article', [0]) }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a></div><img src="{{ url('assets/images/view-profile/photo02.jpg') }}" class="img-fluid " alt="Article"></li><li class="media"><div class="media-body"><h5 class="h20 font-nbd mb-0">First trip to Myrtle Beach for dad’s 35th Birthday </h5><span class="date font-bd h15">September 5, 2020</span><p class="h13 by">By Christine Sarris</p><a href="{{ route('admin/read-more-stories-article', [0]) }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a></div><img src="{{ url('assets/images/view-profile/photo02.jpg') }}" class="img-fluid " alt="Article"></li></ul></div></div></div>`;

    var defaultGuestBook = `<li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest01.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest02.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest03.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest04.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest05.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest06.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest07.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest08.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest09.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest10.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li><li><div class="guestBook_user d-flex"><div class="profile overflow-hidden rounded-circle"><img src="{{ url('assets/images/view-profile/guest11.png') }}" alt="guest-img"></div><h6 class="font-bd">Sarah Sarris <span class="font-rg">Signed the Guest Book</span> <br> <i class="mb-0 font-rg">November 5, 2020</i></h6></div></li>`;

    </script>
    <script src="{{ url('assets/admin/js/view-profile.js') }}"></script>
@endsection