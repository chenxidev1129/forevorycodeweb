
@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ url('assets/css/family-tree-style.css') }}" type="text/css">
@endsection

@section('content')
@section('title', __('message.family_tree'))
<!-- Main -->
<main class="main-content familyTreePage">
	<!-- profile -->
	<section class="profile pt -4 h-100">
        <a href="{{ url('view-profile/'.$profile->id) }}" class="btn btn-outline-primary btn-sm mt-2 ml-2 d-none d-lg-inline-flex backBtn">Back</a>
		<div class="profile_top h-100">
            <!-- <h1 class="h34 font-nbd title">@lang('message.family_tree')</h1> -->
            <!-- <input type="button" id="sendData" value="send data" onclick="$.send_Family({url: 'save_family.php'})"/> -->
            <div class="treeWrap position-relative">
                <div id="pk-family-tree"></div>    
                
                <!-- zoom in out range slider  -->
                <div class="treeRange d-flex justify-content-center">
                   <a href="Javascript:void(0);" class="controls d-flex align-items-center justify-content-center" id="minBtn"><em class="icon icon-circle-with-minus"></em></a>
                   <input class="range" type="range" min=".125" max="4" value="1" step="0.1" id="rangeSlide">
                   <a href="Javascript:void(0);" class="controls d-flex align-items-center justify-content-center" id="maxBtn"><em class="icon icon-circle-with-plus"></em></a>    
                </div>
               
               
              
            </div>
		</div>
	</section>
    
    <a href="javascript:void(0);" class="d-lg-none sidebarToggle showSidebar">
        <em class="icon-menu-bar"></em>
    </a>
    <div class="familySidebar">
        <div class="d-flex d-lg-block justify-content-between mb-3 pr-3">
            @if($profile->user_id == @Auth::guard(request()->guard)->user()->id)
                <h3 class="font-bd h20 px-3 mb-0">Add or Edit Family</h3>
            @else
                <h3 class="font-bd h20 px-3 mb-0">View Family Member</h3>
            @endif
            <a href="javascript:void(0);" class="showSidebar theme-color d-lg-none"><em class="icon-close"></em></a>
        </div>
        <ul class="nav nav-tabs px-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="true">Personal</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="biographical-tab" data-toggle="tab" href="#biographical" role="tab" aria-controls="biographical" aria-selected="false">Biographical</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                <form>
                    <div class="personalInfo">
                        <div class="px-3">
                            <div class="form-group ">
                                <label>Given Names</label>
                                <span id="familyMemberName">{{$profile->profile_name}}</span>
                            </div>
                            <!-- <div class="form-group mb-0">
                                <label>Surname</label>
                                <span id="familyMemberSurname">{{$profile->profile_name}}</span>
                            </div> -->
                        </div>

                        @if($profile->user_id == @Auth::guard(request()->guard)->user()->id)
                        <div class="btnColumn borderBottom px-3">
                            <a href="javascript:void(0);" onclick="editBtn()" class="btn-outline-primary btn">
                                Edit Details
                            </a>
                        </div>
                        @endif
                        <!-- common btn area was added here -->

                    </div>

                    @if($profile->user_id == @Auth::guard(request()->guard)->user()->id)
                    <div class="editBox px-3" style="display:none;">
                        <div class="form-group text-center">
                            <div class="uploadProfile show position-relative rounded-circle overflow-hidden mx-auto">
                                <img src="" id="outputImg" alt="User-img">
                                <label class="rounded-circle mb-0">
                                    <em class="icon-camera"></em>
                                    <input type="file" onchange="readUrlForCropper(this);" class="d-none" accept="image/png, image/jpg, image/jpeg">
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Given Names</label>
                            <input id="editGivenName" type="text" class="form-control" autocomplete="off" placeholder="Given Names">
                        </div>
                        <!-- <div class="form-group">
                            <label>Surname</label>
                            <input id="editGivenSurname"type="text" class="form-control" autocomplete="off" placeholder="Surname">
                        </div> -->
                        
                        <div class="form-group">
                            <label class="d-block">Gender</label>
                            <div class="custom-control custom-radio custom-control-inline mr-2">
                                <input type="radio" id="customRadio1" name="gender" class="custom-control-input" value="female">
                                <label class="custom-control-label" for="customRadio1">Female</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline mr-2">
                                <input type="radio" id="customRadio2" name="gender" class="custom-control-input" value="male">
                                <label class="custom-control-label" for="customRadio2">Male</label>
                            </div>
                            <!-- <div class="custom-control custom-radio custom-control-inline mr-0">
                                <input type="radio" id="customRadio3" name="gender" class="custom-control-input" value="other">
                                <label class="custom-control-label" for="customRadio3">Other</label>
                            </div>
                            <input type="text" class="form-control mt-1" id="otherGender" style="display:none;"> -->
                        </div>
                        <div class="form-group">
                            <label>Birth Date</label>
                            <div class="position-relative date">
                                <input class="form-control" type="text" name="" id="datepicker1" placeholder="Birth Date">
                                <span class="icon-date"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="leaveCheckbox" value="option1" name="checkLeave" checked>
                            <label class="form-check-label" for="leaveCheckbox"> This person is living</label>
                            </div>
                        </div>
                        <div class="form-group" id="deathDate" style="display: none;">
                            <label>Death Date</label>
                            <div class="position-relative date">
                                <input class="form-control" type="text" name="" id="datepicker2" placeholder="Death Date">
                                <span class="icon-date"></span>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <a href="javascript:void(0);" onclick="backPrev();" class="btn-outline-primary btn mr-2">
                                Cancel
                            </a>
                            <a href="javascript:void(0);" onclick="saveMember();" class="btn-primary btn">
                                Save
                            </a>
                        </div>
                    </div>
                    @endif
                
                </form>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <form>
                    <div class="contactInfo">
                        <div class="px-3">
                            <div class="form-group ">
                                <label>Email</label>
                                <span id="familyMemberEmail"></span>
                            </div>
                            <div class="form-group">
                                <label>Phone number</label>
                                <span id="familyMemberPhone"></span>
                            </div>
                            <!-- <div class="form-group">
                                <label>Website</label>
                                <span id="familyMemberWebsite"></span>
                            </div> -->
                            <div class="form-group mb-0">
                                <label>Address</label>
                                <span id="familyMemberAddress"></span>
                            </div>
                        </div>

                        @if($profile->user_id == @Auth::guard(request()->guard)->user()->id)
                        <div class="btnColumn borderBottom px-3">
                            <a href="javascript:void(0);" onclick="editBtn()" class="btn-outline-primary btn">
                                Edit Details
                            </a>
                        </div>
                        @endif

                    </div>

                    @if($profile->user_id == @Auth::guard(request()->guard)->user()->id)
                    <div class="editBoxContact px-3" style="display:none;">
                        <div class="form-group">
                            <label>Email</label>
                            <input id="editGivenEmail" autocomplete="off" type="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Phone number</label>
                            <input id="editGivenPhone" autocomplete="off" type="text" class="form-control" placeholder="Phone number">
                        </div>
                        <!-- <div class="form-group">
                            <label>Website</label>
                            <input id="editGivenWebsite" autocomplete="off" type="text" class="form-control">
                        </div> 
                        <div class="form-group">
                            <label>Blog</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Photo Site</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Home Phone</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Work Phone</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Skype</label>
                            <input type="text" class="form-control">
                        </div> -->
                        <div class="form-group">
                            <label>Address</label>
                            <textarea id="editGivenAddress" class="form-control" placeholder="Address"></textarea>
                        </div>
                        <!-- <div class="form-group">
                            <label>Other</label>
                            <input type="text" class="form-control">
                        </div> -->

                        <div class="text-center mt-3">
                            <a href="javascript:void(0);" onclick="backPrev();" class="btn-outline-primary btn mr-2">
                                Cancel
                            </a>
                            <a href="javascript:void(0);" onclick="saveMember();" class="btn-primary btn">
                                Save
                            </a>
                        </div>
                    </div>
                    @endif

                </form>
            </div>
            <div class="tab-pane fade" id="biographical" role="tabpanel" aria-labelledby="biographical-tab">
                <form>
                    <div class="biographicalInfo">
                        <div class="px-3">
                            <div class="form-group ">
                                <label>Birth place</label>
                                <span id="familyMemberBirthplace"></span>
                            </div>
                            <div class="form-group">
                                <label>Profession</label>
                                <span id="familyMemberProfession"></span>
                            </div>
                            <div class="form-group mb-0">
                                <label>Company</label>
                                <span id="familyMemberCompany"></span>
                            </div>
                        </div>

                        @if($profile->user_id == @Auth::guard(request()->guard)->user()->id)
                        <div class="btnColumn borderBottom px-3">
                            <a href="javascript:void(0);" onclick="editBtn()" class="btn-outline-primary btn">
                                Edit Details
                            </a>
                        </div>
                        @endif

                    </div>

                    @if($profile->user_id == @Auth::guard(request()->guard)->user()->id)
                    <div class="editBoxBiographical px-3" style="display:none;">
                        <div class="form-group">
                            <label>Birth place</label>
                            <input id="editGivenBirthplace" autocomplete="off" type="text" class="form-control" placeholder="Birth place">
                        </div>
                        <div class="form-group">
                            <label>Profession</label>
                            <input id="editGivenProfession" autocomplete="off" type="text" class="form-control" placeholder="Profession">
                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <input id="editGivenCompany" autocomplete="off" type="text" class="form-control" placeholder="Company">
                        </div>
                        <!-- <div class="form-group">
                            <label>Interests</label>
                            <textarea class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Activities</label>
                            <textarea class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Bio Notes</label>
                            <textarea class="form-control"></textarea>
                        </div> -->
                        <div class="text-center mt-3">
                            <a href="javascript:void(0);" onclick="backPrev();" class="btn-outline-primary btn mr-2">
                                Cancel
                            </a>
                            <a href="javascript:void(0);" onclick="saveMember();" class="btn-primary btn">
                                Save
                            </a>
                        </div>
                    </div>
                    @endif

                </form>
            </div>

            @if($profile->user_id == @Auth::guard(request()->guard)->user()->id)
            <!-- common btn area now added here -->
            <div id="commonBtn">
                <div class="btnColumn px-3">
                    <a id="addParentBtn" onclick="addNewMember('Parents')" href="javascript:void(0);" class="btn-outline-primary btn">
                        Add Parents
                    </a>
                    <a id="addFatherBtn" onclick="addNewMember('Father')" href="javascript:void(0);" class="btn-outline-primary btn">
                        Add Father
                    </a>
                    <a id="addMotherBtn" onclick="addNewMember('Mother')" href="javascript:void(0);" class="btn-outline-primary btn">
                        Add Mother
                    </a>
                    <a id="addPartnerBtn" onclick="addNewMember('Spouse')" href="javascript:void(0);" class="btn-outline-primary btn">
                        Add Spouse
                    </a>
                    <a id="addSiblingsBtn" onclick="addNewMember('Siblings')" href="javascript:void(0);" class="btn-outline-primary btn">
                        Add Siblings
                    </a>
                    <a id="addChildBtn" onclick="addNewMember('Child')" href="javascript:void(0);" class="btn-outline-primary btn">
                        Add Child
                    </a>
                    <a onclick="deleteMember()" href="javascript:void(0);" class="btn-outline-primary btn">
                        Delete Member
                    </a>
                </div>
                <div class="btnBottom text-center px-3">
                    <!-- <a href="javascript:void(0);" class="btn-outline-primary btn">
                        Cancel
                    </a> -->
                    <a href="javascript:void(0);" onclick="saveFamilTree()" class="btn-primary btn w-100">
                        Save Family Tree
                    </a>
                </div>
            </div>
            @endif
            
        </div>
    </div>
    
</main>

@if($profile->user_id == @Auth::guard(request()->guard)->user()->id)
<!--cropper image modal-->
<div class="modal fade modalCrop" tabindex="-1" id="cropper-modal" data-backdrop="static" data-keyboard="false" aria-labelledby="cropper-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-none">
            <div class="modal-header">
                <h5 class="modal-title">Add Member Image</h5>
                <a href="javascript:void(0);" onclick="cropperResetBtn()" class="close" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">                    
                <div class="form-group text-center">
                    <div class="upload position-relative">
                        <div id="show-image">
                            <!--set image-->
                        </div>
                    </div>
                </div>
                <div class="btnRow text-center">
                    <button type="button" class="btn btn-light ripple-effect mr-2" onclick="cropperResetBtn()" id="cropper-reset-btn"> Reset</button>
                    <button type="button" class="btn ripple-effect btn-primary" onclick="saveCropperImage()" id="cropper-image-btn">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('js')
<script src="{{ url('assets/js/jquery-ui.min.js') }}"></script>

@if($profile->user_id == @Auth::guard(request()->guard)->user()->id)
    <script>
        var saveTree = "{{ route('save-family-tree')}}";
    </script>
@endif

<script src="{{ url('assets/js/family-tree.js?').time() }}"></script>

<script>
    var treeGround = null;
    var selectedMember = null;
    var isSideBar = 0;
    
	var profileId = "{{$profile->id}}";
	loadFamilyTree();
	function loadFamilyTree(){
        if(profileId){
            $.ajax({
                url: "{{ route('get-family-tree')}}",
                type: 'GET', 
                data: {profileId : profileId },
                dataType: 'JSON',
                success: function (response)
                {
                    if (response.success) {

                        /* load show family */
						$('#pk-family-tree').pk_family_create({
							data: response.data
						});

                        setTimeout(function () {
                            /* Reset family tree width */
                            resetWidth();

                            setZoomIn();
                        },250);
                    } else {
                        _toast.error(response.message) 
                    }
                    
                }, error: function (err) {
                    var errors = jQuery.parseJSON(err.responseText);
                    _toast.error(errors.message)
                },
            });
        }
    }
	/* initialize new familty tree */
    // $('#pk-family-tree').pk_family();
</script>

@if($profile->user_id == @Auth::guard(request()->guard)->user()->id)
<script>
$(document).ready(function() {
    window.pageLoader = function(){
        return '<div class="pageLoader text-center"><div class="spinner-border" role="status"></div></div>';
    }

    window.readUrlForCropper =  function(input,type) {
        
        if (input.files && input.files[0]) {

            if(input.files[0].type == 'image/jpg' || input.files[0].type == 'image/png' || input.files[0].type == 'image/jpeg' ){
                if (input.files[0].size >= 5120000) {
                    _toast.error("Please add a image not exceeding 5 MB.");
                }else{
                    $('#show-image').html(''); 

                    var reader = new FileReader();
                    reader.onload = function(e) {
                            var image = new Image();
                            image.src = e.target.result;
                            image.onload = function() {
                                if(this.width < 100  || this.height < 100 ){
                                    _toast.error('Please make sure for image width & height should be greater than 100 * 100');
                                }else{
                                    $('#cropper-modal').modal('show'); 
                                    $('#show-image').html(pageLoader());
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
                _toast.error('Please upload images of format png, jpg and jpeg only.');
            }

        }

        // setting min height and width of cropper modal with respect of product modal
        // var product_modal_height = $('#Addmodal .modal-content').outerHeight(true);
        // $("#cropper-modal .modal-content").css('height', product_modal_height);

    }

    window.loadCoverCropper = function () {
        var $imageCover = $("#crop_image");
        $imageCover.cropper({
            aspectRatio: 1 / 1,
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
            var imageData = $imageCover.cropper('getCroppedCanvas', { 'width': 284, 'height': 284, 'imageSmoothingQuality':'medium' }).toDataURL('image/jpeg');
            uploadMemberImage(imageData);
        }
        $('#cropper-modal').modal('hide'); 
        $("#cropper-modal").modal().on('hidden.bs.modal', function (e) {
            $("body").addClass("modal-open");
        });
       return true;             
    }    

    window.cropperResetBtn = function(){
        $('#crop_image').cropper('destroy');
        $('#show-image').html();
        $('#cropper-modal').modal('hide'); 
        $("#cropper-modal").modal().on('hidden.bs.modal', function (e) {
            $("body").addClass("modal-open");
        });
    }

    /*Function used to upload profile image  */ 
    function uploadMemberImage(profileImage){
        // Created form instance
        var formData = new FormData();
        // Append data in to form
        formData.append('member_image', profileImage);
        formData.append('profile_id', profileId);
        //Ajax to update profile image in profile
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}" 
            },
            url: "{{route('upload-member-image')}}",
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
                    $('.familySidebar #myTabContent .editBox img#outputImg').attr('src',data.data).removeClass('d-none');   
                }
                
            }, error: function (err) {
                var errors = jQuery.parseJSON(err.responseText);
                _toast.error(errors.message)
            },
        })
    }
});
</script>

<script>
    var editByRelation = '';
    var memberName = '';
    var memberSurname = '';
    var memberGender = '';
    var memberBirthDate = '';
    var memberDeathDate = '';
    
    /* Contact details */
    var memberEmail = '';
    var memberPhone = '';
    var memberAddress = '';

    /* Biographical Details */
    var memberBirthplace = '';
    var memberProfession = '';
    var memberCompany = '';


    $("#datepicker1").datepicker({
        changeMonth: true,
        changeYear: true,
        show: true,
        axDate: new Date,
        //minDate: new Date(2007, 6, 12),
        maxDate: new Date,
        yearRange: '1400:+0',
        onSelect: function (selectedDate) {
            if(selectedDate) {
                $("#datepicker2").datepicker("option", "minDate", selectedDate);
            }
        }
    });

    $("#datepicker2").datepicker({
        changeMonth: true,
        changeYear: true,
        show: true,
        axDate: new Date,
        //minDate: new Date(2007, 6, 12),
        maxDate: new Date,
        yearRange: '1400:+0',
        onSelect: function (selectedDate) {
            if(selectedDate) {
                $("#datepicker1").datepicker("option", "maxDate", selectedDate);
            }
        }
    });

    $("input[name='gender']").change(function(){
    	 var radioValue = $("input[name='gender']:checked").val();
    	 if(radioValue == 'other'){
    	 	$("#otherGender").show();
    	 }else{
    	 	$("#otherGender").hide();
    	 }
    });

	$(document).ready(function(){
        $('#leaveCheckbox').click(function(){
            if($(this).prop("checked") == true){
               $("#deathDate").hide(); 
               $('#datepicker2').val('');
            }
            else if($(this).prop("checked") == false){
                $("#deathDate").show(); 
            }
        });
    });

    


    /**************************************************************************************************************************/
    /************************************************ Family tree code start here *****************************************************/
    /**************************************************************************************************************************/

    /* for edit family member */
    function editBtn() {

        if (selectedMember != null) {

            var memberImg = $(selectedMember).find('img').attr('src');
            var givenName = $(selectedMember).attr('data-name');
            var givenSurname = $(selectedMember).attr('data-surname');
            var givenGender = $(selectedMember).attr('data-gender');
            var givenDobdate = $(selectedMember).attr('data-dobdate');
            var givenDoddate = $(selectedMember).attr('data-doddate');

            /* Contact details */
            var givenEmail = $(selectedMember).attr('data-email');
            var givenPhone = $(selectedMember).attr('data-phone');
            var givenAddress = $(selectedMember).attr('data-address');

            /* Biographical Details */
            var givenBirthplace = $(selectedMember).attr('data-birthplace');
            var givenProfession = $(selectedMember).attr('data-profession');
            var givenCompany = $(selectedMember).attr('data-company');

            editByRelation = $(selectedMember).attr('data-relation');

            
            $('.familySidebar #myTabContent .editBox img#outputImg').attr('src',memberImg);

            $('.familySidebar #myTabContent .editBox #editGivenName').val(givenName);
            $('.familySidebar #myTabContent .editBox #editGivenSurname').val(givenSurname);

            if(givenGender == 'male') {
                $('.familySidebar #myTabContent .editBox #customRadio2').prop('checked',true);
                $('.familySidebar #myTabContent .editBox #customRadio1').prop('checked',false);
            } else {
                $('.familySidebar #myTabContent .editBox #customRadio1').prop('checked',true);
                $('.familySidebar #myTabContent .editBox #customRadio2').prop('checked',false);
            }
            
            $('.familySidebar #myTabContent .editBox #datepicker1').val(givenDobdate);
            $('.familySidebar #myTabContent .editBox #datepicker2').val(givenDoddate);

            if(givenDoddate) {
                $('.familySidebar #myTabContent .editBox #leaveCheckbox').prop("checked", false);
                $(".familySidebar #myTabContent .editBox #deathDate").show(); 
            } else {
                $('.familySidebar #myTabContent .editBox #leaveCheckbox').prop("checked", true);
                $(".familySidebar #myTabContent .editBox #deathDate").hide();
            }


            /* Edit Contact details */
            $('.familySidebar #myTabContent .editBoxContact #editGivenEmail').val(givenEmail);
            $('.familySidebar #myTabContent .editBoxContact #editGivenPhone').val(givenPhone);
            $('.familySidebar #myTabContent .editBoxContact #editGivenAddress').val(givenAddress);

            /* Edit Biographical details */
            $('.familySidebar #myTabContent .editBoxBiographical #editGivenBirthplace').val(givenBirthplace);
            $('.familySidebar #myTabContent .editBoxBiographical #editGivenProfession').val(givenProfession);
            $('.familySidebar #myTabContent .editBoxBiographical #editGivenCompany').val(givenCompany);


            /* Hide common btn area */
            $('.familySidebar #myTabContent #commonBtn').hide();

            /* Show personal info and Hide edit personal detail */
            $(".personalInfo").hide();
            $(".editBox").show();
            $('.tab-content').addClass('editContent')

            /* Show contact info and Hide edit contact detail */
            $(".contactInfo").hide();
            $(".editBoxContact").show();

            /* Show biographical info and Hide edit biographical detail */
            $(".biographicalInfo").hide();
            $(".editBoxBiographical").show();

        }
    }

    /* for edit save family member */
    function saveMember() {
        if (selectedMember != null) {
            
            memberName = $('.familySidebar #myTabContent .editBox #editGivenName').val();
            memberSurname = $('.familySidebar #myTabContent .editBox #editGivenSurname').val();
            memberGender =  $('.familySidebar #myTabContent .editBox input[name="gender"]:checked').val();
            memberBirthDate = $('.familySidebar #myTabContent .editBox #datepicker1').val();
            memberDeathDate = $('.familySidebar #myTabContent .editBox  #datepicker2').val();
            memberPic = $('.familySidebar #myTabContent .editBox img#outputImg').attr('src');


            /* Get contact edit detail */
            memberEmail = $('.familySidebar #myTabContent .editBoxContact #editGivenEmail').val();
            memberPhone = $('.familySidebar #myTabContent .editBoxContact #editGivenPhone').val();
            memberAddress = $('.familySidebar #myTabContent .editBoxContact #editGivenAddress').val();

            /* Get contact edit detail */
            memberBirthplace = $('.familySidebar #myTabContent .editBoxBiographical #editGivenBirthplace').val();
            memberProfession = $('.familySidebar #myTabContent .editBoxBiographical #editGivenProfession').val();
            memberCompany = $('.familySidebar #myTabContent .editBoxBiographical #editGivenCompany').val();


            /* get element */
            var sParent = $(selectedMember).parent(); // super parent
            var toPrepend = $(sParent).find("[data-relation='"+editByRelation+"']:first");
            
            /* remove old data */
            $(toPrepend).html('');

            /* Add new data */
            var center = $('<center>').appendTo(toPrepend);
            var editPic = $('<img>').attr('src', imageUrl+'/male.png');
            var extraData = "";
            if (memberGender == "male") {
                extraData = "(M)";
            } else {
                extraData = "(F)";
                $(editPic).attr('src', imageUrl+'/female.png');
            }
            $(editPic).appendTo(center);
            $(center).append($('<br>'));
            $('<span class="memberName">').html(memberName).appendTo(center);
            //$('<span>').html(extraData).appendTo(center);
            $(center).append($('<br>'));

            // if(memberSurname) {
            //     $('<span class="memberName">').html(memberSurname).appendTo(center);
            // } else {
            //     $('<span class="memberName">').html('-').appendTo(center);
            // }
            
            if(memberPic) {
                var n = memberPic.lastIndexOf('/');
                var result = memberPic.substring(n + 1);
                if(result != 'male.png' && result != 'female.png') {
                    $(editPic).attr('src', memberPic);
                }
            }

            /* Update value on attributes */
            $(toPrepend).attr('data-name', memberName);
            $(toPrepend).attr('data-surname', memberSurname);
            $(toPrepend).attr('data-gender', memberGender);
            $(toPrepend).attr('data-dobDate', memberBirthDate);
            $(toPrepend).attr('data-dodDate', memberDeathDate);

            /* Update contact detail value on attributes */
            $(toPrepend).attr('data-email', memberEmail);
            $(toPrepend).attr('data-phone', memberPhone);
            $(toPrepend).attr('data-address', memberAddress);

            /* Update biographical detail value on attributes */
            $(toPrepend).attr('data-birthplace', memberBirthplace);
            $(toPrepend).attr('data-profession', memberProfession);
            $(toPrepend).attr('data-company', memberCompany);


            viewMemberDetails(selectedMember);

            /* send to server */
            // setTimeout(function() {
            //     $.send_Family({url: saveTree});
            // }, 1000)
        }
    }

    /* Add new member in family tree */
    function addNewMember(newMember) {

        if (selectedMember != null) {
            var sParent = $(selectedMember).parent();
            var parent = $(sParent).parent();
            var parentParent = $(parent).parent();

            memberName = '';
            memberBirthDate = '';
            memberPic = '';
            memberSurname = '';
            memberDeathDate = '';
            memberGender = '';
            memberRelation = '';


            /* Contact details */
            memberEmail = '';
            memberPhone = '';
            memberAddress = '';

            /* Biographical Details */
            memberBirthplace = '';
            memberProfession = '';
            memberCompany = '';


            if(newMember == 'Parents' || newMember == 'Father') {
                memberName = 'Father of '+ $(selectedMember).attr('data-name');
                memberGender = 'male';
                memberRelation = 'Father';
            }
            else if(newMember == 'Mother') {
                memberName = 'Mother of '+ $(selectedMember).attr('data-name');
                memberGender = 'female';
                memberRelation = 'Mother';
            }
            else if(newMember == 'Spouse') {
                memberName = 'Spouse of '+ $(selectedMember).attr('data-name');
                memberGender = 'female';
                memberRelation = 'Spouse';
            }
            else if(newMember == 'Siblings') {
                memberName = 'Siblings of '+ $(selectedMember).attr('data-name');
                memberGender = 'male';
                memberRelation = 'Sibling';
            }
            else if(newMember == 'Child') {
                memberName = 'Child of '+ $(selectedMember).attr('data-name');
                memberGender = 'male';
                memberRelation = 'Child';
            }

            if(memberName) {
                var addedMember = saveNewMember();
                if(addedMember != null){
                    if(newMember == 'Parents') {
                        addNewMember('Mother');
                    }

                    viewMemberDetails(addedMember);
                    editBtn();
                }
            }
        }
    }

    /* Save new member in family tree */
    function saveNewMember() {

        var addedMember = null;

        var aLink = $('<a>').attr('href', '#');
        var center = $('<center>').appendTo(aLink);
        var pic = $('<img>').attr('src', imageUrl+'/male.png');
        var extraData = "";
        if (memberGender == "male") {
            extraData = "(M)";
        } else {
            extraData = "(F)";
            $(pic).attr('src', imageUrl+'/female.png');
        }
        $(pic).appendTo(center);
        $(center).append($('<br>'));
        $('<span class="memberName">').html(memberName).appendTo(center);
        //$('<span>').html(extraData).appendTo(center);
        $(center).append($('<br>'));
        // if(memberSurname) {
        //     $('<span class="memberName">').html(memberSurname).appendTo(center);
        // } else {
        //     $('<span class="memberName">').html('-').appendTo(center);
        // }


        var li = $('<li>').append(aLink);
        $(aLink).attr('data-name', memberName);
        $(aLink).attr('data-surname', memberSurname);
        $(aLink).attr('data-gender', memberGender);
        //$(aLink).attr('data-age', memberAge);
        $(aLink).attr('data-relation', memberRelation);
        $(aLink).attr('data-dobDate', memberBirthDate);
        $(aLink).attr('data-dodDate', memberDeathDate);


        /* Contact details */
        $(aLink).attr('data-email', memberEmail);
        $(aLink).attr('data-phone', memberPhone);
        $(aLink).attr('data-address', memberAddress);

        /* Biographical details */
        $(aLink).attr('data-birthplace', memberBirthplace);
        $(aLink).attr('data-profession', memberProfession);
        $(aLink).attr('data-company', memberCompany);


        $(aLink).mousedown(function(event) {
            if (event.button == 0) {
                viewMemberDetails(this);
            }
            return true;
        });

        
        if (selectedMember != null) {
            
            var sParent = $(selectedMember).parent(); // a parent

            if (memberRelation == 'Father') {
                
                var parent = $(sParent).parent(); // li parent
                var parentParent = $(parent).parent(); // ul parent
                
                /* Check is there any parent for grand parent */
                var treeParent = $(parentParent).attr("id");
                
                if(treeParent == 'treeGround') {

                    console.log('adding father alone');
                    //var ul = $('<ul>').append(li);
                    //$(parent).appendTo(li);
                    //$(parentParent).append(ul);

                    var ul = $('<ul>').append(sParent);
                    $(ul).appendTo(li);
                    $(parent).html(li);
                    
                } else {

                    //var motherElement = $(parentParent).find("a:first");
                    //var motherElement = $(parentParent).find("[data-relation='Mother']:first");
                    var motherElement = $(parentParent).find("a:first");
                    if(motherElement.length > 0){
                        console.log('adding back to mother');
                        var tmp = $(motherElement).parent();
                        //$(tmp).append(aLink);
                        $(motherElement).before(aLink);
                        /* add class for mother element*/
                        $(motherElement).attr('class', 'mother');
                    
                    }else{
                        console.log('adding father alone');
                        var ul = $('<ul>').append(li);
                        $(parent).appendTo(li);
                        $(parentParent).append(ul);
                    }
                }
            }
            if (memberRelation == 'Mother') {
            
                var parent = $(sParent).parent();
                var parentParent = $(parent).parent();
                
                /* Check is there any parent for grand parent */
                var treeParent = $(parentParent).attr("id");

                if(treeParent == 'treeGround') {

                    console.log('adding mother alone');
                    // var ul = $('<ul>').append(li);
                    // $(parent).appendTo(li);
                    // $(parentParent).append(ul);

                    var ul = $('<ul>').append(sParent);
                    $(ul).appendTo(li);
                    $(parent).html(li);

                } else {
                    
                    //var fatherElement = $(parentParent).find("[data-relation='Father']:first");
                    var fatherElement = $(parentParent).find("a:first");
                    if(fatherElement.length > 0){
                        console.log('adding adajecent to father');
                        var tmp = $(fatherElement).parent();
                        $(aLink).attr('class', 'mother');
                        //$(tmp).append(aLink);
                        $(fatherElement).after(aLink);
                    
                    }else{
                        console.log('adding mother alone');
                        var ul = $('<ul>').append(li);
                        $(parent).appendTo(li);
                        $(parentParent).append(ul);
                    }
                }
            }
            if (memberRelation == 'Spouse') {
                $(aLink).attr('class', 'spouse');
                var toPrepend = $(sParent).find('a:first');
                $(sParent).prepend(aLink);
                $(sParent).prepend(toPrepend);
            }
            if (memberRelation == 'Child') {
                var toAddUL = $(sParent).find('UL:first');
                if ($(toAddUL).prop('tagName') == 'UL') {
                    $(toAddUL).append(li);
                } else {
                    var ul = $('<ul>').append(li);
                    $(sParent).append(ul);
                }

            }
            if (memberRelation == 'Sibling') {
                $(sParent).parent().append(li);

            }
            
        } else {
            var ul = $('<ul>').append(li);
            $(treeGround).append(ul);

        }

        /* Reset family tree width */
        resetWidth();

        /* show added member for edit */
        return $(aLink);

    }

    /* Delete member from Family tree */
    function deleteMember() {
        if (selectedMember != null) {

            var member = nearestMemeber = selectedMember;

            if ($(member).attr('data-relation') == 'Sibling' || $(member).attr('data-relation') == 'Child') {
                var cLen = $(member).parent().parent().children('li').length;
                if (cLen > 1) {

                    /* set sibling as default member */
                    var nextNearest = $(member).closest("li").next().find('a:first');
                    var prevNearest = $(member).closest("li").prev().find('a:first');
                    if(nextNearest.length > 0) {
                        nearestMemeber = nextNearest;
                    } else {
                        nearestMemeber = prevNearest;
                    }

                    $(member).parent().remove();

                } else {

                    /* set parent as default member */
                    nearestMemeber = $(member).parent().parent().parent().find('a:first');
                    $(member).parent().parent().remove();
                }
            }
            else if ($(member).attr('data-relation') == 'Father') {
                
                /*check for mother and remove class of mother */
                var checkMotherElement = $(member).parent().find("[data-relation='Mother']:first");

                if($(checkMotherElement).siblings().is($(member))) {
                    // var child = $(member).children('ul');
                    // var parent = $(member).parent().parent();
                    // $(child).appendTo(parent);
                    $(member).remove();

                    $(checkMotherElement).attr('class', '');

                    /* set mother memebr as default member */
                    nearestMemeber = checkMotherElement;

                } else{
                    var child = $(member).parent().children('ul').children('li');
                    var parent = $(member).parent().parent();
                    $(child).appendTo(parent);
                    $(member).parent().remove();

                    /* set nearest memebr as default member */
                    nearestMemeber = $(child).children("a:first");
                }

            }
            else if ($(member).attr('data-relation') == 'Mother') {

                /*check for father and remove */
                var checkFatherElement = $(member).parent().find("[data-relation='Father']:first");
                
                if($(checkFatherElement).siblings().is($(member))) {
                    // var child = $(member).children('ul');
                    // var parent = $(member).parent().parent();
                    // $(child).appendTo(parent);
                    $(member).remove();

                    /* set mother memebr as default member */
                    nearestMemeber = checkFatherElement;
                } else {
                    var child = $(member).parent().children('ul').children('li');
                    var parent = $(member).parent().parent();
                    $(child).appendTo(parent);
                    $(member).parent().remove();

                    /* set nearest memebr as default member */
                    nearestMemeber = $(child).children("a:first");
                }

            }
            else if ($(member).attr('data-relation') == 'Spouse') {
                /* set nearest memebr as default member */
                nearestMemeber = $(member).siblings("a");
                $(member).remove();
            }

            
            viewMemberDetails(nearestMemeber);

            /* Reset family tree width */
            resetWidth();
        }

        /* send to server */
        // setTimeout(function() {
        //     $.send_Family({url: saveTree});
        // }, 1000)
    }

    /* Save Family tree to server */
    function saveFamilTree() {
        $.send_Family({url: saveTree});
    }

</script>
@endif

<script>
    /* View member details */
    function viewMemberDetails (element) {

        /* Start For open sidebar in small width */
        if(isSideBar == 1) {
            if (window.matchMedia('(max-width: 991px)').matches)
            {
                $('.familySidebar').toggleClass('familySidebar--show')
            }
        }
        isSideBar = 1;
        /* End For open sidebar in small width */

        /* start show member deatils */
        backPrev();

        selectedMember = element;
        
        /* highlight selected memeber */
        $('.tree-ground li a').removeClass('highlightMember');
        $(element).addClass('highlightMember');


        /* Check for parent is added */
        var parents = $(selectedMember).parent().parent().parent().children('a');
        if(parents.length >= 2) {

            /* when both parent are added */
            $('.familySidebar #myTabContent #commonBtn #addParentBtn').hide();
            $('.familySidebar #myTabContent #commonBtn #addFatherBtn').hide();
            $('.familySidebar #myTabContent #commonBtn #addMotherBtn').hide();

        } else if(parents.length == 1) {
            /* when single parent is added */
            $('.familySidebar #myTabContent #commonBtn #addParentBtn').hide();

            var parentRelation = $(parents).attr('data-relation');
            // if(parentRelation == 'Child' || parentRelation == 'Sibling' || parentRelation == 'self') {
                var parentGender = $(parents).attr('data-gender');
                if(parentGender == 'male') {

                    $('.familySidebar #myTabContent #commonBtn #addFatherBtn').hide();
                    $('.familySidebar #myTabContent #commonBtn #addMotherBtn').show();
                    
                } else if(parentGender == 'female') {

                    $('.familySidebar #myTabContent #commonBtn #addMotherBtn').hide();
                    $('.familySidebar #myTabContent #commonBtn #addFatherBtn').show();

                }
            // } else if(parentRelation == 'Father') {

            //     $('.familySidebar #myTabContent #commonBtn #addFatherBtn').hide();
            //     $('.familySidebar #myTabContent #commonBtn #addMotherBtn').show();

            // } else if(parentRelation == 'Mother') {

            //     $('.familySidebar #myTabContent #commonBtn #addMotherBtn').hide();
            //     $('.familySidebar #myTabContent #commonBtn #addFatherBtn').show();
                
            // }
        } else {
            /* when no parent is added */
            $('.familySidebar #myTabContent #commonBtn #addParentBtn').show();
            $('.familySidebar #myTabContent #commonBtn #addFatherBtn').hide();
            $('.familySidebar #myTabContent #commonBtn #addMotherBtn').hide();
        }

        /* Check for partner is added */
        var partnerCount = $(selectedMember).parent().children('a').length;
        if(partnerCount >= 2) {
            /* when partner is added */
            $('.familySidebar #myTabContent #commonBtn #addPartnerBtn').hide();
        } else {
            /* when partner is not added */
            $('.familySidebar #myTabContent #commonBtn #addPartnerBtn').show();
        }


        /* Show personal on view detail */
        $('.familySidebar #myTab .nav-link').removeClass('active');
        $('.familySidebar #myTab #personal-tab').addClass('active');
        $('.familySidebar #myTabContent .tab-pane').removeClass('active show');
        $('.familySidebar #myTabContent #personal').addClass('active show');
        
        /* Show member persoanl details */
        $('.familySidebar #myTabContent .personalInfo #familyMemberName').text($(element).attr('data-name'));
        $('.familySidebar #myTabContent .personalInfo #familyMemberSurname').text('').text($(element).attr('data-surname'));

        /* Show membe Contact details */
        $('.familySidebar #myTabContent .contactInfo #familyMemberEmail').text('').text($(element).attr('data-email'));
        $('.familySidebar #myTabContent .contactInfo #familyMemberPhone').text('').text($(element).attr('data-phone'));
        $('.familySidebar #myTabContent .contactInfo #familyMemberAddress').text('').text($(element).attr('data-address'));

        /* Show membe Biographical details */
        $('.familySidebar #myTabContent .biographicalInfo #familyMemberBirthplace').text('').text($(element).attr('data-birthplace'));
        $('.familySidebar #myTabContent .biographicalInfo #familyMemberProfession').text('').text($(element).attr('data-profession'));
        $('.familySidebar #myTabContent .biographicalInfo #familyMemberCompany').text('').text($(element).attr('data-company'));

    }

    function backPrev(){
        
        /* Show common btn area */
        $('.familySidebar #myTabContent #commonBtn').show();

        /* Show personal info and Hide edit personal detail */
    	$(".personalInfo").show();
    	$(".editBox").hide();
        $('.tab-content').removeClass('editContent')
        /* Show contact info and Hide edit contact detail */
        $(".contactInfo").show();
    	$(".editBoxContact").hide();

        /* Show biographical info and Hide edit biographical detail */
        $(".biographicalInfo").show();
    	$(".editBoxBiographical").hide();
    }

    $('.showSidebar').click(function(){
        $('.familySidebar').toggleClass('familySidebar--show')
    })
    if (screen.width < 576) {
        $(".header .navbar-brand").removeAttr("href");
    }


    function setZoomIn() {
        
        let zoom = 1;
        const ZOOM_SPEED = 0.1;
        const rangeSlide = document.getElementById('rangeSlide');
        const zoomElement = document.querySelector("#treeGround > ul");
        const minBtn = document.getElementById('minBtn');
        const maxBtn = document.getElementById('maxBtn');

        function zoomIn() {
            zoom -= ZOOM_SPEED; 
            zoom = Math.min(Math.max(.125, zoom), 4);             
            zoomElement.style.transform = `scale(${zoom})`;             
            $(rangeSlide).val(zoom);
        }
        function zoomOut() {
            zoom += ZOOM_SPEED; 
            zoom = Math.min(Math.max(.125, zoom), 4);         
            zoomElement.style.transform = `scale(${zoom})`;
            $(rangeSlide).val(zoom);
        }      
        zoomElement.addEventListener("wheel", function(e) {
            if(e.deltaY > 0){   
               zoomIn();
            }else{                
              zoomOut();
            }

            /* Reset family tree width */
            resetWidth();
        });
        minBtn.addEventListener("click", function() {
            zoomIn();

            /* Reset family tree width */
            resetWidth();
        });
        maxBtn.addEventListener("click", function() {
            zoomOut();

            /* Reset family tree width */
            resetWidth();
        });
        rangeSlide.oninput = function() {
            zoomlevel = rangeSlide.valueAsNumber;      
            zoomElement.style.transform = "scale("+zoomlevel+")"; 

            /* Reset family tree width */
            resetWidth();
        }
    }

    function resetWidth() {
        /* calculate family tree width */
        requiredWidth = 0;
        $('#pk-family-tree #treeGround ul li a').each(function() {
            requiredWidth += $(this).outerWidth( true ) + 10;
        });
        
        //$("#pk-family-tree #treeGround ul").attr('style','');
        $("#pk-family-tree #treeGround ul:first-child").width(requiredWidth);
    }
</script>
@endsection