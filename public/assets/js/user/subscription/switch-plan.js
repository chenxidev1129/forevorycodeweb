function switchPlanMessage(id){
    
    $('#switchPlanMessage').attr('onClick', 'switchPlan('+id+');');
    $('#switchSub').modal('show');
}

/* Function used to switch plan */
function switchPlan(id) {
    var planId = id;
    var subscriptionId = $("#subscriptionId").val();
    
    var btn = $('#switchPlanMessage');
    var formData = new FormData();

    formData.append('plan_id', planId);
    formData.append('id', subscriptionId);
    btn.prop('disabled', true);
    $(".switchPlanButton").prop('disabled', true);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        url: switchPlanUrl,
        type: 'POST', 
        data: formData,
        dataType: 'JSON',
        processData: false,
        contentType: false,
        success: function (data)
        {
            if (data.success) {
                $('#switchSub').modal('hide');
                _toast.success(data.message);
                setTimeout(function () {
                    window.location.reload();
                }, 1000);

            } else {
                _toast.error(data.message) 
                btn.prop('disabled', false);
                $(".switchPlanButton").prop('disabled'. false);
            }
        
        }, error: function (err) {
            var errors = jQuery.parseJSON(err.responseText);
            _toast.error(errors.message)
            btn.prop('disabled', false);                   
        },
    });        
} 