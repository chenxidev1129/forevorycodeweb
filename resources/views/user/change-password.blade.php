@extends('user.layouts.app')
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
                <form action="{{ route('change-password') }}" method="post" id="updatePasswordFrom">
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
                        <button type="button" onclick="submitUpdatePassword()" id="updatePasswordButton" class="btn btn-primary ripple-effect w-100">@lang('message.save_changes_button')</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </section>
</main>
@endsection

@section('js')
{!! JsValidator::formRequest('App\Http\Requests\ChangePasswordRequest','#updatePasswordFrom') !!}
<script src="{{ url('assets/js/user/change-password.js') }}"></script>
@endsection