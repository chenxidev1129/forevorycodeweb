<!DOCTYPE html>
<html lang="en">

<head>
    <title> @lang('message.login') || Forevory</title>
    @include('user.layouts.header-links')
</head>

<body >

<!-- Main -->
<main class="main-content loginPage " id="loginUpFormDivId">
    <section class="loginWrap frontEnd">
        <div class="content">
            <div class="logo">
                <img src="{{ url('assets/images/logo.svg')}}" alt="logo">
            </div>
            <p>@lang('message.remembring_our_loved_one_forevory_and_share')</p>
        </div>
        <div class="form">
            <div class="bg-white loginWrap_inner">
                <div class="text-center">
                    <h1 class="h34 font-nbd">@lang('message.login_page_heading')</h1>
                </div>
                <form action="{{ route('login') }}" method="post" id="userLoginForm">
                    @csrf
                    <div class="form-group">
                        <label>@lang('message.email_label')</label>
                        <input type="email" name="email" class="form-control" placeholder="@lang('message.email_placeholder')">
                    </div>
                    <div class="form-group">
                        <label>@lang('message.password_label')</label>
                        <div class="position-relative passwordField">
                            <input type="password" name="password" class="form-control" placeholder="@lang('message.password_placeholder')">
                            <a href="javascript:void(0);" class="showPassword">
                                <em class="icon-eye"></em>
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <button  id="submitUserLogin" class="btn btn-primary-light ripple-effect w-100">@lang('message.login_button_title')</button>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('forgot-password') }}" class="theme-link font-bd h15">@lang('message.forgot_password_link')</a>
                    </div>

                    <div class="otherLoginButtons">
                        <div class="separator d-flex align-items-center text-center font-bd">or</div>
                        <a href="{{ route('login-apple', ['id' => !empty($profileId) ? $profileId : 0]) }}" class="btn btn-outline-dark ripple-effect w-100 mb-3"><em class="icon-apple"></em> @lang('message.apple_login_button')</a>
                        <a href="{{ route('login-google', ['id' => !empty($profileId) ? $profileId : 0]) }}" class="btn btn-primary ripple-effect w-100 mb-3 google"><em class="icon-google"></em> @lang('message.google_login_button')</a>
                        <a href="{{ route('login-facebook', ['id' => !empty($profileId) ? $profileId : 0]) }}" class="btn btn-outline-primary ripple-effect w-100"><em class="icon-facebook"></em> @lang('message.facebook_login_button')</a>
                    </div>
                    <div class="separator d-flex align-items-center text-center font-bd">@lang('message.dont_have_forevory_account')</div>
                    <div class="text-center">
                        <a  href="@if(!empty($profileId)){{ route('guest-sign-up' , [$profileId]) }}@else{{ route('sign-up') }}@endif" class="btn btn-outline-primary ripple-effect w-100">@lang('message.sign_up_title')</a>
                    </div>

                </form>
            </div>
        </div>
    </section>
</main>

<input type="hidden" id="profileId" value="{{ $profileId}}">
<main class="main-content loginPage" id="otpFormDivId"  style="display: none">
    <section class="loginWrap">
        <div class="loginWrap_outer">
            <div class="loginWrap_logo text-center">
                <img src="{{ url('assets/images/logo.svg')}}" alt="logo">
            </div>
            <div class="bg-white loginWrap_inner">
                <div class="text-center">
                    <h1 class="h34 font-nbd">@lang('message.security_verification_text')</h1>
                    <p class="text-left h15">@lang('message.signup_otp_verification_text')</p>
                </div>
                <form action="{{ route('otp-verification') }}" method="post" id="otpVerificationForm">
                    @csrf
                    <input type="hidden" class="form-control verifyEmail" id="verifyEmail" name="email" />
                    <div class="form-group" id="resendOtp">
                        <input type="number" name="otp" class="form-control otp-length" placeholder="6 digit code" onKeyPress="if(this.value.length==6) return false;">
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
    @include('user.layouts.footer-links')
    {!! JsValidator::formRequest('App\Http\Requests\LoginRequest','#userLoginForm') !!}
    {!! JsValidator::formRequest('App\Http\Requests\OtpVerify','#otpVerificationForm') !!}

    <script>
        var profileId = $("#profileId").val();
        var redirectUrl = '{{ route("guest-profile", ":profile_id") }}';
        var profileUrl = "{{ route('profile') }}";
        var resendUrl = "{{route('resend')}}";
        var csrfToken = "{{csrf_token()}}";
    </script>
    <script src="{{ url('assets/js/user/login/login.js') }}"></script>
</body>

</html>