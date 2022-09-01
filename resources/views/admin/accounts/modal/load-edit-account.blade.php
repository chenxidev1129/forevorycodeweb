<style>
.pac-container {
    z-index: 100000;
}
</style>

<form action="{{ route('admin/edit-accout') }}" method="post" id="editAccountForm" >
@csrf
<input type="hidden" name="country_code" value="{{@$getUser->country_code}}" id="country_code">  
<input type="hidden" name="country_short_name" value="{{ @$getUser->country_short_name }}" id="country_sortname">
<input type="hidden" name="country_iso_code" value="{{@$getUser->country_iso_code}}" id="country_iso_code"> 
<input type="hidden" name="id" value="{{@$getUser->id}}">  
<input type="hidden" name="lat" value="{{@$getUser->lat}}" id="lat">   
<input type="hidden" name="lng" value="{{@$getUser->lng}}" id="lng">   
<div class="row">
    <div class="col-sm-6">
        <div class="form-group ">
            <label>@lang('message.first_name_label')</label>
            <input type="text" class="form-control" name="first_name" value="{{ @$getUser->first_name }}" placeholder="@lang('message.first_name_placeholder')">
        </div>
    </div>
        <div class="col-sm-6">
        <div class="form-group ">
            <label>@lang('message.last_name_label')</label>
            <input type="text" class="form-control" name="last_name" value="{{ @$getUser->last_name }}" placeholder="@lang('message.last_name_placeholder')">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group ">
            <label>@lang('message.email_label')</label>
            <input type="email" class="form-control" name="email" value="{{ @$getUser->email }}"  placeholder="@lang('message.email_placeholder')">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group ">
            <label>@lang('message.phone_number_label')</label>
            <input id="phone" class="form-control us" name="phone_number" value="{{@$getUser->country_code}}" type="tel"  placeholder="@lang('message.phone_number_placeholder')" aria-describedby="phone-error">
            <span id="phone-error" class="help-block error-help-block"></span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>@lang('message.address_label')</label>
            <input type="text" class="form-control" name="address" id="address" value="{{ @$getUser->address }}" placeholder="@lang('message.address_placeholder')">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>@lang('message.zip_code_label')</label>
            <input type="text" class="form-control" name="zip_code" id="zipCode" value="{{ @$getUser->zip_code }}"  placeholder="@lang('message.zip_code_placeholder')">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>@lang('message.country_label')</label>
            <input type="text" name="country" id="country" value="{{ @$getUser->country }}" class="form-control" placeholder="@lang('message.country_placeholder')" readonly>
        </div>
    </div>
    <div class="col-sm-6" >
        <div class="form-group">
            <label>@lang('message.state_label')</label>
            <input type="text" name="state" id="state" value="{{ @$getUser->state }}" class="form-control" placeholder="@lang('message.state_placeholder')" readonly>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
                <label>@lang('message.city_label')</label>
                <input type="text" name="city" id="city" value="{{ @$getUser->city }}" class="form-control" placeholder="@lang('message.city_placeholder')">
        </div>
    </div>
</div>

<div class="text-right  mt-5 submitBtn">
    <a href="javascript:void(0);" class="btn btn-outline-primary ripple-effect mr-2" data-dismiss="modal">@lang('message.cancel_title')</a>
    <button type="button" onclick="editAccout()" id="editAccountButton" class="btn btn-primary ripple-effect">@lang('message.update_title')</button>
</div>
</form>
{!! JsValidator::formRequest('App\Http\Requests\UserSignUpRequest','#editAccountForm') !!}

    <!-- google address js -->
    <script src="{{ url('assets/js/google-address.js') }}"></script>
    <!-- Google address api  -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{config('constants.addressApiKey')}}&libraries=places&callback=initAutocomplete"></script>
    
<script>
    
    $(document).ready(function() {  
       $(".selectpicker").selectpicker();
    });

    var input = document.querySelector("#phone");
    var iti = window.intlTelInput(input, {
        initialCountry: "us",
       // separateDialCode: true, 
            
    });

	/* Set ios2 country  */
	var country_iso_code = $("#country_iso_code").val();

	if(country_iso_code){
		iti.setCountry(country_iso_code)
	}

    /* listen to the phone input for changes */
    input.addEventListener('countrychange', function(e) {
        $("#country_code").val('+'+iti.getSelectedCountryData().dialCode);
        $("#country_iso_code").val(iti.getSelectedCountryData().iso2);
        
    });

   /* Phone number masking */
    $(document).ready(function() {
 
    var phones = [{ "mask": "###-###-####"}];
        $('#phone').inputmask({ 
            mask: phones, 
            greedy: false, 
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
    });

    var s = "{{ @$getUser->phone_number}}";
    var phone = s.replace(/\D+/g, '').replace(/^(\d{3})(\d{3})(\d{4}).*/, '$1-$2-$3');
    
    $("#phone").val(phone);

    /* Edit account */
    function editAccout(){
       
        var form = $('#editAccountForm');
        var btn = $('#editAccountButton');
        if (form.valid()) {
            btn.prop('disabled', true);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                dataType: 'JSON',
                success: function (data){
                    if (data.success) {
                        _toast.success(data.message);
                        setTimeout(function() {
                                location.reload();
                            }, 1000)
                    } else {
                        _toast.error(data.message) 
                        btn.prop('disabled', false);
                    }
                 
                }, error: function (err) {
                    btn.prop('disabled', false);
                    var errors = jQuery.parseJSON(err.responseText);
                    if (err.status === 422) {
                        $.each(errors.errors, function(key, val) {
                            $("#" + key + "-error").text(val);
                        });
                    } else {
                        _toast.error(errors.message)
                    }
                },
            });
        }
    };
        
</script>
