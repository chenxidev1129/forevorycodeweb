@extends('admin.layouts.app')
@section('content')
@section('title', __('message.change_password'))
    <!-- Main -->
    <main class="main-content loginPage changePasswordPage">

    	<section class="loginWrap">
        	<div class="loginWrap_outer bg-white">
                <div class="loginWrap_inner">
            		<div class="text-center">
            			<h1 class="h34 font-nbd">@lang('message.change_password_heading')</h1>
            		</div>
                    <form action="{{ route('admin/change-password') }}" method="post" id='updatePasswordForm'>
                        @csrf
            			<div class="form-group">
            				<label>@lang('message.current_password_label')</label>
                            <div class="position-relative passwordField">
            				    <input type="password" name="current_password" class="form-control" placeholder="@lang('message.current_password_placeholder')">                                
                                <a href="javascript:void(0);" class="showPassword">
                                    <em class="icon-eye"></em>
                                </a>
                            </div>
            			</div>
            			<div class="form-group ">
            				<label>@lang('message.new_password_label')</label>
                            <div class="position-relative passwordField">
            				    <input type="password" name="new_password" class="form-control" placeholder="@lang('message.new_password_placeholder')">
                                <a href="javascript:void(0);" class="showPassword">
                                    <em class="icon-eye"></em>
                                </a>
                            </div>
            			</div>
            			<div class="form-group">
            				<label>@lang('message.confirm_password_label')</label>
                            <div class="position-relative passwordField">
            				    <input type="password" name="confirm_password" class="form-control" placeholder="@lang('message.confirm_change_password_placeholder')">
                                <a href="javascript:void(0);" class="showPassword">
                                    <em class="icon-eye"></em>
                                </a>
                            </div>
            			</div>
            			<div class="form-group">
    	        			<button id="updatePasswordSubmit" class="btn btn-primary ripple-effect w-100">@lang('message.change_password_title')</button>
    	        		</div>
            			
            		</form>
                </div>
        	</div>
        </section>
        
    </main>
@endsection

    @section('js')
    {!! JsValidator::formRequest('App\Http\Requests\ChangePasswordRequest','#updatePasswordForm') !!}
    <script>
    $("#updatePasswordForm").submit(function (e) {
        e.preventDefault();
        var form = $('#updatePasswordForm');
        var btn = $('#updatePasswordSubmit');
        if (form.valid()) {
            btn.prop('disabled', true);
            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: form.serialize(),
                dataType: 'JSON',
                success: function (data)
                {
                    btn.prop('disabled', false);
                    if (data.success) {
                        $('#updatePasswordForm').trigger("reset");
                        _toast.success(data.message);
                    } else {
                        _toast.error(data.message)
                        
                    }
                }, error: function (err) {
                    btn.prop('disabled', false);
                    var obj = jQuery.parseJSON(err.responseText);
                    _toast.error(obj.message)
                    for (var x in obj.errors) {
                        $('#' + x + '-error').html(obj.errors[x]);
                        $('#' + x + '-error').parent('.form-group').removeClass('has-success').addClass('has-error');
                    }
                },
            });
        }
     });
    </script>

    @endsection