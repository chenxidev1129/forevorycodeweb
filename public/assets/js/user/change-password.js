/* Change password */
function submitUpdatePassword(){
       
    var form = $('#updatePasswordFrom');
    var method = form.attr('method');
    var btn = $('#updatePasswordButton');
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
                    $('#updatePasswordFrom').trigger("reset");
                    btn.prop('disabled', false);
                    _toast.success(data.message);

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