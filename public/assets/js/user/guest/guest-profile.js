/* Open login model */
$('.authModal').on('show.bs.modal', function (event) {
    setTimeout(function(){ 
        $('body').addClass('modal-open');
    }, 1000);
    
});

/* load sign up form. */
function signUp() {
    $('#signupModal').modal('show');
    $.ajax({
        type: "GET",
        url: loadSignUpModelUrl,
        success: function (data) {
            if(data.success){
                $('#loadGuestSignUp').html(data.html);
            }else{
                _toast.error(data.message);
                $('#loadGuestSignUp').modal('hide');
            }
        },
    });
}

/* load login form */
function login() {
    $('#loginModal').modal('show');
    var profileId = $('#guestProfileId').val();
    $.ajax({
        type: "GET",
        data: { profileId : profileId},
        url: loadLoginModelUrl,
        success: function (data) {
            if(data.success){
                $('#loadGuestLogin').html(data.html);
            }else{
                _toast.error(data.message);
                $('#loadGuestLogin').modal('hide');
            }
        },
    });
}

topMenu = $("#topMenu"),
topMenuHeight = topMenu.outerHeight() + 20;
$('#signGuestBook').on('click', function (event) {
    event.preventDefault()
    $('#pills-voiceNotes-tab').tab('show');
    $('html, body').animate({
        scrollTop: $("#pills-voiceNotes-tab").offset().top - topMenuHeight
    }, 1000);
})

/* Otp verification */
function securityVerification(){
       
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
                if (data.success) 
                {
                    _toast.success(data.message);
                    setTimeout(function() {
                        location.reload(); 
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
} 

/* Resend otp */
$("#resendOtp a").click(function() {
    var email = $('#otpToEmail').val();
    var otpType = $("#otpType").val();
    var email_type = '';
    /* Check the type of email sent */
    if(otpType == 'login') {
        email_type = 'login';
    } else {
        email_type = 'sign-up';
    }

    $.ajax({
        type: "POST",
        data: {email: email, email_type: email_type,_token : $('meta[name="_token"]').attr('content')},
        url: resendOtpUrl,
        success: function (data) {
            if(data.success){
                _toast.success(data.message);

            }else{
                _toast.error(data.message);

            }
        }
    });
})


var guestBookPage = 1;

loadProfileGuestBook();

/* Guest book view */
function loadProfileGuestBook() {

    var profile_id = $('#guestProfileId').val();
    
    $.ajax({
        type: "GET",
        data: {profile_id:profile_id,limit: 10, page: guestBookPage},
        url: guestProfileGuestBook,
        success: function (response) {
            if(response.success){

                if(response.html != ''){
                    $('#loadProfileGuestBook').html(response.html);
                }else{
                    $(".loadMoreGuestBook").hide();
                    $('#loadProfileGuestBook').html("No guests have signed the guest book yet<p> Guest sign your guest book by scanning your QR code or going to your loved one's profile directly guest need to sign in or create a new account.</p>");
                    
                }
                /* Show hide load more guset book */
                if(parseInt(response.last_page)  > 1){
                    $(".loadMoreGuestBook").show();
                }else{
                    $(".loadMoreGuestBook").hide();
                }

            }else{
                _toast.error(response.message);
            
            }
        },
    });           
}