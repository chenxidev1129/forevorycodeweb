/* Card detail hide */
$(".editCard").hide();

/* Buy new paln */
function planCheckout(){
    var cardid =  $('input[name="card_id"]:checked').val();
    var planId = $("#planId").val();
    var subscriptionId = $("#subscriptionId").val();
    var planType = $("#planType").val();

    var btn = $('#planCheckoutButton');
    var buttonText =  btn.text();
    if (typeof cardid === "undefined") {
        _toast.error('Please select card to make payment or add new card');
    }else{
        
        var formData = new FormData();
        formData.append('card_id', cardid);
        formData.append('plan_id', planId);
        formData.append('id', subscriptionId);
        btn.prop('disabled', true);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: buySubscriptionUrl,
            type: 'POST', 
            data: formData,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            beforeSend: function() {
                btn.html('<span class="btnLoader ml-2 spinner-border"></span>');
            },
            success: function (data)
            {
                
                if(data.success){
                    _toast.success(data.message);
                    addMixpanelEvent('Subscription purchased');
                    
                    setTimeout(function () {
                        if(planType == 'buyNewPlan'){
                            window.location.href = transectionListUrl;
                        }else{
                            profileRedirectUrl = profileRedirectUrl.replace(':profile_id', data.profileId);
                            window.location.href =  profileRedirectUrl;
                        }
                        
                    }, 1000);

                }else{
                   
                    btn.find("span").remove();
                    btn.text(buttonText);
                    _toast.error(data.message) 
                    btn.prop('disabled', false);
                }

            
            }, error: function (err) {
                btn.prop('disabled', false);
                btn.find("span").remove();
                btn.text(buttonText);
                var errors = jQuery.parseJSON(err.responseText);
                _toast.error(errors.message)
            },
        });
    }
}

/* Add card function */ 
function addCard(){
    
    var form = $('#addCardForm');
    var btn = $('#addCardButton');
    var buttonText =  btn.text();

    if(form.valid()){
        
        btn.prop('disabled', true);
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            dataType: 'JSON',
            beforeSend: function() {
                btn.html('<span class="btnLoader ml-2 spinner-border"></span>');
            },
            success: function (data){

                if(data.success){
                    _toast.success(data.message);
                    
                    setTimeout(function() {
                            location.reload();
                        }, 1000);

                }else{
                    btn.find("span").remove();
                    btn.text(buttonText);
                    btn.prop('disabled', false);
                    _toast.error(data.message) 
                }
            
            }, error: function (err) {
                
                btn.find("span").remove();
                btn.text(buttonText);
                btn.prop('disabled', false);
                var errors = jQuery.parseJSON(err.responseText);
                
                if(err.status === 422){
                    $.each(errors.errors, function(key, val) {
                        $("#" + key + "-error").text(val);
                    });
                }

                _toast.error(errors.message) 
            },

        });
    }
}