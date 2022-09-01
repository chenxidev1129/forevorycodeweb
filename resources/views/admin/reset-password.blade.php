<!DOCTYPE html>
<html lang="en">

<head>
	<title>@lang('message.reset_password') || Forevory </title>
     @include('admin.layouts.header-links')
     <meta name="verify-token" content="{{ $verify_token }}">
</head>

<body >
    <!-- Main -->
    <main class="main-content loginPage">

        <section class="loginWrap">
        	<div class="loginWrap_outer">
                <div class="loginWrap_logo text-center">
                    <img src="{{ url('assets/images/logo.svg') }}" alt="logo">
                </div>
                <div class="bg-white loginWrap_inner">
            		<div class="text-center">
            			<h1 class="h34 font-nbd">@lang('message.reset_password')</h1>
            		</div>
            		<form action="" method="post" id="reset-password-form">
                        @csrf
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
    	        			<button id="reset-password-btn-submit" class="btn btn-primary ripple-effect w-100">@lang('message.change_title')</button>
    	        		</div>
            		</form>
                </div>
        	</div>
        </section>
        
    </main>
	<!-- Header -->
    @include('admin.layouts.footer-links')
    {!! JsValidator::formRequest('App\Http\Requests\ResetPasswordRequest','#reset-password-form') !!}
    
    <script>
    $("#reset-password-form").submit(function (e) {
        e.preventDefault();
        if ($('#reset-password-form').valid()) {
        
            var button = $(this)
            $("#reset-password-btn-submit").attr("disabled", true);

            var password = $("#reset-password-form input[name=password]").val();
            var password_confirmation = $("#reset-password-form input[name=password_confirmation]").val();
            var verify_token = $('meta[name=verify-token]').attr('content')

            $.ajax({
                type: "POST",
                url: "{{ route('admin/resetPassword') }}",
                data: {
                    password: password,
                    password_confirmation: password_confirmation,
                    verify_token: verify_token,
                    _token: "{{ csrf_token() }}" 
                },
                
                success: function(data) {
                    $("#reset-password-btn-submit").attr("disabled", false);
                    if (data.success) {
                        $('#reset-password-form').trigger("reset");
                        _toast.success('Password Reset Successful.')
                        setTimeout(function() {
                            window.location.href = "{{url('admin')}}"
                        }, 1000);
                    }
                },
                error: function(data) {
                    $("#reset-password-btn-submit").attr("disabled", false);
                    if (data.status === 422) {
                        var obj = jQuery.parseJSON(data.responseText);
                        for (var x in obj.errors) {
                            $('#reset-password-form input[name=' + x + ']').next('.error-help-block').html(obj.errors[x].join('<br>'));
                        }
                    } else if (data.status === 400) {
                        _toast.error('Password not set.')
                    }
                }
            });
        }
    });
    </script>
</body>

</html>