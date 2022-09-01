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

$(document).ready(function() {
    var phones = [{ "mask": "###-###-####"}];
    $('#phone').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
});



function saveSignUp(){
    var form = new FormData($('#signUpForm')[0]);

    if (form.get('cropped_image')) {
        var file = imageBase64toFile(form.get('cropped_image'), 'profile_image');
        form.delete('cropped_image');
        form.append("profile_image", file); // remove base64 image content
    }

    var btn = $('#signUpbutton');

    if ($('#signUpForm').valid()) {
       btn.prop('disabled', true);

       $.ajax({
        url: $('#signUpForm').attr('action'),
        type: $('#signUpForm').attr('method'),
        data: form,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        async: "false",
           success: function (data)
           {
               if (data.success) {   

                    _toast.success(data.message);
                    $("#signUpFormDivId").hide();
                    $("#otpFormDivId").show();
                    $("#verifyEmail").val(data.data);

                    addMixpanelEvent('Sign up');
                    
                }else {
                    _toast.error(data.message) 
                    btn.prop('disabled', false);
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
};

$("#resendOtp a").click(function() {
    var email = $('.verifyEmail').val();
    var email_type = 'sign-up';
    $.ajax({
        type: "POST",
        data: {email: email, email_type: email_type  ,_token : csrfToken},
        url: resendUrl,
        success: function (data) {
            if(data.success){
                _toast.success(data.message);

            }else{
                _toast.error(data.message);

            }
        }
    });
});


function submitOtpVerification(){
    var form = $('#otpVerificationForm');
    var method = form.attr('method');
    var btn = $('#otpVerificationButton');
  
    if (form.valid()) {
        btn.prop('disabled', true);
        $.ajax({
            url: form.attr('action'),
            type: method, 
            data: form.serialize(),
            dataType: 'JSON',
            success: function (data)
            {
                if (data.success) {
                    _toast.success(data.message);
                    setTimeout(function() {
                        /* If login user is guest redirect to guest profile */
                        if(profileId != ''){
                            redirectUrl = url.replace(':profile_id', profileId);
                            window.location.href = redirectUrl;
                        }else{
                            addMixpanelEvent('Login');
                            window.location.href = profileUrl;
                        }
                       
                       }, 2000);

                } else {
                    _toast.error(data.message) 
                    btn.prop('disabled', false);
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
};  