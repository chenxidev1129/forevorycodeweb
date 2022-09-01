$('.selectpicker').selectpicker();

/* Added journey editor and show editor value into journy tab in view */
ClassicEditor.create( document.querySelector( '#journey' ), {
    toolbar: ['Heading','Bold', 'Italic','Link']
} ).then( editor => {
    editor.model.document.on('change:data', () => {
        $( 'textarea#journey').val(editor.getData())
        $("#onChangeJourneyHide").hide(); 
        $("#onChangeJourneyShow").html(editor.getData()); 
        $(".readMoreJourney").hide(); 
    });
}).catch( error => {
   
});  

$('textarea').on('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

/* Edit profile progress function */
function editProfileInProgress(){ 
    bootbox.confirm({
        title: 'File Uploading In Progress',
        message: "Files are still uploading to your loved one's profile. Are you sure you want to discontinue uploading files? All files pending uploading will not be saved and will be removed.",
        centerVertical:true,
        buttons: {
            confirm: {
                label: 'Continue Uploading',
                className: 'btn btn-primary ripple-effect'
            },
            cancel: {
                label: ' Cancel Uploading',
                className: 'btn btn-outline-primary ripple-effect'
            }
        },
        callback: function (result) {
            if(result){
               
            }else{
                /*Cancel uploading and submit form */
                $("#editProfileDetailButton").attr('onclick', 'editProfileDetail()');
            }
        }
    });
}

/* Edit profile save function */
function editProfileDetail(){
    var form = new FormData($('#editProfileDetail')[0]);
    var btn = $('#editProfileDetailButton');
    if ($('#editProfileDetail').valid()) {
        btn.prop('disabled', true);
        $.ajax({
            url: $('#editProfileDetail').attr('action'),
            type: $('#editProfileDetail').attr('method'),
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            async: "false",
            success: function (data)
            {
                if(data.success) {
                    _toast.success(data.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000)
                    
                }else {
                    _toast.error(data.message) 
                   
                }
                btn.prop('disabled', false);
             
            }, error: function (error) {

                btn.prop('disabled', false);
                var obj = jQuery.parseJSON(error.responseText);
                
                for (var x in obj.errors) {
                    var idname = x.replace(".", "");
                    $('#' + idname + '-error').html(obj.errors[x])
                    $('#' + idname + '-error').show();
                }

            },
        });
    }
}

/*  Datepicker */
$( function() {
    $( "#deathDate" ).datepicker({
        changeMonth: true,
        changeYear: true,
        show:true,
        axDate: new Date, 
        yearRange: '1400:+0',
        maxDate: 0,
        onClose: function (selectedDate) {
            $("#birthDate").datepicker("option", "maxDate", selectedDate);
            /*Show birth and death date with short discription on view profile */
            $("#onChangeBirthDeathDate").text($("#birthDate").val()  +' - '+ $("#deathDate").val()  +'| '+$("#onChangeShortDescription").val());   
          
        }
    }).bind('click',function () {
        $("#ui-datepicker-div").appendTo(".deathDate");
    });

    $( "#birthDate" ).datepicker({
        changeMonth: true,
        changeYear: true,
        show:true,
        axDate: new Date, 
        yearRange: '1400:+0',
        maxDate: 0,
        onClose: function (selectedDate) {
            $("#deathDate").datepicker("option", "minDate", selectedDate);
             /*Show birth and death date with short discription on view profile */
            $("#onChangeBirthDeathDate").text($("#birthDate").val()  +' - '+  $("#deathDate").val() +'| ' +$("#onChangeShortDescription").val());   
        }
    }).bind('click',function () {
        $("#ui-datepicker-div").appendTo(".birthDate");
    });

});

/* Sidebar close */
$(".rightSidebar_closeIcon, .rightSidebar_close").click(function() { 

    $('audio').each(function(){
        this.pause(); // Stop playing
        this.currentTime = 0; // Reset time
    }); 

    $(this).parents('.rightSidebar').removeClass('open');
    $('.rightSidebar-overlay').remove();
    $('body').removeClass('overflow-hidden');
    $('#editProfileBtn').show();
    $('#startFreeTrialBtn').hide();

});


setTimeout(function(){ 
    $(".pac-container").prependTo("#gravesiteDropdown");
}, 300); 


/* Update name in view on input change  */
$('#lovedProfileName').keyup(function() {
    $("#onChangeLovedOneName").text(this.value);   
});

/* Update short discription on change */
$('#onChangeShortDescription').keyup(function() {
    var birthDate =  $("#birthDate").val();
    var deathDate =  $("#deathDate").val();
    $("#onChangeBirthDeathDate").text(birthDate  +' - '+  deathDate +' | ' +this.value);    
});