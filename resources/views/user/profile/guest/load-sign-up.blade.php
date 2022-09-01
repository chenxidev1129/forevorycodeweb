<style>
.pac-container {
    z-index: 100000;
}
</style>
<link rel="stylesheet" href="{{ url('assets/css/intlTelInput.css') }}" type="text/css">
<form action="{{ route('sign-up') }}" method="post" id="guestSignUpForm" enctype = "multipart/form-data">
    @csrf
    <input type="hidden" name="country_iso_code" value="us" id="country_iso_code">
    <input type="hidden" name="country_code" value="+1" id="country_code">
    <input type="hidden" name="country_short_name" id="country_sortname">
    <input type="hidden" name="lat" id="lat">   
    <input type="hidden" name="lng" id="lng">  
    <div class="row">
        <div class="col-12">
            <div class="form-group text-center">
                <div class="uploadProfile  position-relative rounded-circle overflow-hidden mx-auto show">
                    <div class="upload__img  mx-auto text-center"> 
                    <img id="show-image-preview"  src="{{ url('assets/images/user-default.jpg') }}" alt="User-img">
                    </div>
                    <label class="rounded-circle mb-0">
                        <em class="icon-camera"></em>
                        <input type="file" class="d-none"  id="upload_image" name="upload_image" onchange="readUrlForCropper(this);" >
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
        <div class="col-sm-6">
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
        <button type="button" onclick="guestSignUp()" id="guestSignUpbutton" class="btn btn-primary ripple-effect w-100">Sign Up</button>
    </div>


    <div class="text-center mt-4">
        <p class="h15 mb-0">Already a member? <a href="javascript:void(0);" data-dismiss="modal" onclick="login()" class="theme-link font-bd">Login</a></p>
    </div>

</form>

<!-- Header -->
<script src="{{ url('assets/js/intlTelInput.js') }}"></script>
  <!-- google address js -->
<script src="{{ url('assets/js/google-address.js') }}"></script>
<!-- Google address api  -->
<script src="https://maps.googleapis.com/maps/api/js?key={{config('constants.addressApiKey')}}&libraries=places&callback=initAutocomplete"></script>
<!-- Custome js for cropper -->
<script src="{{ url('assets/js/custom.js') }}"></script>
<!-- Guest sign up form validation  -->
{!! JsValidator::formRequest('App\Http\Requests\UserSignUpRequest','#guestSignUpForm') !!}
<script src="{{ url('assets/js/user/signup/guest-signup.js') }}"></script>
