<!DOCTYPE html>
<html lang="en">

<head>
    <title> @lang('message.forgot_password') || Forevory</title>
    @include('user.layouts.header-links')
</head>

<body >

<!-- Main -->
<main class="main-content loginPage" id="forgotPasswordFormDivId">
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
					<h1 class="h34 font-nbd">@lang('message.forgot_password_heading')</h1>
				</div>
				<form action="{{ route('forgot-password') }}" method="post" id="forgotPasswordForm">
					@csrf
					<div class="form-group">
						<label>@lang('message.email_label')</label>
						<input type="email" name="email" class="form-control" placeholder="@lang('message.email_placeholder')">
					</div>
					<div class="form-group">
						<button  id="submitforgotPassword" class="btn btn-primary ripple-effect w-100">@lang('message.forgot_password_title')</button>
					</div>
					<div class="text-center mt-4">
						<p class="h15 mb-0">@lang('message.back_to') <a href="{{ url('/') }}" class="theme-link font-bd">@lang('message.login_button_title')</a></p>
					</div>

				</form>
			</div>
		</div>
	</section>
</main>

<main class="main-content loginPage" id="otpFormDivId"  style="display: none">
    <section class="loginWrap">
        <div class="loginWrap_outer">
            <div class="loginWrap_logo text-center">
                <img src="{{ url('assets/images/logo.svg')}}" alt="logo">
            </div>
            <div class="bg-white loginWrap_inner">
                <div class="text-center">
                    <h1 class="h34 font-nbd">@lang('message.we_have_sent_you_email')</h1>
                    <p class="text-left h15" id="showHiddenEmail"> <a href="{{ route('forgot-password') }}" class="theme-link font-bd"> @lang('message.change_title') </a></p>
                </div>
                <form action="{{ route('forgot-password-otp-verification') }}" method="post" id="fotgotPasswordOtpForm">
                    @csrf
                    <input type="hidden" class="form-control verifyEmail" id="verifyEmail" name="email" />
                    <div class="form-group" id="resendOtp">
                        <input type="number" name="otp" class="form-control otp-length" placeholder="6 digit code" onKeyPress="if(this.value.length==6) return false;">
                        <a href="javascript:void(0)"  class=" mt-1 h15 theme-link font-bd d-block">
                            @lang('message.resend_title')
                        </a>
                    </div>
                    <div class="form-group">
                        <button type="button" onclick="submitForgotPasswordOtp()" id="fotgotPasswordOtpButton" class="btn btn-primary ripple-effect w-100 otp-btn-active" disabled>@lang('message.submit_title')</button>
                    </div>

                </form>
            </div>
        </div>
    </section>
</main>
	
<!-- Main -->
<main class="main-content loginPage" id="passwordResetFormDivId"  style="display: none">
    <section class="loginWrap">
        <div class="loginWrap_outer">
            <div class="loginWrap_logo text-center">
                <img src="{{ url('assets/images/logo.svg')}}" alt="logo">
            </div>
            <div class="bg-white loginWrap_inner">
                <div class="text-center">
                    <h1 class="h34 font-nbd">@lang('message.reset_password')</h1>
                </div>
                <form action="{{ route('reset-forgot-password') }}" method="post" id="resetPasswordFrom">
                @csrf
                    <input type="hidden" class="form-control verifyEmail" id="forgotPasswordEmail" name="email" />
                    <div class="form-group">
                        <label>@lang('message.new_password_label')</label>
                        <div class="position-relative passwordField">
                            <input type="password" name="password" class="form-control" placeholder="@lang('message.new_password_placeholder')">
                            <a href="javascript:void(0);" class="showPassword">
                                <em class="icon-eye"></em>
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('message.confirm_password_label')</label>
                        <div class="position-relative passwordField">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="@lang('message.confirm_password_placeholder')">
                            <a href="javascript:void(0);" class="showPassword">
                                <em class="icon-eye"></em>
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" onclick="submitResetPassword()" id="resetPasswordButton" class="btn btn-primary ripple-effect w-100">@lang('message.change_title')</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>	
    <!-- Header -->
	@include('user.layouts.footer-links')
	{!! JsValidator::formRequest('App\Http\Requests\ForgotPasswordRequest','#forgotPasswordForm') !!}
	{!! JsValidator::formRequest('App\Http\Requests\OtpVerify','#fotgotPasswordOtpForm') !!}
	{!! JsValidator::formRequest('App\Http\Requests\ForgotPasswordResetRequest','#resetPasswordFrom') !!}
    <script>
        var resendUrl = "{{route('resend')}}";
        var csrfToken = "{{csrf_token()}}";
        var homeUrl = "{{ url('/') }}";
    </script>
    <script src="{{ url('assets/js/user/forgot-password/forgot-password.js') }}"></script>
</body>

</html>