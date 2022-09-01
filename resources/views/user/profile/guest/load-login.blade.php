<form action="{{ route('login') }}" method="post" id="guestLoginForm">
    @csrf
    <div class="form-group">
        <label>@lang('message.email_label')</label>
        <input type="email" class="form-control" name="email" placeholder="@lang('message.email_placeholder')">
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
    <div class="form-group mb-2">
        <button type="button" onclick="guestLogin()" id="guestLoginButton" class="btn btn-primary ripple-effect w-100">@lang('message.login_button_title')</button>
    </div>
    <!-- <div class="text-center">
        <a href="javascript:void(0);" onclick="forgotPassword()" data-dismiss="modal" class="theme-link font-bd h15">Forgot Password</a>
    </div> -->

    <div class="otherLoginButtons">
        <div class="divider font-bd">or</div>
        <a href="{{ route('login-apple', ['id' => @$request->profileId]) }}" class="btn btn-outline-dark ripple-effect w-100 mb-3">@lang('message.apple_login_button')</a>
        <a href="{{ route('login-google', ['id' => @$request->profileId]) }}" class="btn btn-primary ripple-effect w-100 mb-3">@lang('message.google_login_button')</a>
        <a href="{{ route('login-facebook', ['id' => @$request->profileId]) }}" class="btn btn-outline-primary ripple-effect w-100">@lang('message.facebook_login_button')</a>
    </div>
    <div class="text-center">
        <p class="h15 mb-0">New user? <a href="javascript:void(0);" data-dismiss="modal" onclick="signUp()"  class="theme-link font-bd">Sign up</a></p>
    </div>
</form>

{!! JsValidator::formRequest('App\Http\Requests\LoginRequest','#guestLoginForm') !!}
<script>
    /* Guest login */
    function guestLogin(){

        var form = $('#guestLoginForm');
        var method = form.attr('method');
        var btn = $('#guestLoginButton');
        if (form.valid()) {
            btn.prop('disabled', true);
            $.ajax({
                url: form.attr('action'),
                type: method, 
                data: form.serialize(),
                dataType: 'JSON',
                success: function (data)
                {
                    /* If user is not verified */
                    if(data.data.email_verified != '1'){
                        _toast.error(data.message);
                        $('#loginModal').modal('hide');
                        $('#securityVerificationModal').modal('show');
                        $("#otpToEmail").val(data.data.email);
                        /* Otp type to check type of emai sent */
                        $("#otpType").val('login');
                    }else{
                        _toast.success(data.message);
                        setTimeout(function() {
                            location.reload(); 
                        }, 2000)
                    }
                    
                }, error: function (err) {
                    btn.prop('disabled', false);
                    var errors = jQuery.parseJSON(err.responseText);
                    if (errors.status === 422) {
                        $.each(errors.errors, function(key, val) {
                            $("#" + key + "-error").text(val);
                        });
                    } else {
                        _toast.error(errors.message)
                    }
                },
            });
        }
    }

    /* Show password text field */
    $('.showPassword').click(function() {
        $(this).children('em').toggleClass('icon-eye icon-eye-off')
            $(this).siblings(".form-control").attr('type', function(index, attr){
            return attr == 'text' ? 'password' : 'text';
        });
    });
</script>