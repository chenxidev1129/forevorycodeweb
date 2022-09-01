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

   /*Function used to upload profile image  */ 
    function profileUpload(profileImage){
        var profile_id = $('#profile_id').val(); 
        var file = imageBase64toFile(profileImage,'profileImage');

        // Created form instance
        var formData = new FormData();
       
        // Append data in to form
        formData.append('profile_image', file);
        formData.append('profile_id', profile_id);
        //Ajax to update profile image in profile
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: uploadProfileImageUrl,
            type: "POST",
            data: formData,
            dataType:'JSON',
            contentType: false,
            cache: false,
            processData: false,
            async: false,
            success:function(data)
            { 
                if(data.success){
                    _toast.success(data.message);    
                    $('#profileImage').attr('src',profileImage);
                    $('.profileImage').attr('src',profileImage);    
                }
                
            }, error: function (err) {
                var errors = jQuery.parseJSON(err.responseText);
                _toast.error(errors.message);
            }
        })
    }

    /*Function used to read profile image */
    function uploadProfileImage(input, imageType='profile') {
        if (input.files && input.files[0]) {
            
            var fileName = input.files[0].name;
            var fileExtension = fileName.substr((fileName.lastIndexOf('.') + 1));

            if(fileExtension == 'jpg' || fileExtension == 'png' || fileExtension == 'jpeg' ){
                if (input.files[0].size >= 5120000) {
                    _toast.error("Please add a image not exceeding 5 MB.");
                    $('#upload_profile_image').val('');
                } else {
                  
                    $('#show-image').html(''); 

                    var reader = new FileReader();
                    reader.onload = function(e) {
                            var image = new Image();
                            image.src = e.target.result;
                            image.onload = function() {
                                if(this.width < 200  || this.height < 200 ){
                                    $('#upload_profile_image').val('');
                                    _toast.error('Please make sure for image width & height should be 200 * 200 ');
                                }else{
                                    $('#cropper-modal').modal('show'); 
                                    //$('#show-image').html(pageLoader());
                                    setTimeout(function(){ 
                                    $('#show-image').html('<img id="crop_image" image-type="'+imageType+'" class="img-fluid" src="'+e.target.result+'">');
                                        loadCoverCropper()
                                    }, 1000); 
                                }
                            };
                    }
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                    input.value = '';
                }
            } else if (fileExtension == 'heic') {
                console.log('Go to conversion');
                convertHeicToJpg(input,imageType,uploadProfileImage)
                 
            } else {
                $('#upload_profile_image').val('');
                _toast.error('Please upload images of format png, jpg and jpeg only.');
            }
        }
    }

    function convertHeicToJpg(input,imageType,functionName,id=0,articleId=0,type='')
    {   
        var blob = $(input)[0].files[0]; //ev.target.files[0];
        heic2any({
            blob: blob,
            toType: "image/jpg",
        })
        .then(function (resultBlob) {

            //adding converted picture to the original <input type="file">
            let fileInputElement = $(input)[0];
            let container = new DataTransfer();
            let file = new File([resultBlob], "heic"+".jpg",{type:"image/jpeg", lastModified:new Date().getTime()});
            container.items.add(file);

            fileInputElement.files = container.files;
            console.log("added");
            
            if(type != '') {
                functionName(fileInputElement, id, articleId, type, imageType);
            } else {
                functionName(fileInputElement, imageType);
            }
        })
        .catch(function (x) {
            console.log(x.code);
            console.log(x.message);
        });
        
    }

    /* Function used to upload profile banner image */ 
    function uploadProfileBanner(profileImage){
        var profile_id = $('#profile_id').val();   
        var file = imageBase64toFile(profileImage,'profileBannerImage');           
        // Created form instance
        var formData = new FormData();
        // Append data in to form
        formData.append('banner_image', file);
        formData.append('profile_id', profile_id);
        //Ajax to update profile image in profile
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: uploadProfileBannerImageUrl,
            type: "POST",
            data: formData,
            dataType:'JSON',
            contentType: false,
            cache: false,
            processData: false,
            async: false,
            success:function(data)
            { 
                if(data.success){
                _toast.success(data.message);  
                /* Show image in sidebar and profile page */
                $('#profileBannerImage').attr('src',profileImage);
                $('.profileBannerImage').attr('src',profileImage);  
            }
                
            }, error: function (err) {
                var errors = jQuery.parseJSON(err.responseText);
                _toast.error(errors.message)
            },
        })
    }

    /*Function used to read banner image */
    function uploadProfileBannerImage(input , imageType) {
        if (input.files && input.files[0]) {

            var fileName = input.files[0].name;
            var fileExtension = fileName.substr((fileName.lastIndexOf('.') + 1));

            if(fileExtension == 'jpg' || fileExtension == 'png' || fileExtension == 'jpeg' ){
                if (input.files[0].size >= 5120000) {
                    _toast.error("Please add a image not exceeding 5 MB.");
                    $('#uploadBackgroundImage').val('');
                }else{
                    
                    $('#show-image').html(''); 

                    var reader = new FileReader();
                    reader.onload = function(e) {
                            var image = new Image();
                            image.src = e.target.result;
                            image.onload = function() {
                                // if(this.width < 1000  || this.height < 358 ){
                                //     $('#uploadBackgroundImage').val('');
                                //     _toast.error('Please make sure for image width & height should be 1000 * 358 ');
                                // }else{
                                    $('#cropper-modal').modal('show'); 
                                    //$('#show-image').html(pageLoader());
                                    setTimeout(function(){ 
                                    $('#show-image').html('<img id="crop_image" image-type="'+imageType+'" class="img-fluid" src="'+e.target.result+'">');
                                        loadCoverCropper()
                                    }, 1000); 
                                // }
                            };
                    }
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                    input.value ='';
                }
            } else if (fileExtension == 'heic') {
                console.log('Go to conversion');
                convertHeicToJpg(input,imageType,uploadProfileBannerImage)
                 
            } else {
                $('#uploadBackgroundImage').val('');
                _toast.error('Please upload images of format png, jpg and jpeg only.');
            }
        }
    }    


    /*Upload user to read grave site image */
    function graveSiteImage(input, imageType) {
        if (input.files && input.files[0]) {

            var fileName = input.files[0].name;
            var fileExtension = fileName.substr((fileName.lastIndexOf('.') + 1));

            if(fileExtension == 'jpg' || fileExtension == 'png' || fileExtension == 'jpeg' ){
        
                if (input.files[0].size >= 5120000) {
                    _toast.error("Please add a image not exceeding 5 MB.");
                    $('#uploadedGraveSitePhoto').val('');
                }else{
                   
                    $('#show-image').html(''); 

                    var reader = new FileReader();
                    reader.onload = function(e) {
                            var image = new Image();
                            image.src = e.target.result;
                            image.onload = function() {
                                // if(this.width < 815  || this.height < 822 ){
                                //     $('#uploadedGraveSitePhoto').val('');
                                //     _toast.error('Please make sure for image width & height should be 815 * 822');
                                // }else{
                                    $('#cropper-modal').modal('show'); 
                                    //$('#show-image').html(pageLoader());
                                    setTimeout(function(){ 
                                    $('#show-image').html('<img id="crop_image" image-type="'+imageType+'" class="img-fluid" src="'+e.target.result+'">');
                                        loadCoverCropper()
                                    }, 1000); 
                                // }
                            };
                    }
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                    input.value = '';
                }
            } else if (fileExtension == 'heic') {
                console.log('Go to conversion');
                convertHeicToJpg(input,imageType,graveSiteImage)
                 
            } else {
                $('#uploadedGraveSitePhoto').val('');
                _toast.error('Please upload images of format png, jpg and jpeg only.');
            }
        }
    }   

    
    /*Function used to read stories article image*/
    function uploadStoriesArticlesImage(input, id, articleId, type, imageType = 'stories_articles') {
       
        if (input.files && input.files[0]) {

            var fileName = input.files[0].name;
            var fileExtension = fileName.substr((fileName.lastIndexOf('.') + 1));

            if(fileExtension == 'jpg' || fileExtension == 'png' || fileExtension == 'jpeg' ){
        
                if (input.files[0].size >= 5120000) {
                    _toast.error("Please add a image not exceeding 5 MB.");
                    $('.resetStoriesArticleImage'+id).val('');
                }else{
                   
                    $('#show-image').html(''); 

                    var reader = new FileReader();
                    reader.onload = function(e) {
                            var image = new Image();
                            image.src = e.target.result;
                            image.onload = function() {
                                // if(this.width < 815  || this.height < 822 ){
                                //     $('.resetStoriesArticleImage'+id).val('');
                                //     _toast.error('Please make sure for image width & height should be 815 * 822');
                                // }else{
                                    $('#cropper-modal').modal('show'); 
                                    //$('#show-image').html(pageLoader());
                                    setTimeout(function(){ 
                                        $('#show-image').html('<img id="crop_image" image-type="'+imageType+'" class="img-fluid" src="'+e.target.result+'">');
                                        $('#articleId').val(articleId);
                                        $('#imageShowId').val(id);
                                        $('#editType').val(type);
                                        
                                        loadCoverCropper()
                                    }, 1000); 
                                // }
                            };
                    }
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                    //input.value= '';
                }
            } else if (fileExtension == 'heic') {
                console.log('Go to conversion');
                convertHeicToJpg(input,imageType,uploadStoriesArticlesImage,id,articleId,type)
                 
            } else {
                $('.resetStoriesArticleImage'+id).val('');
                _toast.error('Please upload images of format png, jpg and jpeg only.');
            }
        }
    }  

    /*Function used to add or update stories article image */ 

    function uploadStoriesArticlesAjax(storiesArticleImage, id, articleId , type){
        var profile_id = $('#profile_id').val();   
        var file = imageBase64toFile(storiesArticleImage,'storiesArticleImage');                   
        // Created form instance
        var formData = new FormData();
        // Append data in to form
        formData.append('images', file);
        formData.append('profile_id', profile_id);
        formData.append('type', type);
        formData.append('id', articleId);
        //Ajax to update stories article image
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: uploadArticleImageUrl,
            type: "POST",
            data: formData,
            dataType:'JSON',
            contentType: false,
            cache: false,
            processData: false,
            async: false,
            success:function(data)
            { 
                if(data.success){

                     var articleIndexId = 'articleIndex'+id; 
                      
                      $('#articlesList .rowIndex'+id+'').attr('id', articleIndexId);
                      if(type == 'add'){
                        $('#articlesList #'+articleIndexId+' .uploadImg  input[name="articles-image-position['+id+']"]').val(data.id);
                        $('#articlesList #'+articleIndexId+' .action .deleteArticleRow').attr('deleteArticleIndex', data.id);
                        $('#articlesList #'+articleIndexId+' .action .deleteArticleRow').attr('id', 'deleteArticleIndex'+data.id);
                    }
                      
                      /*Update on change funtion to edit only */
                      $('#articlesList #'+articleIndexId+' .uploadImg .position-relative .updateOnChange').attr('onchange', 'uploadStoriesArticlesImage((this), '+id+', '+data.id+' , "edit");');
                      /* Update id to show update image */ 
                      $('#articlesList #'+articleIndexId+' .uploadImg img').attr('id' ,'articleContent'+id);
                      /* Hide box text */
                      $('#articlesList #'+articleIndexId+' .uploadImg .text-center').hide();
                      /* Show uplaoded image */
                      $('#articlesList #'+articleIndexId+' .uploadImg .position-relative #articleContent'+id).attr('src',storiesArticleImage);
                      /*Show image box */
                      $('#articlesList #'+articleIndexId+' .uploadImg img').show();
                      // Set class dynamic for cropper 
                      $('#articlesList #'+articleIndexId+' .uploadImg .position-relative .updateOnChange').removeClass('resetStoriesArticleImage'+id);
                      $('#articlesList #'+articleIndexId+' .uploadImg .position-relative .updateOnChange').addClass('resetStoriesArticleImage'+data.id);
            }
                
            }, error: function (err) {
                var errors = jQuery.parseJSON(err.responseText);
                _toast.error(errors.message)
            },
        })
    }


    window.loadCoverCropper = function () {
      
        var imageCover = $("#crop_image");
        var imageType = $("#crop_image").attr('image-type');
        var _widthRatio = '';
        var _heightRatio = '';
        /*Set profile image ratio */
        if(imageType == 'profile'){ 
            _widthRatio =  1; 
            _heightRatio =  1; 
        }
        /*Set profile Banner image ratio */
        if(imageType == 'profileBanner'){ 
            _widthRatio = 720;  
            _heightRatio =  179;
        }
        /*Set profile grave sit image ratio */
        if(imageType == 'grave_site_image'){ 
            _widthRatio = 815;  
            _heightRatio =  822;
        }
        /*Set profile stories article ratio */
        if(imageType == 'stories_articles'){ 
            _widthRatio = 480;  
            _heightRatio =  413;
        }
        imageCover.cropper({
            
            aspectRatio: _widthRatio / _heightRatio,
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
        var imageCover = $("#crop_image");
        var imageType = $("#crop_image").attr('image-type');
        var  width = '';
        var  height = '';
        if(typeof imageCover.val() !== "undefined"){
            if(imageType == 'profile'){  width = '284';  height = '284';}
            if(imageType == 'profileBanner'){  width = '1440';  height = '358';}
            if(imageType == 'grave_site_image'){  width = '815';  height = '822';}
            /* Get width height for stories articles image */
            if(imageType == 'stories_articles'){  width = '480';  height = '413';}
            var imageData = imageCover.cropper('getCroppedCanvas', { 'width': width, 'height': height, 'imageSmoothingQuality':'medium' }).toDataURL('image/jpeg');
         
            /*Upload cropped profile image */
            if(imageType == 'profile'){
               
                profileUpload(imageData);
               
            }
            /*Upload cropped profile banner image */
            if(imageType == 'profileBanner'){
              
                uploadProfileBanner(imageData); 
            }
            /*Upload cropped profile grave site image */
            if(imageType == 'grave_site_image'){
              
                $('#graveSitePhoto .text-center').hide();
                $('#showGraveSiteImage').attr('src', imageData);
                $('#graveBase64Image').html('<input type="hidden" name="grave_image" value="'+imageData+'">');
                // Disabled add grave button
                $('#addGraveSitePhoto').prop('disabled', true);
                $('#graveSitePhoto img').show();
                // Add delete button.
                $('#addDeleteButton').html('<a href="javascript:void(0);" class="delete" onclick="deletGraveSitePhoto(0)" id="graveDeleteButton"> <em class="icon-delete"></em></a>');
                
          }
          /*Upload cropped profile stories article image */
          if(imageType == 'stories_articles'){
              /* Show updated image */
              var id = $("#imageShowId").val();
              /* Update image in table using articleId */
              var articleId = $("#articleId").val();
              /*Check image is going to add or update */
              var editType = $("#editType").val();
              uploadStoriesArticlesAjax(imageData, id, articleId, editType); 
          }
         
        }
        $('#cropper-modal').modal('hide'); 
        $("#cropper-modal").modal().on('hidden.bs.modal', function (e) {
            $("body").addClass("modal-open");
        });
       return true;             
    }    

    window.cropperResetBtn = function(){
        var imageType = $("#crop_image").attr('image-type');
        $('#crop_image').cropper('destroy')
        $('#show-image').html();
        if(imageType == 'profile'){
            $('#upload_profile_image').val('');
        }
        if(imageType == 'profileBanner'){
            $('#uploadBackgroundImage').val('');
        }
        if(imageType == 'grave_site_image'){
            $('#uploadedGraveSitePhoto').val('');
        }
        if(imageType == 'stories_articles'){
            var id = $("#editId").val();
            $('.resetStoriesArticleImage'+id).val('');
        }
        $('#cropper-modal').modal('hide'); 
        $("#cropper-modal").modal().on('hidden.bs.modal', function (e) {
            $("body").addClass("modal-open");
        });
    }
  

