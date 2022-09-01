/* Add gravesite location */
$('.addLocation').click(function() {
    $('#gravesiteLocation').show();
});

/* Add srave site photo */
$('.addGraveSitePhoto').click(function() {
    $('#graveSitePhoto').show();
});

function deletGraveSitePhoto(id){
    if(id){
        $.ajax({
            url: removeGraveSitePhotoUrl,
            type: 'GET', 
            data: {id : id },
            dataType: 'JSON',
            success: function (data)
            {
                if (data.success) {
                    /* Enable grave add button */
                    $('#addGraveSitePhoto').prop('disabled', false);
                    /* Add add grave site image box. */
                    $("#graveSitePhoto").html(`<label class="mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center"><div class="text-center"><em class="icon-close"></em><p class="mb-0 mt-2 font-bd h17">Upload Grave Site Image</p></div><div id="graveBase64Image"></div><input type="file" onchange="graveSiteImage(this, 'grave_site_image');" id="uploadedGraveSitePhoto"><img src="" class="img-fluid" id="showGraveSiteImage" alt="grave-site"  style="display: none"></label><div class="action d-flex align-items-center" id="addDeleteButton"><a href="javascript:void(0);" class="delete" onclick="deletGraveSitePhoto(0)" id="graveDeleteButton" style="display: none"> <em class="icon-delete"></em></a></div>`);
                    $('#graveSitePhoto').hide();
                    _toast.success(data.message);   
                    loadGravesiteDetail();      
                } else {
                    _toast.error(data.message) 
                    
                }
                
            }, error: function (err) {
                var errors = jQuery.parseJSON(err.responseText);
                _toast.error(errors.message)
            },
        });
    }else{
        /* Enable grave add button */
        $('#addGraveSitePhoto').prop('disabled', false);
        /* Add add grave site image box. */
        $("#graveSitePhoto").html(`<label class="mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center"><div class="text-center"><em class="icon-close"></em><p class="mb-0 mt-2 font-bd h17">Upload Grave Site Image</p></div><div id="graveBase64Image"></div><input type="file" onchange="graveSiteImage(this, 'grave_site_image');" id="uploadedGraveSitePhoto"><img src="" class="img-fluid" id="showGraveSiteImage" alt="grave-site"  style="display: none"></label><div class="action d-flex align-items-center" id="addDeleteButton"><a href="javascript:void(0);" class="delete" onclick="deletGraveSitePhoto(0)" id="graveDeleteButton" style="display: none"> <em class="icon-delete"></em></a></div>`);
        $('#graveSitePhoto').hide();
    }
}