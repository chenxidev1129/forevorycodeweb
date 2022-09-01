<!DOCTYPE html>
<html lang="en">

<head>
    <title> @lang('message.login') || Forevory</title>
    @include('admin.layouts.header-links')
</head>

<body >

    <!-- Main -->
    <main class="main-content loginPage ">

        <section class="loginWrap">
        	<div class="loginWrap_outer">
        		<div class="loginWrap_logo text-center">
                    <img src="{{ url('assets/images/logo.svg')}}" alt="logo">
        		</div>
                <div class="bg-white loginWrap_inner">
                    <div class="text-center">
                        <h1 class="h34 font-nbd">@lang('message.login_page_heading')</h1>
                    </div>
            		<form action="{{ route('admin/login') }}" class="needs-validation" method="post" id="loginForm" novalidate>
                        @csrf
            			<div class="form-group">
            				<label>@lang('message.email_label')</label>
                            <input type="email" name="email" class="form-control" placeholder="@lang('message.email_placeholder')">
                        </div>
                        <div class="error-help-block text-danger" id="email-error"></div>
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
                            <button id="submitLoginButton" class="btn btn-primary ripple-effect w-100">@lang('message.login_button_title')</button>
                        </div>
    	        		<div class="text-center">
            				<a href="{{ route('admin/forgot-password') }}" class="theme-link font-bd h15">@lang('message.forgot_password_link')</a>
            			</div>
            		</form>
                </div>
        	</div>
        </section>
        
    </main>
    
    <!-- Header -->
    @include('admin.layouts.footer-links')
    {!! JsValidator::formRequest('App\Http\Requests\LoginRequest','#loginForm') !!}
    <script> 
    $("#loginForm").submit(function (e) {
        e.preventDefault();
        if ($('#loginForm').valid()) {
            $('#submitLoginButton').prop('disabled', true);
            $.ajax({
                url: "{{ route('admin/login') }}",
                data: $('#loginForm').serialize(),
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    $('#submitLoginButton').prop('disabled', false);
                    if (data.success) {
                        _toast.success(data.message);
                        if(data.data.user_type == 'support'){
                            var url = "{{route('admin/accounts')}}";
                        }else{
                            var url = "{{route('admin/dashboard')}}";
                        }
                        setTimeout(function() {
                            window.location.href = url;
                        }, 500)
                    } else {
                        _toast.error(data.message);
                        
                    }
                },
                error: function(err) {
                    $('#submitLoginButton').prop('disabled', false);
                    var errors = jQuery.parseJSON(err.responseText);
                    if (errors.status === 422) {
                        $.each(errors.errors, function(key, val) {
                            $("#" + key + "-error").text(val);
                        });
                    } else {
                        _toast.error(errors.message)
                    }
                }
            });
        }
    });
    </script>
</body>

</html>