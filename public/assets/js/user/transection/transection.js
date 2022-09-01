/* Load payment method list */
function loadPaymentMethod(){
    $.ajax({
        type: "GET",
        url: loadPaymentMethodListUrl,
        success: function (data) {
            $('#showManagePayment').html(data.html);
        },error: function (err) {
            var errors = jQuery.parseJSON(err.responseText);
            _toast.error(errors.message)
        },
    });
}

/* Get subscription list. */
$(document).on("click",".loadManagePayment a",function() {
    loadPaymentMethod();
});  

/*Function used to make card default confirmation. */
function makeDefaultCard(id, message) {
    bootbox.confirm({
        message: message,
        centerVertical:true,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn btn-primary ripple-effect'
            },
            cancel: {
                label: 'No',
                className: 'btn btn-outline-primary ripple-effect'
            }
        },
        callback: function (result) {

            if(result){

                $.ajax({
                    type: "GET",
                    data: {id:id},
                    url:  setDefaultCardUrl,
                    success: function (data) {
                        if(data.success){
                            _toast.success(data.message);
                            loadPaymentMethod();
                        }else{
                            _toast.error(data.message);
                        }
                    },error: function (err) {
                            var errors = jQuery.parseJSON(err.responseText);
                            _toast.error(errors.message)
                    },
                });

            }
        }
    });         
} 


/* Function used to delete the card  */
function deleteCard(id, message) {
    bootbox.confirm({
        message: message,
        centerVertical:true,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn btn-primary ripple-effect'
            },
            cancel: {
                label: 'No',
                className: 'btn btn-outline-primary ripple-effect'
            }
        },
        callback: function (result) {
            
            if(result){
                $.ajax({
                    type: "GET",
                    data: {id:id},
                    url:  deleteSaveCardUrl,
                    success: function (data) {
                        if(data.success){
                            _toast.success(data.message);
                            loadPaymentMethod();
                        }else{
                            _toast.error(data.message);
                        }
                    },error: function (err) {
                        var errors = jQuery.parseJSON(err.responseText);
                        _toast.error(errors.message)
                    },
                });
            }
        }
    });         
}

/* Add card detail*/ 
function addCard(){
    
    var form = $('#addCardForm');
    var btn = $('#addCardButton');
    var buttonText =  btn.text();

    if (form.valid()) {
        
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
                    loadPaymentMethod();
                }else{
                    btn.find("span").remove();
                    btn.text(buttonText);
                    _toast.error(data.message) 
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
                }

                _toast.error(errors.message) 
            },
        });

    }
}

/* Check all uncheck radio button */
$(document).on('change', '.checkAll', function(){

    if($(this).prop("checked")) {
        /* check all  */
        $(".checkBox").prop("checked", true);
    } else {
        /* uncheck all */
        $(".checkBox").prop("checked", false);
    }

});


$(document).on('change', '.checkBox', function(){

    if($('.checkBox:checked').length == $('.checkBox').length){
        /* if the length is same then untick  */
        $(".checkAll").prop("checked", true);
    }else {
        /* vise versa */
        $(".checkAll").prop("checked", false);            
    }

});

/* Get subscription list. */
$(document).on("click",".loadSubscriptionInfo a",function() {
    $('#loadTransectionList').empty();
    $.ajax({
        type: "GET",
        url: subscriptionListUrl,
        success: function (data) {
            $('#showSubscriptionInFoTable').html(data);
                /* To remove html from datatabel checkbox */
            $('th:first-child').hover(function(e){

                $(this).attr('data-title', $(this).attr('title'));
                $(this).removeAttr('title');

            },
            function(e){
                $(this).attr('title', $(this).attr('data-title'));

            });
        },error: function (err) {
            var errors = jQuery.parseJSON(err.responseText);
            _toast.error(errors.message)
        },
    });
});

/* Get transection list on page load */
loadUserTransection();

function loadUserTransection(){
    $('#showSubscriptionInFoTable').empty();
    $.ajax({
        type: "GET",
        url: transectionListUrl,
        success: function (data) {
            $('#loadTransectionList').html(data);
            /* To remove html from datatabel checkbox */
            $('th:first-child').hover(function(e){

                $(this).attr('data-title', $(this).attr('title'));
                $(this).removeAttr('title');

            },
            function(e){
                $(this).attr('title', $(this).attr('data-title'));

            });
        },error: function (err) {
        
            var errors = jQuery.parseJSON(err.responseText);
            _toast.error(errors.message)
        },
    });
} 

/* Get transection list on transection tab seclect */
$(document).on("click","a#transaction-history-tab",function() {
    loadUserTransection();
});

/* Get subscription detail. */
function viewDetail(id='') {

    $('#viewDetail').modal('show');
    $.ajax({
        type: "GET",
        data: {id:id},
        url: viewSubscriptionDetailUrl,
        beforeSend: function() {
            $('#showSubscriptionDetailData').html('<div class="pageLoader mt-3"><div class="spinner-border"></div></div>');
        },
        success: function (data) {
            
            if(data.success){
                $('#showSubscriptionDetailData').html(data.html);
            }else{
                _toast.error(data.message);
                $('#showSubscriptionDetailData').modal('hide');
            }

        },error: function (err) {
            var errors = jQuery.parseJSON(err.responseText);
            _toast.error(errors.message)
        },
    });
}
    
/* Function to update status active to inactive. */
function showConfirmMessage(id, message) {
    bootbox.confirm({
        message: message,
        centerVertical:true,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn btn-primary ripple-effect'
            },
            cancel: {
                label: 'No',
                className: 'btn btn-outline-primary ripple-effect'
            }
        },
        callback: function (result) {
            /* Cancel user subscription */
            if(result){

                $.ajax({
                    type: "GET",
                    data: {id:id},
                    url: cancelSubscriptionUrl,
                    success: function (data) {

                        if(data.success){
                            _toast.success(data.message);
                            addMixpanelEvent('Unsubscribed Profile');
                            
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);

                        }else{
                            _toast.error(data.message);
                        }

                    },error: function (err) {
                        var errors = jQuery.parseJSON(err.responseText);
                        _toast.error(errors.message)
                    },
                });
                
            }else{
                viewDetail(id);
            }
        }
    });         
} 

/* Buy now redirection */
function buyNowRedirect(planId, subId ,message) {
    $('#viewPlan').modal('hide');
    bootbox.confirm({
        message: message,
        centerVertical:true,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn btn-primary ripple-effect'
            },
            cancel: {
                label: 'No',
                className: 'btn btn-outline-primary ripple-effect'
            }
        }, callback: function (result) {
            /* Cancel user subscription */
            if(result){

                setTimeout(function() {
                    
                    window.location.href = planCheckoutUrl+"/"+planId+"/"+subId+"/"+'buyNewPlan';
                }, 1000);

            }
        }
    });         
} 

/* view all plan */
function viewPlan(id='') {

    setTimeout(function() {
        $('#viewPlan').modal('show');
    }, 600);

    $('#viewPlanButton').attr('onClick', 'viewDetail('+id+');');
    $.ajax({
        type: "GET",
        data: {id:id},
        url: viewSubscriptionPlanUrl,
        success: function (data) {
            
            if(data.success){
                $('#loadSubscriptionPlan').html(data.html);
            }else{
                _toast.error(data.message);
                $('#loadSubscriptionPlan').modal('hide');
            }

        },error: function (err) {
            var errors = jQuery.parseJSON(err.responseText);
            _toast.error(errors.message)
        },
    });
}

/* cancel Subscription */
function cancelSub() {
    $('#cancelSub').modal('show');
}

/* Function used to delete the card  */
function deleteDefaultConfirmation(message) {
    bootbox.confirm({
        message: message,
        centerVertical:true,
        buttons: {
            confirm: {
                label: 'Ok',
                className: 'btn btn-primary ripple-effect'
            },
            cancel: {
                label: 'No',
                className: 'btn btn-outline-primary ripple-effect'
            }
        }, callback: function (result) {

        }
    });         
}
