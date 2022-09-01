<!DOCTYPE html>
<html lang="en">

<head>
    <title> @lang('message.sign_up') || Forevory</title>
    @include('user.layouts.header-links')	
    <link rel="stylesheet" href="{{ url('assets/css/intlTelInput.css') }}" type="text/css">
</head>

<body >

    <!-- Main -->
    <main class="main-content loginPage" id="signUpFormDivId">
        <section class="loginWrap frontEnd">
            <div class="content">
                <div class="logo">
                    <img src="{{ url('assets/images/logo.svg')}}" alt="logo">
                </div>
                <p>Remembring our loved ones forever and sharing tham with all and future generations.</p>
            </div>
            <div class="form">
                <div class="bg-white loginWrap_inner">
                    <div class="text-center">
                        <h1 class="h34 font-nbd">@lang('message.sign_up_heading')</h1>
                    </div>
                    <form action="{{ route('sign-up') }}" method="post" id="signUpForm" enctype = "multipart/form-data">
                        @csrf
                      
                        <input type="hidden" name="country_iso_code" value="us" id="country_iso_code">
                        <input type="hidden" name="country_code" value="+1" id="country_code">
                        <input type="hidden" name="country_short_name"  id="country_sortname">   
                        <input type="hidden" name="lat" id="lat">   
                        <input type="hidden" name="lng"  id="lng">  
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-center">

                                    <div class="uploadProfile  position-relative rounded-circle overflow-hidden mx-auto show">
                                        <div class="upload__img  mx-auto text-center"> 
                                        <img id="show-image-preview"  src="{{ url('assets/images/user-default.jpg') }}" alt="User-img">
                                        </div>
                                        <label class="rounded-circle mb-0">
                                            <em class="icon-camera"></em>
                                            <input type="file" class="d-none"  id="upload_image" onchange="readUrlForCropper(this);" accept="image/png, image/jpg, image/jpeg" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('message.first_name_label')</label>
                                    <input type="text" name="first_name" class="form-control text-capitalize" placeholder="@lang('message.first_name_placeholder')">
                                </div>
                            </div>
                             <div class="col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('message.last_name_label')</label>
                                    <input type="text" name="last_name" class="form-control text-capitalize" placeholder="@lang('message.last_name_placeholder')">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('message.email_label')</label>
                                    <input type="email" name="email" class="form-control" placeholder="@lang('message.email_placeholder')">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('message.phone_number_label')</label>
                                    <input id="phone" name="phone_number" class="form-control"  type="tel" placeholder="@lang('message.phone_number_placeholder')" aria-describedby="phone-error">
                                    <span id="phone-error" class="help-block error-help-block"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('message.address_label')</label>
                                    <input type="text" name="address" id="address" class="form-control" placeholder="@lang('message.address_placeholder')" autocomplete="on">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('message.zip_code_label')</label>
                                    <input type="text" name="zip_code" id="zipCode" class="form-control" placeholder="@lang('message.zip_code_placeholder')">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('message.country_label')</label>
                                    <input type="text" name="country" id="country" class="form-control" placeholder="@lang('message.country_placeholder')" readonly>
                                </div>
                            </div>
                                <div class="col-sm-6" >
                                    <div class="form-group">
                                        <label>@lang('message.state_label')</label>
                                        <input type="text" name="state" id="state" class="form-control" placeholder="@lang('message.state_placeholder')" readonly>
                                    </div>
                                </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                        <label>@lang('message.city_label')</label>
                                        <input type="text" name="city" id="city" class="form-control" placeholder="@lang('message.city_placeholder')">
                                </div>
                           </div>
                            <div class="col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('message.password_label')</label>
                                    <div class="position-relative passwordField">
                                        <input type="password" name="password" class="form-control" placeholder="@lang('message.password_placeholder')">
                                        <a href="javascript:void(0);" class="showPassword">
                                            <em class="icon-eye"></em>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="set_crop_image_input">
                            <!--Set Cropper Image-->
                        </div>
                        <div class="form-group">
                            <button type="button" onclick="saveSignUp()" id="signUpbutton" class="btn btn-primary ripple-effect w-100">@lang('message.sign_up_title')</button>
                        </div>
                        <div class="text-center mt-4">
                            <p class="h15 mb-0">@lang('message.already_member_text') <a href="@if(!empty($profileId)){{ route('guest-login' , [$profileId]) }}@else{{ url('/') }}@endif" class="theme-link font-bd">@lang('message.login_button_title')</a></p>
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
                                            <button type="button" class="btn ripple-effect btn-primary" onclick="saveCropperImage()" id="cropper-image-btn" data-dismis="modal">@lang('message.save_title')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <input type="hidden" id="profileId" value="{{ $profileId}}">
    <main class="main-content loginPage" id="otpFormDivId"  style="display: none">
        <section class="loginWrap frontEnd">
            <div class="content">
                <div class="logo">
                    <img src="{{ url('assets/images/logo.svg')}}" alt="logo">
                </div>
                <p>Remembring our loved ones forever and sharing tham with all and future generations.</p>
            </div>
            <div class="form">
                <div class="bg-white loginWrap_inner">
            		<div class="text-center">
            			<h1 class="h34 font-nbd">@lang('message.security_verification_text')</h1>
						<p class="text-left h15">@lang('message.signup_otp_verification_text')</p>
            		</div>
            		<form action="{{ route('otp-verification') }}" method="post" id="otpVerificationForm">
                        @csrf
                        <input type="hidden" class="form-control verifyEmail" id="verifyEmail" name="email" />
            			<div class="form-group" id="resendOtp">
            				<input type="number" name="otp" class="form-control otp-length" placeholder="6 digit code">
							<a href="javascript:void(0)"  class=" mt-1 h15 theme-link font-bd d-block">
                                @lang('message.resend_title')
                            </a>
                        </div>
            			<div class="form-group">
    	        			<button type="button" onclick="submitOtpVerification()" id="otpVerificationButton" class="btn btn-primary ripple-effect w-100 otp-btn-active" disabled>@lang('message.submit_title')</button>
    	        		</div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Header -->
    <script src="{{ url('assets/js/intlTelInput.js') }}"></script>
    @include('user.layouts.footer-links')
    <!-- google address js -->
    <script src="{{ url('assets/js/google-address.js') }}"></script>
    <!-- Google address api  -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{config('constants.addressApiKey')}}&libraries=places&callback=initAutocomplete"></script>
    <script src="{{ url('assets/js/custom.js') }}"></script>
        {!! JsValidator::formRequest('App\Http\Requests\OtpVerify','#otpVerificationForm') !!}
        {!! JsValidator::formRequest('App\Http\Requests\UserSignUpRequest','#signUpForm') !!}
    <script> 
        var profileId = $("#profileId").val();
        var url = '{{ route("guest-profile", ":profile_id") }}';
        var resendUrl = "{{route('resend')}}";
        var csrfToken = "{{csrf_token()}}";
        var profileUrl = "{{ route('profile') }}";

        $('#cropper-modal').on('hide.bs.modal', function (event) {
           setTimeout( function() {
                $('body').removeClass('modal-open');
           }, 1500);
        })

    </script>
    <script src="{{ url('assets/js/user/signup/signup.js') }}"></script>
   
</body>

</html>