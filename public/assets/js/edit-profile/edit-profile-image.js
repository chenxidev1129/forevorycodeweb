/* Drag and drop multiple images */
var i = 1;
var INPUT_FILE = document.querySelector('.upload-files');
var FILES_LIST_CONTAINER = document.querySelector('#photosList');
var FILE_LIST = [];

var multipleEvents = (element, eventNames, listener) => {
    var events = eventNames.split(' ');
    
    events.forEach(event => {
    element.addEventListener(event, listener, false);
    });
};

var previewImages = () => {
    FILES_LIST_CONTAINER.innerHTML = '';

    if (FILE_LIST.length > 0) {
    
    FILE_LIST.forEach((addedFile, index) => {
        
        if(addedFile.id) {
            var content = `<div class="photoRow commonBox media align-items-center" data-index="${index}"><div class="pageLoader position-absolute d-none"><span class="spinner-border"></span></div><label class="photoRow_img mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center" title="${addedFile.name}"><div class="text-center d-none"><em class="icon-close"></em></div><input type="text" name="image_position[]" value="${addedFile.id}"><input type="file" onchange="updateUploadImage((this), ${addedFile.id});"  accept="image/*"><img class="img-fluid" src="${addedFile.url}" id="updateUploadImage${addedFile.id}" alt="${addedFile.name}"></label><div class="media-body  ml-sm-3"><div class="form-group"><label>Photo Caption</label><input type="text" name="image_caption[${addedFile.id}]" value="${addedFile.caption}" class="form-control imageCaption" placeholder="Enter Photo Caption"></div><div class="form-group" id="imagePreviewUrl${addedFile.id}"><a href="${addedFile.url}" data-fancybox="images${index}"  class="btn btn-outline-primary ripple-effect w-100">Preview Photo</a></div></div><div class="action d-flex align-items-center"><a href="#" class="delete deleteRow deleteImageRow" id="deleteImageRow${addedFile.id}" delete-image-row="${addedFile.id}"> <em class="icon-delete"></em></a><a href="javascript:void(0);" class="bar"><em class="icon-bar"></em></a></div></div>`;
        } else {
            var content = `<div id="${addedFile.boxId}" class="photoRow commonBox media align-items-center" data-index="${index}"><div class="pageLoader position-absolute"><span class="spinner-border"></span></div><label class="photoRow_img mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center" title="${addedFile.name}"><div class="text-center d-none"><em class="icon-close"></em></div><input type="text" name="image_position[]" value=""><input type="file" onchange="updateUploadImage((this), ${addedFile.id});"  accept="image/*"><img class="form__image" src="${addedFile.url}" id="updateUploadImage${index}" alt="${addedFile.name}"></label><div class="media-body  ml-sm-3"><div class="form-group"><label>Photo Caption</label><input type="text" name="image_caption[${index}]" value="${addedFile.caption}" class="form-control imageCaption" placeholder="Enter Photo Caption"></div><div class="form-group" id="imagePreviewUrl${addedFile.id}"><a href="${addedFile.url}" data-fancybox="images${index}"  class="btn btn-outline-primary ripple-effect w-100">Preview Photo</a></div></div><div class="action d-flex align-items-center"><a href="#" class="delete deleteRow deleteImageRow" id="deleteImageRow${index}" delete-image-row="${index}"><em class="icon-delete"></em></a><a href="javascript:void(0);" class="bar"> <em class="icon-bar"></em></a></div></div>`;
        }

        FILES_LIST_CONTAINER.insertAdjacentHTML('beforeEnd', content);
        
    });

    } else {
    
    INPUT_FILE.value= "";
    }
}

var fileUpload = () => {
    if (FILES_LIST_CONTAINER) {
        
        INPUT_FILE.addEventListener('change', () => {
            /*File uploading message on button submit */
            $("#editProfileDetailButton").attr('onclick', 'editProfileInProgress()');
            var files = [...INPUT_FILE.files];
            var imageFileCount = files.length;
            files.forEach(file => {
                var fileName = file.name;
                var fileURL = URL.createObjectURL(file);

                var fileExtension = fileName.substr((fileName.lastIndexOf('.') + 1));
                console.log('Go to conversion' +fileExtension);
                /* check and convert heic file format */
                if (fileExtension == 'heic') {
                    
                    console.log('Go to conversion');
                    
                    heic2any({
                        blob: file,
                        toType: "image/jpg",
                    })
                    .then(function (resultBlob) {

                        fileURL = URL.createObjectURL(resultBlob);
                        file = [];
                        file = new File([resultBlob], "heic"+".jpg",{type:"image/jpeg", lastModified:new Date().getTime()});
                    })
                    .catch(function (x) {
                        console.log(x.code);
                        console.log(x.message);
                    });
                }
                
                setTimeout(function(){
                    if(file) {
                        var boxContainId = 'content'+i;

                        if (!file.type.match("image/")){
                            _toast.error(file.name + " is not an image");
                        } else {
                            /* Add box on drop area */
                            const uploadedFiles = {
                                name: fileName,
                                url: fileURL,
                                caption : '',
                                id: '',
                                boxId: boxContainId,
                    
                            };
                            FILE_LIST.push(uploadedFiles);
                            previewImages();
                            var form = new FormData();
                            form.append('image', file);
                            form.append('profile_id', profile_id);

                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                },
                                url: uploadCaptionImageUrl,
                                method:"POST",
                                data:form,
                                dataType:'JSON', 
                                contentType: false,
                                cache: false,
                                processData: false,
                                success:function(data)
                                { 
                                    if(data.success){
                                        /*Decrement file count*/ 
                                        imageFileCount--;
                                        /*If all images are uploaded */ 
                                        if(imageFileCount == 0){
                                            $("#editProfileDetailButton").attr('onclick', 'editProfileDetail()');
                                        }
                                        
                                        $('#photosList #'+boxContainId+' .pageLoader').addClass('d-none');
                                        $('#photosList #'+boxContainId+' .photoRow_img input[name="image_position[]"]').val(data.id);
                                        $('#photosList #'+boxContainId+' .action .deleteRow').attr('delete-image-row', data.id);
                                        $('#photosList #'+boxContainId+' .action .deleteRow').attr('id', 'deleteImageRow'+data.id);
                                        
                                        /* Remove temp image from the array. */
                                        var removeImageIndex =  $("#"+boxContainId).attr('data-index');
                                        FILE_LIST.splice(removeImageIndex, 1);
                                        /* Add updated image data into array */
                                        const updateImageFiles = {
                                            name: fileName,
                                            url: fileURL,
                                            caption : '',
                                            id: data.id,
                                            boxId: boxContainId,
                                        };
                                        FILE_LIST.push(updateImageFiles);
                                        previewImages();

                                    }
                                }, error: function (err) {
                                    var errors = jQuery.parseJSON(err.responseText);
                                    _toast.error(errors.message)
                                },
                            }) 

                            i++;
                        }           
                    }       
                },1000);
            });
        }); 
    }
};

fileUpload();

/* Show uploaded image */
$.each(imageArray, function (i, elem) {
    if(elem.type == 'image'){
        var caption = '';
        if(elem.caption){
            caption = elem.caption;
        }
        const imageData = {
            name: elem.media,
            url: elem.media_with_url,
            caption : caption,
            id: elem.id,
        };
        FILE_LIST.push(imageData);
    }
});
previewImages();

/* Update uploaded media image */
function updateUploadImage(inputImage , id) {
    if (inputImage.files && inputImage.files[0]) {
        if(inputImage.files[0].type.match("image/")){
        
            var formData = new FormData();
            formData.append('image', inputImage.files[0]);
            formData.append('profile_id', profile_id);
            formData.append('id', id);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: updateMediaImageUrl,
                type: "POST",
                data: formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                async: false,
                success:function(response)
                { 
                    
                    if(response.success){
                    
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#updateUploadImage'+id).attr('src', e.target.result);
                            /* Update image preview url */
                            $('#imagePreviewUrl'+id+' a').attr('href', response.data);
                        };
                        reader.readAsDataURL(inputImage.files[0]); 
                    }
                    _toast.success(response.message);   

                }, error: function (err) {
                    var errors = jQuery.parseJSON(err.responseText);
                    _toast.error(errors.message)
                },
            })

        }else{
            _toast.error('Please upload a valid image only.');
        }
    }
} 

$(document).ready(function() {
    $("#photosList").sortable({
        handle: '.bar',
        cursor: 'move',
        axis: "y",
        start: function(e,ui)
        {
            /* Refresh position only for first drag */
            $(this).sortable("refreshPositions");
        }
    });
});