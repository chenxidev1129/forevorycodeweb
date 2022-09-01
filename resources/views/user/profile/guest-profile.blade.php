@extends('user.layouts.app')
@section('content')
@section('title', __('message.guest_profile'))

<!-- Main -->
<main class="main-content viewProfile guestProfile">
    <!-- banner -->
    <section class="banner p-30">
        <div class="container">
            <div class="banner_content d-sm-flex align-items-sm-center justify-content-sm-between mb-24">
                <div class="left">
                    <h1 class="h34 font-nbd my-24 mt-0" id="onChangeLovedOneName">@if(!empty($getProfile->profile_name)){{$getProfile->profile_name }}@else{{"Ralph “Raphy” Sarris"}}@endif</h1>
                    <p class="mb-0 h15 font-bd" id="onChangeBirthDeathDate">@if(!empty($getProfile->date_of_birth)){{ getConvertedDate($getProfile->date_of_birth, 1) }}@else{{'10/7/1941'}}@endif - @if(!empty($getProfile->date_of_death)){{ getConvertedDate($getProfile->date_of_death, 1) }}@else{{'22/11/2006'}}@endif <span class="d-none d-sm-inline-block">|</span> @if(!empty($getProfile->short_description)){{$getProfile->short_description}}@else{{'Best Brother'}}@endif</p>
                </div>
                <div class="right text-sm-right">
                    <p class="mb-0">@lang('message.leave_loved_one_voice_note')</p>
                    <a href="javascript:void(0);" onclick="login()" class="btn btn-primary ripple-effect mt-2">@lang('message.sign_guest_book_button')</a>
                </div>
            </div>
            <div class="banner_img overflow-hidden position-relative">
                @if(!empty($getProfile->banner_image)) 
                    <img data-progressive="{{ getUploadMedia($getProfile->banner_image) }}" class="img-fluid progressive__img progressive--not-loaded profileBannerImage" alt="Profile-banner">
                    @else
                    <img data-progressive="{{ url('assets/images/view-profile/profile-banner.jpg') }}" class="img-fluid progressive__img progressive--not-loaded profileBannerImage" alt="Profile-banner">
                @endif
                
                <div class="profileImg">
                @if(!empty($getProfile->profile_image)) 
                    <img data-progressive="{{ getUploadMedia($getProfile->profile_image) }}" class="progressive__img progressive--not-loaded profileImage" alt="ralph"> 
                    @else
                    <img data-progressive="{{ url('assets/images/view-profile/ralph.png') }}" class="progressive__img progressive--not-loaded profileImage" alt="ralph">
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
                        <div class="loadMore text-center">
                            <a href="javascript:void(0);" onclick="login()" id="guest Load" class="btn btn-outline-primary ripple-effect loadMoreGuestBook">@lang('message.load_more_button')</a>
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
                                    <a class="nav-link " id="pills-basicinfo-tab" data-toggle="pill" onclick="login()" href="javascript:void(0);" role="tab" aria-controls="pills-basicinfo" aria-selected="true">@if(!empty($getProfile->profile_name)){{$getProfile->profile_name.'’s' }}@else{{"Ralph’s"}}@endif Journey</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-photos-tab" data-toggle="pill" onclick="login()" href="javascript:void(0);" role="tab" aria-controls="pills-photos" aria-selected="false">Photos</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-videos-tab" data-toggle="pill"  onclick="login()"hhref="javascript:void(0);" role="tab" aria-controls="pills-videos" aria-selected="false">Videos</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-voiceNotes-tab" data-toggle="pill" onclick="login()" href="javascript:void(0);" role="tab" aria-controls="pills-voiceNotes" aria-selected="false">Voice Notes</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-stories-tab" data-toggle="pill" onclick="login()" href="javascript:void(0);" role="tab" aria-controls="pills-stories" aria-selected="false">Stories & Articles</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content" id="pills-tabContent">
                            <!-- journey -->
                            <div class="tab-pane fade show active" id="pills-basicinfo" role="tabpanel" aria-labelledby="pills-basicinfo-tab">
                                <div class="basicContent">
                                @if(!empty($getProfile->journey))
                                 <p>{!! substr($getProfile->journey, 0, 350) !!}</p>
                        
                                @else
                                    <p>Our beloved Ralph Sarris, age 70, resident of Austin, was born into Eternal Life on Thursday, October 29, 2020. He is reunited with his parents, Raymond and Sally Gomez Sarris; his brother, Donald Sarris his sister, Roseanna Sarris. Ralph is survived by his son, grandsons, and grandaugthers.
                                    </p>
                                    <p>Ralph was born in Brooklyn, New York, to Greek immigrant parents, Themis (née Katavolos) and George Andrew Sarris, and grew up in Ozone Park, Queens.[2] After attending John Adams High School in South Ozone Park (where he overlapped with Jimmy Breslin), he graduated from Columbia University in 1951 and then served for three years in the Army Signal Corps before moving to Paris for a year, where he befriended Jean-Luc Godard and François Truffaut. Upon returning to New York's Lower East Side, Sarris briefly pursued graduate studies at his alma mater and Teachers College, Columbia University before turning to film criticism as a vocation.</p>
                                @endif
                                    <div class="d-sm-flex align-items-sm-center">
                                        <a href="javascript:void(0);" onclick="login()" class="btn btn-primary ripple-effect read More">@lang('message.read_more_button')</a>
                                        <a href="javascript:void(0);" onclick="login()" class="btn btn-outline-primary ripple-effect">@lang('message.view_family_tree_button')</a>
                                    </div>
                                </div>
                            </div>
                            <!-- photos tab pane -->
                            <div class="tab-pane fade" id="pills-photos" role="tabpanel" aria-labelledby="pills-photos-tab">
                                
                            </div>
                            <!-- videos -->
                            <div class="tab-pane fade" id="pills-videos" role="tabpanel" aria-labelledby="pills-videos-tab">
                                
                            </div>
                            <!-- voice notes -->
                            <div class="tab-pane fade" id="pills-voiceNotes" role="tabpanel" aria-labelledby="pills-voiceNotes-tab">
                                
                            </div>
                            <!-- stories and articale -->
                            <div class="tab-pane fade" id="pills-stories" role="tabpanel" aria-labelledby="pills-stories-tab">
                                
                            </div>
                        </div>
                        <!-- gravesite details -->
                        <div class="gravesiteDetails">
                            <h2 class="h34 font-nbd">@lang('message.gravesite_details')</h2>
                            <div class="map">
                            @if(!empty($getProfile->profileGraveSite))
                                @if(!empty($getProfile->profileGraveSite->lat) && !empty($getProfile->profileGraveSite->lang))
                                <iframe src = "https://maps.google.com/maps?q={{$getProfile->profileGraveSite->lat}},{{$getProfile->profileGraveSite->lang}}&hl=es;z=14&amp;output=embed" width="100%" height="100%" allowfullscreen="" loading="lazy" title="Gravesite Details"></iframe>
                                @else
                                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d394.1937950246738!2d-122.42336835477595!3d37.77713998838866!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1622450604892!5m2!1sen!2sin" width="100%" height="100%" allowfullscreen="" loading="lazy" title="Gravesite Details"></iframe> 
                                @endif 
                            @else
                                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d394.1937950246738!2d-122.42336835477595!3d37.77713998838866!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1622450604892!5m2!1sen!2sin" width="100%" height="100%" allowfullscreen="" loading="lazy" title="Gravesite Details"></iframe> 
                            @endif   
                        
                            </div>
                            <div class="locationList">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h4 class="h22 font-nbd">@lang('message.location')</h4>
                                        <p class="" id="showGraveSiteAddress">
                                            @if(!empty($getProfile->profileGraveSite->address))
                                                {{ $getProfile->profileGraveSite->address }}

                                                @if(!empty($getProfile->profileGraveSite->zip_code))
                                                    @if (strpos($getProfile->profileGraveSite->address, $getProfile->profileGraveSite->zip_code) === false)
                                                        {{ $getProfile->profileGraveSite->zip_code }}
                                                    @endif
                                                @endif

                                            @else 
                                                TX 78702 Gravesite Location Row 5 Plot 7 Cordoza Road, 909 Navasota St, Texas State Cemetery, Austin 
                                            @endif
                                        </p>
                                        <p class="mb-0">
                                            @if(!empty($getProfile->profileGraveSite->note)){{ $getProfile->profileGraveSite->note }}@endif
                                        </p>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4 class="h22 font-nbd">@lang('message.gravesite_prayers')</h4>
                                        <p class="mb-0">@lang('message.gravesite_prayers_text')</p>
                                        <a href="javascript:void(0);" onclick="login()" class="btn btn-outline-primary ripple-effect">@lang('message.view_all_prayers')</a>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4 class="h22 font-nbd">@lang('message.headstone_image')</h4>
                                        <p class="mb-0">@lang('message.headstone_image_text')</p>
                                        <a href="javascript:void(0);" onclick="login()"  class="btn btn-outline-primary ripple-effect viewLocation">@lang('message.view_image_button')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="footerNote">
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-md-8">
                    <div class="d-flex align-items-center">
                        <img src="{{ url('assets/images/mobile-logo.svg') }}"  alt="logo">
                        <div class="caption ml-3">
                            <h6 class="font-md text-white">@lang('message.login_page_heading')</h6>
                            <p class="mb-0 text-white ">@lang('message.login_to_leave_loved_one_voice_note')</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-md-4 text-center text-sm-right mt-2 mt-sm-0">
                    <div class="footerNote_right">
                        <a href="{{ url('guest-login' , [$profileId]) }}" class="loginBtn ml-auto">@lang('message.login_button_title')</a>
                        <a href="{{ url('guest-sign-up' , [$profileId]) }}" id="signUpRedirect" class="d-inline-block ml-2 ml-sm-0 mt-sm-2">@lang('message.sign_up_title')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<!-- login -->
<div class="modal fade authModal" id="loginModal" data-backdrop="static"  aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header align-items-center flex-column">
                <img src="{{ url('assets/images/logo.svg') }}" alt="logo">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon-close"></em>
                </button>
                <p class="subText mb-0">@lang('message.login_signup_guest')</p>
            </div>
            <div class="modal-body" id="loadGuestLogin">
             
            </div>
        </div>
    </div>
</div>

<!-- sign up -->
<div class="modal fade authModal" id="signupModal" data-backdrop="static"  aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header align-items-center flex-column">
                <img src="{{ url('assets/images/logo.svg') }}" alt="logo">
                <h5 class="modal-title h20" id="signupModalLabel">@lang('message.sign_up_heading')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon-close"></em>
                </button>
            </div>
            <div class="modal-body" id="loadGuestSignUp">
            
            </div>
        </div>
    </div>
</div>

<!-- security verification -->
<div class="modal fade authModal" id="securityVerificationModal" data-backdrop="static"  aria-labelledby="securityVerificationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header align-items-center flex-column">
                <img src="{{ url('assets/images/logo.svg') }}" alt="logo">
                <h5 class="modal-title h20" id="securityVerificationModalLabel">@lang('message.security_verification_forevory')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon-close"></em>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('otp-verification') }}" method="post" id="otpVerificationForm">
                        @csrf
                        <input type="hidden" class="form-control" id="otpToEmail" name="email" />
                        <input type="hidden" class="form-control" id="otpType"/>
            			<div class="form-group" id="resendOtp">
            				<input type="number" name="otp" class="form-control otp-length" placeholder="6 digit code" onKeyPress="if(this.value.length==6) return false;">
							<a href="javascript:void(0)"  class=" mt-1 h15 theme-link font-bd d-block">
                                @lang('message.resend_title')
                            </a>
            			</div>
            			<div class="form-group">
    	        			<button type="button" onclick="securityVerification()" id="otpVerificationButton" class="btn btn-primary ripple-effect w-100 otp-btn-active" disabled>@lang('message.submit_title')</button>
    	        		</div>

            	</form>
            </div>
        </div>
    </div>
</div>
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
                <div class="btnRow text-center">
                    <button type="button" class="btn btn-light ripple-effect mr-2" onclick="cropperResetBtn()" id="cropper-reset-btn"> @lang('message.reset_title')</button>
                    <button type="button" class="btn ripple-effect btn-primary" onclick="saveCropperImage()" id="cropper-image-btn">@lang('message.save_title')</button>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="profile_id" id="guestProfileId" class="profile_id"  value="{{ $profileId }}">
@endsection
    
@section('js')
{!! JsValidator::formRequest('App\Http\Requests\OtpVerify','#otpVerificationForm') !!}
<script src="{{ url('assets/js/jquery-ui.min.js') }}"></script>

<script>
    var csrfToken = "{{csrf_token()}}";
    var loadSignUpModelUrl = "{{ route('load-sign-up') }}"; 
    var loadLoginModelUrl = "{{ route('load-guest-login') }}";
    var guestProfileGuestBook = "{{ url('load-profile-guset-book') }}";
    var resendOtpUrl = "{{route('resend')}}";
</script>
<script src="{{ url('assets/js/user/guest/guest-profile.js') }}"></script>
@endsection