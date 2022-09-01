 /* get default country code */
 var input = document.querySelector("#phone");
 var iti = window.intlTelInput(input, {
     initialCountry: "us",
    // separateDialCode: true, 
         
 });

 /* listen to the phone input for changes */
 input.addEventListener('countrychange', function(e) {
     $("#country_code").val('+'+iti.getSelectedCountryData().dialCode);
     $("#country_iso_code").val(iti.getSelectedCountryData().iso2);
 });

 /* Masking for phone number */
 $(document).ready(function() {
     var phones = [{ "mask": "###-###-####"}];
     $('#phone').inputmask({ 
         mask: phones, 
         greedy: false, 
         definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
 });

 /* Show password text field */
 $('.showPassword').click(function() {
     $(this).children('em').toggleClass('icon-eye icon-eye-off')
         $(this).siblings(".form-control").attr('type', function(index, attr){
         return attr == 'text' ? 'password' : 'text';
     });
 });

 /* Guest sign up  */
 function guestSignUp(){

    var form = new FormData($('#guestSignUpForm')[0]);
    if (form.get('cropped_image')) {
        var file = imageBase64toFile(form.get('cropped_image'), 'profile_image');
        form.delete('cropped_image');
        form.append("profile_image", file); // remove base64 image content
    }
    
    var btn = $('#guestSignUpbutton');

    if ($('#guestSignUpForm').valid()) {
        btn.prop('disabled', true);
        $.ajax({
            url: $('#guestSignUpForm').attr('action'),
            type: $('#guestSignUpForm').attr('method'),
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            async: "false",
            success: function (data){
                
                if(data.success){   
                    _toast.success(data.message);
                    $('#signupModal').modal('hide');
                    $('#securityVerificationModal').modal('show');
                    $("#otpToEmail").val(data.data);
                    /* Otp type to check type of emai sent */
                    $("#otpType").val('sign-up');

                }else{
                    _toast.error(data.message) 
                    btn.prop('disabled', false);
                }
                
            }, error: function (err) {
                btn.prop('disabled', false);
                var errors = jQuery.parseJSON(err.responseText);
                    
                    if(errors.status === 422){
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