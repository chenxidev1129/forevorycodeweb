/* convert base64 image to file object */
function imageBase64toFile(dataurl, filename) {

    var arr = dataurl.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), 
        n = bstr.length, 
        u8arr = new Uint8Array(n);
    
    var ext = mime.split('/')[1]; // get image extension
    if (!ext) {
        ext = 'png'; // set default extension
    }

    if (!mime) {
        mime = 'image/png'; //set default image type
    }

    filename = filename + '.' + ext;    
        
    while(n--){
        u8arr[n] = bstr.charCodeAt(n);
    }
    
    return new File([u8arr], filename, {type:mime});
}

/* cropper image */
var uploadingType = '';
window.readUrlForCropper =  function(input,type) {
    if (input.files && input.files[0]) {

    if(input.files[0].type == 'image/jpg' || input.files[0].type == 'image/png' || input.files[0].type == 'image/jpeg' ){
        if (input.files[0].size >= 5120000) {
            _toast.error("Please add a image not exceeding 5 MB.");
                $('#upload_image').val('');
            }else{
                uploadingType = type;
                $('#show-image').html(''); 

                var reader = new FileReader();
                reader.onload = function(e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function() {
                        if(this.width < 200  || this.height < 200 ){
                            $('#upload_image').val('');
                            _toast.error('Please make sure for image width & height should be 200 * 200 ');
                        }else{
                            $('#cropper-modal').modal('show'); 
                            //$('#show-image').html(pageLoader());
                            setTimeout(function(){ 
                            $('#show-image').html('<img id="crop_image" class="img-fluid" src="'+e.target.result+'">');
                                loadCoverCropper()
                            }, 1000); 
                        }
                    };
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
                input.value = '';
            }
        }else{
            $('#upload_image').val('');
            _toast.error('Please upload images of format png, jpg and jpeg only.');
        }
    }
}

window.loadCoverCropper = function () {
    var $imageCover = $("#crop_image");
    $imageCover.cropper({
        aspectRatio: 10 / 10,
        cropBoxResizable: false,
        autoCropArea: 0,
        resize: false,
        strict: false,
        highlight: false,
        center: true,
        dragCrop: false,
        zoomable: true,
        zoomOnTouch: true,
        zoomOnWheel: false,
    });
}

window.saveCropperImage = function(){
    var $imageCover = $("#crop_image");
    
    if(typeof $imageCover.val() !== "undefined"){
        var imageData = $imageCover.cropper('getCroppedCanvas', { 'width': 200, 'height': 200, 'imageSmoothingQuality':'medium' }).toDataURL('image/jpeg');
        $('#set_crop_image_input').html('<input type="hidden" name="cropped_image" value="'+imageData+'">');
        $('#show-image-preview').attr('src',imageData);
    }
    $('#cropper-modal').modal('hide'); 
    $("#cropper-modal").modal().on('hidden.bs.modal', function (e) {
        $("body").addClass("modal-open");
    });
    return true;             
}    

window.cropperResetBtn = function(){
    $('#crop_image').cropper('destroy')
    $('#show-image').html();
    $('#upload_image').val('');
    $('#cropper-modal').modal('hide'); 
    $("#cropper-modal").modal().on('hidden.bs.modal', function (e) {
        $("body").addClass("modal-open");
    });
}
