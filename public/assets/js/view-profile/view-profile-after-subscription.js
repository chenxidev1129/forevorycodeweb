 /* Load get subsciption form */
$(document).ready(function(){
    editProfile();  
});

/* Load get profile options */
function editProfile() {

    stopAudio();

    $.ajax({
        url: loadEditProfileWindowUrl,
        type: "get", 
        data: { profile_id : profile_id},
        dataType: 'JSON',
        success: function (response)
        {
            if (response.success) {
                
                $('aside.editProfile').html(response.data);
                /* Scroll edit profile side bar to top */ 
                $("#rightSidebar_body").animate({ scrollTop: 0 }, "slow");
                $('aside.editProfile').addClass('open');
                $('body').addClass('overflow-hidden');
                $('body').append('<div class="rightSidebar-overlay"></div>');
                // Show loved one text into detail page
                $("#onChangeLovedOneName").text($("#lovedProfileName").val());
                $('#pills-basicinfo-tab').tab('show');
                // Show birth and death date 
                var birthDate =  $("#birthDate").val();
                var deathDate =  $("#deathDate").val();
                $("#onChangeBirthDeathDate").text(birthDate  +' - '+  deathDate +'| Beloved Dad and Grandfather');    
                // Show short detail 
                $("#onChangeBirthDeathDate").text(birthDate  +' - '+  deathDate +' | ' +$("#onChangeShortDescription").val());
                
            } else {
                _toast.error(response.message) 
            }
        }
    });
}

/* Function used to generate QR code of the profile */
function qrCode(){
    /* Stop current audio */   
    stopAudio();

    $.ajax({
        url: generateProfileQrCodeUrl,
        type: "get", 
        data: { profile_id: profile_id},
        dataType: 'JSON',
        success: function (response)
        {
            if (response.success) {
                $('#qrCode .modal-body').html(response.data);
                $('#qrCode').modal('show');
            } else {
                _toast.error(response.message) 
            }

        }
    });
}

/* Request another qr for prodigy */
function getAnotherSticker(){

    $('#qrCode').modal('hide');
    bootbox.confirm({
    message: 'To get another sticker please reach out to Forevory at info@forevory.com',
        centerVertical:true,
        buttons: {
            cancel: {
                label: 'Cancel',
                className: 'btn btn-outline-primary ripple-effect'
            },
            confirm: {
                label: 'Okay',
                className: 'btn btn-primary ripple-effect'
            }
        
        },
        callback: function (result) {
            if(result){
                document.location = "mailto:info@forevory.com"
            }else{
                $('#qrCode').modal('show');
            }
        }
    });

}
