$('.cardFields').hide();
/* Show add card model */
$('#cardSelect').change(function(){
    var value =  $(this).val();
    if(value == 'addNewCard'){
        $('.addNewCard').show();
    }else{
        $('.addNewCard').hide();
    }
});

/* Card number field format */
var txtCardNumber = document.querySelector(".creditCardText");
txtCardNumber.addEventListener("input", onChangeTxtCardNumber);

function onChangeTxtCardNumber(e) {
    var cardNumber = txtCardNumber.value;

    // Do not allow users to write invalid characters
    var formattedCardNumber = cardNumber.replace(/[^\d]/g, "");
    formattedCardNumber = formattedCardNumber.substring(0, 16);

    // Split the card number is groups of 4
    var cardNumberSections = formattedCardNumber.match(/\d{1,4}/g);
    if (cardNumberSections !== null) {
        formattedCardNumber = cardNumberSections.join(' ');
    }
    // If the formmattedCardNumber is different to what is shown, change the value
    if (cardNumber !== formattedCardNumber) {
        txtCardNumber.value = formattedCardNumber;
    }
}

/* Card expiry date field format */
function formatString(e) {
    var inputChar = String.fromCharCode(event.keyCode);
    var code = event.keyCode;
    var allowedKeys = [8];
    if (allowedKeys.indexOf(code) !== -1) {
        return;
    }

    var expirationDate = event.target.value = event.target.value.replace(
        /^([1-9]\/|[2-9])$/g, '0$1/' // 3 > 03/
    ).replace(
        /^(0[1-9]|1[0-2])$/g, '$1/' // 11 > 11/
    ).replace(
        /^([0-1])([3-9])$/g, '0$1/$2' // 13 > 01/3
    ).replace(
        /^(0?[1-9]|1[0-2])([0-9]{2})$/g, '$1/$2' // 141 > 01/41
    ).replace(
        /^([0]+)\/|[0]+$/g, '0' // 0/ > 0 and 00 > 0
    ).replace(
        /[^\d\/]|^[\/]*$/g, '' // To allow only digits and `/`
    ).replace(
        /\/\//g, '/' // Prevent entering more than 1 `/`
    );
    var result=expirationDate[0]+expirationDate[1];
    
}

/* Refresh country and subscription type dropdown on page load */
$('.resetSubsType').val('');    

/* Dropdown selectpicker */
$('.selectpicker').selectpicker();

$('#startFreeTrialBtn').click(function(){
    $('.startFreeTrial').addClass('open');
    $('body').addClass('overflow-hidden');
    $('body').append('<div class="rightSidebar-overlay"></div>');
});

/* Cancel button close */
$(".closeFreeTrialOnCancel, .rightSidebar_closeIcon").click(function() {  

    $('#userSubscriptionForm').trigger("reset");
    $("#userSubscriptionForm").validate().resetForm();

    $(this).parents('.rightSidebar').removeClass('open');
    $('.rightSidebar-overlay').remove();
    $('body').removeClass('overflow-hidden');
    $('#startFreeTrialBtn').show();
    $('#editProfileBtn').hide();
});

/* On change subscription validate subscription */
$(document).on('change', '#subscriptionType', function(){
    $('#subscriptionType').valid();
    var btn = $('#submitSubscriptionButton');
    if($("#subscriptionType option:selected" ).text() != 'Monthly' && $( "#subscriptionType option:selected" ).text() != 'Annual'){
        btn.text('Buy Now');
    }else{
        btn.text('Start Free Trial');
    }
    var id = this.value;
    $.ajax({
        type: "GET",
        data: {id: id },
        url: subscriptionPlanUrl,
        success: function (data) {
            if(data.success){
                
                $('#subscriptionPrice').val(data.data.price);
                $('#totalPrice').text('$'+data.data.price);
                $('#showBasicPlan').text("Basic Plan"+ '-' +'$'+data.data.price+'/'+data.data.plan);
                
            }else{
                _toast.error(data.message);
            }
        },error: function (err) {
            var errors = jQuery.parseJSON(err.responseText);
            _toast.error(errors.message)
        },
    });
});

/* Edit profile progress function */
function openAccountModel(){ 
    bootbox.confirm({
        title: 'Account Information',
        message: "Your account information is not completed, to update account information click update account button.",
        centerVertical:true,
        buttons: {
            confirm: {
                label: 'Update Account',
                className: 'btn btn-primary ripple-effect'
            },
            cancel: {
                label: ' Cancel',
                className: 'btn btn-outline-primary ripple-effect'
            }
        },
        callback: function (result) {
            if(result){
                window.location.href = editAccountUrl;
            }
        }
    });
}

/* Submit subscription */
function submitSubscription(){

    var form = $('#userSubscriptionForm');
    var method = form.attr('method');
    var btn = $('#submitSubscriptionButton');
    var buttonText =  btn.text();

    if (form.valid()) {

        btn.prop('disabled', true);
        $.ajax({

            url: form.attr('action'),
            type: method, 
            data: form.serialize(),
            dataType: 'JSON',
            beforeSend: function() {
                btn.html('<span class="btnLoader ml-2 spinner-border"></span>');
            },
            success: function (response)
            {
        
                if (response.success) {
                    _toast.success(response.message);
                    /* Send events on mix panel */
                    addMixpanelEvent('Profiles created');
                    addMixpanelEvent('Subscription purchased');

                    redirectUrl = redirectUrl.replace(':profile_id', response.data.profile_id);
                    setTimeout(function() {
                        window.location.href = redirectUrl;
                    }, 1000);
                
                } else {
                    btn.find("span").remove();
                    btn.text(buttonText);
                    
                    _toast.error(response.message) 
                    btn.prop('disabled', false);
                }
                
            }, error: function (err) {

                btn.find("span").remove();
                btn.text(buttonText);
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
}