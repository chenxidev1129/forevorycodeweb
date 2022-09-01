<!DOCTYPE html>
<html lang="en">

<head>
	<title>@lang('message.forgot_password') || Forevory </title>
     @include('admin.layouts.header-links')
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
            			<h1 class="h34 font-nbd">@lang('message.forgot_password_heading')</h1>
            		</div>
            		<form action="{{ route('admin/forgot') }}" method="post" id="forgotPasswordForm">
					    {{csrf_field()  }}
            			<div class="form-group">
            				<label>@lang('message.email_label')</label>
            				<input type="email" name="email" value="" class="form-control" placeholder="@lang('message.email_placeholder')">
            			</div>
            			<div class="form-group">
    	        			<button id="submitForgotPasswordButton" class="btn btn-primary ripple-effect w-100">@lang('message.forgot_password_title')</button>
    	        		</div>
            			<div class="text-center mt-4">
            				<p class="h15 mb-0">Back to <a href="{{ url('admin') }}" class="theme-link font-bd">@lang('message.login_link')</a></p>
            			</div>

            		</form>
                </div>
        	</div>
        </section>        
    </main>

	<!-- Header -->
	@include('admin.layouts.footer-links')
	{!! JsValidator::formRequest('App\Http\Requests\ForgotPasswordRequest','#forgotPasswordForm') !!}
	<script>
    /**
     * Forgot password 
     */
    $("#forgotPasswordForm").submit(function (e) {
        e.preventDefault();
        if ($('#forgotPasswordForm').valid()) {
            
            $('#submitForgotPasswordButton').attr("disabled", true);
            $.ajax({
                url: "{{ route('admin/forgot') }}",
                data: $('#forgotPasswordForm').serialize(),
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    $('#submitForgotPasswordButton').attr("disabled", false);
                    if (data.success) {
                        $('#forgotPasswordForm').trigger("reset");
                        _toast.success(data.message);
                    }
                },
                error: function(data) {
                    $('#submitForgotPasswordButton').attr("disabled", false);
                    var obj = jQuery.parseJSON(data.responseText);
                    if (data.status === 422) {
                        $.each(obj.errors, function(key, val) {
                            $("#" + key + "-error").text(val);
                        });
                    } else if (data.status === 400) {
                        _toast.error(obj.message)
                    }
                }
            });
        }
    });
    </script>
</body>

</html>