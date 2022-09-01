/*  Function used to verify otp */
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

                    /* If login user is guest redirect to guest profile */
                    if(profileId != ''){
                        
                        redirectUrl = redirectUrl.replace(':profile_id', profileId);
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 1000)
                        
                    }else{
                        addMixpanelEvent('Login');
                        setTimeout(function() {
                            window.location.href = profileUrl;
                        }, 1000)
                    } 
                } else {
                    _toast.error(data.message) 
                    btn.prop('disabled', false);
                }
             
            },error: function (err) {
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

/* User login */
$("#userLoginForm").submit(function (e) {
    e.preventDefault(); 
    var btn = $('#submitUserLogin');
    if ($('#userLoginForm').valid()) {
        btn.prop('disabled', true);

         $.ajax({
            url: $('#userLoginForm').attr('action'),
            type: $('#userLoginForm').attr('method'), 
            data: $('#userLoginForm').serialize(),
            dataType: 'JSON',
            success: function (data)
            {
                if (data.success) {
                    /* If user is not verified */
                    if(data.data.email_verified != '1'){

                        _toast.error(data.message);
                        $("#loginUpFormDivId").hide();
                        $("#otpFormDivId").show();
                        $("#verifyEmail").val(data.data.email);
                    
                    }else{

                        _toast.success(data.message);
                        /* If login user is guest redirect to guest profile */
                        if(profileId != ''){

                            redirectUrl = redirectUrl.replace(':profile_id', profileId);
                            setTimeout(function() {
                                window.location.href = redirectUrl;
                            }, 1000)
                           
                        }else{

                            addMixpanelEvent('Login');
                            setTimeout(function() {
                                window.location.href = profileUrl;
                            }, 1000);

                        } 
                    }
                    
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
});

/* Function used to resent otp */
$("#resendOtp a").click(function() {
    var email = $('.verifyEmail').val();
    var email_type = 'login';
    $.ajax({
        type: "POST",
        data: {email: email, email_type: email_type,_token : csrfToken},
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