$('#cropper-modal').on('hidden.bs.modal', function (event) {
    setTimeout(function() {
        $('body').removeClass('modal-open');
    } ,1000);
})

var input = document.querySelector("#phone");
var addressDropdown = document.querySelector("#country_sortname");
var iti = window.intlTelInput(input, {
    initialCountry: "us",
    //separateDialCode: true, 
        
});

/* Set ios2 country  */
var country_iso_code = $("#country_iso_code").val();

if(country_iso_code){
    iti.setCountry(country_iso_code);
}
   
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

var s = $("#phoneNumber").val();
var phone = s.replace(/\D+/g, '').replace(/^(\d{3})(\d{3})(\d{4}).*/, '$1-$2-$3');

$("#phone").val(phone);

/* Function used to update user account detail */
function editAccountDetail(){  
    var form = new FormData($('#editAccountDetail')[0]);

    if (form.get('cropped_image')) {
        var file = imageBase64toFile(form.get('cropped_image'), 'profile_image');
        form.delete('cropped_image');
        form.append("profile_image", file); // remove base64 image content
    }
    
    var btn = $('#editAccountButton');
    
    if ($('#editAccountDetail').valid()) {
    
        btn.prop('disabled', true);
        $.ajax({
            url: $('#editAccountDetail').attr('action'),
            type: $('#editAccountDetail').attr('method'),
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            async: "false",
            success: function (result)
            {
                btn.prop('disabled', false);
                if (result.success) 
                    {   
                    _toast.success(result.message);
                    setTimeout(function() {
                            window.location.href = profileUrl;
                        }, 2000)
                    } else {   
                    _toast.error(result.message) 
                    
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