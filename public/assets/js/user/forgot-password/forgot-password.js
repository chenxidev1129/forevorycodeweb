//Function used to forgot password.
$("#forgotPasswordForm").submit(function (e) {
    e.preventDefault(); 
    var form = $('#forgotPasswordForm');
    var method = form.attr('method');
    var btn = $('#submitforgotPassword');
    if (form.valid()) {
        btn.prop('disabled', true);
        $.ajax({

            url: form.attr('action'),
            type: method, 
            data: form.serialize(),
            dataType: 'JSON',
            success: function (response)
            {
                if (response.success) {
                    
                    _toast.success(response.message);
                    $("#forgotPasswordFormDivId").hide();
                    $("#otpFormDivId").show();
                    $("#verifyEmail").val(response.email);
                    $("#showHiddenEmail").text('Enter the 6 digit verification code sent to '+protect_email(response.email));
            
                } else {
                    _toast.error(response.message) 
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
});

//Function used to resend otp.
$("#resendOtp a").click(function() {
    var email = $('.verifyEmail').val();
    var email_type = 'forgot-password';
    $.ajax({
        type: "POST",
        data: {email: email, email_type: email_type, _token : csrfToken},
        url:  resendUrl,
        success: function (data) {
            if(data.success){
                _toast.success(data.message);

            }else{
                _toast.error(data.message);

            }
        }
    });
});

// Function used to forgot password otp verification 
function submitForgotPasswordOtp(){
    
    var form = $('#fotgotPasswordOtpForm');
    var method = form.attr('method');
    var btn = $('#fotgotPasswordOtpButton');
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
                    $("#forgotPasswordFormDivId").hide();
                    $("#otpFormDivId").hide();
                    $("#passwordResetFormDivId").show();
                    $("#forgotPasswordEmail").val(data.email);
            
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


// Function used to reset password.
function submitResetPassword(){
    
    var form = $('#resetPasswordFrom');
    var method = form.attr('method');
    var btn = $('#resetPasswordButton');
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
                        window.location.href = homeUrl;
                        }, 2000)
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
  
//Function used to modify email in a hidden formate.
protect_email = function (user_email) {
    var avg, splitted, part1, part2;
    splitted = user_email.split("@");
    part1 = splitted[0];
    avg = part1.length / 2;
    part1 = part1.substring(0, (part1.length - avg));
    part2 = splitted[1];
    return part1 + "******@" + part2;
};
    