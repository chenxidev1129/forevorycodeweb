<!-- right sidebar head -->
<div class="rightSidebar_head d-flex align-items-center justify-content-between">
    <h2 class="h34 font-nbd mb-0">@lang('message.edit_profile_heading')</h2>
    <a href="javascript:void(0);" class="rightSidebar_closeIcon"><em class="icon-close"></em></a>
</div>
<!-- right sidebar body -->
<form action="{{ route('edit-profile') }}" method="post" id="editProfileDetail" enctype="multipart/form-data" autocomplete="off">
    @csrf
    <div class="rightSidebar_body" id="rightSidebar_body">
        <input type="hidden" name="profile_id" id="profile_id" value="{{ $profile_id }}">
        <div class="saprateRow">
            <h3 class="h28 font-nbd mb-2 mb-sm-3">@lang('message.details')</h3>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>@lang('message.name_of_loved_one_label') <span class="mandatory">*</span></label>
                        <input type="text" name="profile_name" id="lovedProfileName" class="form-control loved-one" value="@if(!empty($getMedia) && !empty($getMedia->profile_name)){{$getMedia->profile_name}}@else{{'Ralph “Ralphy” Sarris'}}  @endif"
                            placeholder="@lang('message.name_of_loved_one_placeholder')">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>@lang('message.gender') <span class="mandatory">*</span></label>
                        <select class="selectpicker form-control" name="gender" id="gender" data-size="4" title="Select Gender">
                            <option value="male" @if(!empty(@$getMedia) && @$getMedia->gender == 'male') selected @endif>@lang('message.male')</option>
                            <option value="female"@if(!empty(@$getMedia) && @$getMedia->gender == 'female') selected @endif>@lang('message.female')</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group birthDate">
                        <label>@lang('message.birth_date_label') <span class="mandatory">*</span></label>
                        <input type="text" name="birth_date" class="form-control updateBirthDeathDate" id="birthDate" value="@if(!empty($getMedia->date_of_birth)){{ getConvertedDate($getMedia->date_of_birth, 1) }}@else{{'08/10/1950'}}@endif"
                            placeholder="@lang('message.date_placeholder')">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group deathDate">
                        <label>@lang('message.death_date_label') <span class="mandatory">*</span></label>
                        <input type="text" name="death_date" class="form-control updateBirthDeathDate" id="deathDate" value="@if(!empty($getMedia->date_of_death)){{ getConvertedDate($getMedia->date_of_death, 1) }}@else{{'22/11/2006'}}@endif"
                            placeholder="@lang('message.date_placeholder')">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>@lang('message.short_description_label') <span class="mandatory">*</span></label>
                        <input type="text" name="short_description" id="onChangeShortDescription" class="form-control" value="@if(!empty($getMedia->short_description)){{$getMedia->short_description}}@else{{'Best Brother'}}@endif"
                            placeholder="@lang('message.description_placeholder')">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>@lang('message.profile_picture_label') <span class="mandatory">*</span></label>
                <p class="h17">@lang('message.chnage_profile_and_background_image_blow')</p>

                <div class="profile position-relative">
                    <div class="uploadImg banner">
                        <label
                            class="mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center" title="{{@$getMedia->banner_image}}">
                            <!-- <div class="text-center">
                                <em class="icon-close"></em>
                                <p class="mb-0 mt-2 font-bd h17">Upload Banner Image</p>
                            </div> -->
                            <input  type="file" onchange="uploadProfileBannerImage(this, 'profileBanner');" id="uploadBackgroundImage" accept="image/*">
                            @if(!empty($getMedia->banner_image))
                                <img src="{{ getUploadMedia($getMedia->banner_image) }}" id="profileBannerImage" class="img-fluid" alt="banner">
                            @else
                                <img src="{{ url('assets/images/view-profile/profile-banner.jpg') }}" id="profileBannerImage" class="img-fluid" alt="banner">
                            @endif

                        </label>
                    </div>
                    <div class="userImg overflow-hidden">
                        <label
                            class="mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center" title="{{@$getMedia->profile_image}}">
                            <!-- <div class="text-center">
                                <em class="icon-add-user"></em>
                            </div> -->
                            <input class="d-none" type="file" onchange="uploadProfileImage(this , 'profile');"  id="upload_profile_image" accept="image/*">
                            @if(!empty($getMedia->profile_image))
                                <img src="{{ getUploadMedia($getMedia->profile_image) }}"  id="profileImage" class="img-fluid" alt="User-img">
                            @else
                                <img src="{{ url('assets/images/view-profile/ralph.png') }}"  id="profileImage" class="img-fluid" alt="User-img">
                            @endif
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="saprateRow">
            <h3 class="h28 font-nbd mb-2 mb-sm-3">@lang('message.memories')</h3>
            <div class="form-group">
                <label>@lang('message.Journey_label') <span class="mandatory">*</span></label>
                <textarea name="journey" class="form-control" id="journey" placeholder="Enter Text Here" style="height: 506px;">@if(!empty($getMedia->journey)){{ $getMedia->journey }}@endif </textarea>
                <span id="journey-error" class="help-block error-help-block"></span>
            </div>
        </div>

        <!-- photos -->
        <div class="saprateRow">
            <div class="saprateRow_head">
                <h5 class="h20 font-nbd">@lang('message.photos_text')</h5>
                <p class="h17">@lang('message.click_add_button_or_drag_image')</p>
                <div class="form-group">
                    <div class="profile position-relative">
                        <div class="uploadImg banner banner--lg">
                            <label
                                class="mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center upload-container"
                                >
                                <div class="text-center">
                                    <em class="icon-close"></em>
                                    <p class="mb-0 mt-2 font-bd h17">@lang('message.choose_a_photos_or_drag')</p>
                                </div>
                                <input type="file" class="upload-files" accept="image/*" multiple="multiple">
                                
                            </label>
                        </div>
                    </div>
                </div>
                <!-- <button type="button" class="btn btn-outline-primary ripple-effect w-100 addPhoto">Add
                    Photo</button> -->
            </div>

            <div class="photosList position-relative" id="photosList">
            </div>
        </div>

        <!-- videos -->
        <div class="saprateRow ">
            <div class="saprateRow_head">
                <h5 class="h20 font-nbd">@lang('message.videos_text')</h5>
                <p class="h17">@lang('message.click_add_button_below_or_drag_video_panel')</p>
                <div class="form-group">
                    <div class="profile position-relative">
                        <div class="uploadImg banner banner--lg">
                            <label
                                class="mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center upload-container-video">
                                <div class="text-center">
                                    <em class="icon-close"></em>
                                    <p class="mb-0 mt-2 font-bd h17">@lang('message.choose_a_videos_or_drag')</p>
                                </div>
                                <input type="file" class="upload-files-video" accept="video/*" multiple="multiple">
                                
                            </label>
                        </div>
                    </div>
                </div>
                <!-- <button type="button" class="btn btn-outline-primary ripple-effect w-100 addVideo">Add
                    Video</button> -->
            </div>

            <div class="videosList position-relative" id="videosList">
            </div>
        </div>
        <!-- voice notes -->
        <div class="saprateRow">
            <div class="saprateRow_head">
                <h5 class="h20 font-nbd">@lang('message.voice_note_text')</h5>
                <p class="h17">@lang('message.click_add_voice_note_button_or_drag_voice_note')</p>
                <label type="file" class="btn btn-outline-primary ripple-effect w-100 upload-audio-container">@lang('message.add_voice_note_label')
                    <input type="file" class="d-none upload-audio-files" accept="audio/*">
                </label>
            </div>
            <div class="pageLoader mt-3 d-none"><span class="spinner-border"></span></div>
            <div class="voiceNotes audioList" id="audioList">
            </div>
        </div>

        <!-- Stories & Articles -->
        <div class="saprateRow">
            <div class="saprateRow_head">
                <h5 class="h20 font-nbd">@lang('message.stories_article_text')</h5>
                <p class="h17">@lang('message.click_add_stories_article_button_to_add')</p>
                <button type="button" class="btn btn-outline-primary ripple-effect w-100 addArticle">@lang('message.add_stories_articles_button')</button>
            </div>
            <div class="articlesList position-relative" id="articlesList">
                @php $article = 0; @endphp
                @if(!empty($getMedia->ProfileStoriesArticle))
                
                    @foreach($getMedia->ProfileStoriesArticle as $key=>$articleRow)
                    <!-- hidden filed used to check stories article validation -->
                    <input type="hidden" name="stories-articles-validation" value="required"> 
                    <!-- Manage sotries and article dynamic id for add more section -->
                    <input type="hidden" id="storiesArticleAddMoreId" value="{{count($getMedia->ProfileStoriesArticle)}}">
                    <div class="articleRow commonBox" id="articleIndex{{$key}}">
                        <div class="form-group uploadImg mt-4">
                            <input type="hidden" id="articleId{{$key}}" name="articles-image-position[{{$key}}]" value="{{ $articleRow->id }}">
                                <label
                                    class="mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center" title="{{$articleRow->image}}">
                                    <div class="pageLoader position-absolute d-none"><span class="spinner-border"></span>
                                    </div>
                                    
                                    <input type="file" class="updateOnChange  resetStoriesArticleImage{{$key}}"  onchange="uploadStoriesArticlesImage((this), '{{ $key }}' ,'{{  $articleRow->id }}' ,'edit');" value="15" accept="image/*">
                                    <img src="{{ getUploadMedia($articleRow->image) }}" id="articleContent{{$key}}" class="img-fluid"
                                        alt="article">
                                </label>
                        </div>
                        <div class="form-group">
                            <label>@lang('message.article_label')</label>
                            <input type="text" id="storiesArticleTitle{{$key}}" name="storiesArticleTitle[{{$key}}]" class="form-control storiesArticleTitle" value="{{ $articleRow->title }}" placeholder="@lang('message.title_placeholder')" accept="image/*">
                        </div>
                        <div class="form-group ">
                            <label>@lang('message.article_label')</label>
                            <textarea id="storiesArticleText{{$key}}" name="storiesArticleText[{{$key}}]" class="form-control storiesArticleText" rows="3"
                                placeholder="Enter Text Here">{{ $articleRow->text }}</textarea>
                            <span id="storiesArticleText{{$key}}-error" class="help-block error-help-block"></span>
                        </div>
                        <div class="action d-flex align-items-center">
                            <a href="javascript:void(0);" class="delete deleteArticleRow" id="deleteArticleIndex{{$articleRow->id}}" deleteArticleIndex="{{ $articleRow->id }}"> <em class="icon-delete" ></em></a>
                            <a href="javascript:void(0);" class="bar"> <em class="icon-bar"></em></a>
                        </div>
                    </div>
                    @php  $article++; @endphp
                    @endforeach

                @endif
            </div>
        </div>

        <!-- family tree -->
        <div class="saprateRow">
            <div class="saprateRow_head">
                <h5 class="h20 font-nbd">@lang('message.family_tree_text')</h5>
                <p class="h17">@lang('message.click_the_button_below_to_add_stories_article')</p>
                <a href="{{url('family-tree/'.$profile_id)}}"
                    class="btn btn-outline-primary ripple-effect w-100">@lang('message.add_family_tree_button')</a>
            </div>
        </div>

        <!-- grave site photo -->
        <div class="saprateRow">
            <div class="saprateRow_head">
                <h5 class="h20 font-nbd">@lang('message.grave_site_photo_text')</h5>
                <p class="h17">@lang('message.click_below_to_add_an_image_grave_site_photo')</p>
                <button type="button" class="btn btn-outline-primary ripple-effect w-100 addGraveSitePhoto" id="addGraveSitePhoto" @if(!empty($getMedia->ProfileGraveSite->image)) disabled @endif>@lang('message.add_photo_button')</button>
            </div>
            <div class="form-group uploadImg" id="graveSitePhoto" @if(empty($getMedia->ProfileGraveSite->image)) style="display:none" @endif>
                <label
                    class="mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center">
                    @if(empty($getMedia->ProfileGraveSite->image))
                        <div class="text-center">
                            <em class="icon-close"></em>
                            <p class="mb-0 mt-2 font-bd h17">@lang('message.upload_grave_site_image')</p>
                        </div>
                    @endif
                    <div id="graveBase64Image">
                        <!--Set Cropper Image-->
                    </div>
                    <input type="file" onchange="graveSiteImage(this, 'grave_site_image');" id="uploadedGraveSitePhoto" accept="image/png, image/jpg, image/jpeg"  title="Grave Site Photo">
                    <img src="@if(!empty($getMedia) && !empty($getMedia->ProfileGraveSite->image)){{ getUploadMedia($getMedia->ProfileGraveSite->image)}}@endif?{{time()}}" class="img-fluid" id="showGraveSiteImage" alt="Grave Site Photo"  @if(empty($getMedia->ProfileGraveSite->image)) style="display: none" @endif>
                </label>
                @if(!empty($getMedia->ProfileGraveSite))
                    <div class="action d-flex align-items-center" id="addDeleteButton">
                        <a href="javascript:void(0);" class="delete" onclick="deletGraveSitePhoto({{$getMedia->ProfileGraveSite->id}})" id="graveDeleteButton" @if(empty($getMedia->ProfileGraveSite->image)) style="display:none" @endif> <em class="icon-delete"></em></a>
                    </div>  
                @endif
            </div>
        </div>

        <!-- gravesite location -->
        <div class="saprateRow">
            <div class="saprateRow_head">
                <h5 class="h20 font-nbd">@lang('message.gravesite_location')</h5>
                <p class="h17">@lang('message.click_blow_to_add_an_image_grave_site_location')</p>
                <button type="button" class="btn btn-outline-primary addLocation ripple-effect w-100">@lang('message.add_location_button')</button>
            </div>
            <div class="row" id="gravesiteLocation" @if(empty($getMedia->ProfileGraveSite->address)) style="display:none" @endif>
                <div class="col-12">
                    <div class="form-group" id="gravesiteDropdown">
                        <label>@lang('message.address_label')</label>
                        <input type="text" id="graveAddress" name="address" value="@if(!empty($getMedia->ProfileGraveSite->address)){{$getMedia->ProfileGraveSite->address}}@endif" class="form-control" placeholder="@lang('message.address_placeholder')">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>@lang('message.country_label')</label>
                        <input type="text" id="gravecountry" name="country" value="@if(!empty($getMedia->ProfileGraveSite->country)){{$getMedia->ProfileGraveSite->country}}@endif"  class="form-control" placeholder="@lang('message.country_placeholder')" readonly>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>@lang('message.state_label')</label>
                        <input type="text" id="graveState" name="state" value="@if(!empty($getMedia->ProfileGraveSite->state)){{$getMedia->ProfileGraveSite->state}}@endif" class="form-control" placeholder="@lang('message.state_placeholder')" readonly>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>@lang('message.city_label')</label>
                        <input type="text" id="graveCity" name="city" value="@if(!empty($getMedia->ProfileGraveSite->city)){{$getMedia->ProfileGraveSite->city}}@endif" class="form-control" placeholder="@lang('message.city_placeholder')">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>@lang('message.postal_code_label')</label>
                        <input type="text" id="graveZipCode" name="zip_code" value="@if(!empty($getMedia->ProfileGraveSite->zip_code)){{$getMedia->ProfileGraveSite->zip_code}}@endif" class="form-control" placeholder="@lang('message.postal_code_placeholder')">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>@lang('message.note_label')</label>
                        <textarea name="note" class="form-control" placeholder="@lang('message.enter_note_placeholder')">@if(!empty($getMedia->ProfileGraveSite->note)){{$getMedia->ProfileGraveSite->note}}@endif</textarea>
                    </div>
                </div>
                <input type="hidden" id="graveLat" name="lat" value="@if(!empty($getMedia->ProfileGraveSite->lat)){{$getMedia->ProfileGraveSite->lat}}@endif" id="lat">
                <input type="hidden" id="graveLng" name="lang" value="@if(!empty($getMedia->ProfileGraveSite->lang)){{$getMedia->ProfileGraveSite->lang}}@endif" id="lang">
            </div>
        </div>

        <!-- right sidebar footer -->
        <div class="rightSidebar_bottom d-flex align-items-center justify-content-center justify-content-sm-between flex-wrap">
            <!-- <a href="javascript:void(0);" class="btn btn-outline-primary ripple-effect">Share QR Code</a> -->

            <div class="w-100 text-center mt-2 mt-sm-0">
                <a href="javascript:void(0);" class="btn btn-outline-primary ripple-effect rightSidebar_close">@lang('message.cancel_title')</a>
                <button type="button" onclick="editProfileDetail()" id="editProfileDetailButton" class="btn btn-primary ripple-effect profile-save">@lang('message.save_changes_button')</button>
            </div>
        </div>
    </div>
</form>
<!--Video duration-->
<input type="hidden" name="video_duration" id="videoDuration">
    {!! JsValidator::formRequest('App\Http\Requests\EditProfileRequest','#editProfileDetail') !!}

<script>
    var profile_id = $('#profile_id').val();
    var imageArray = <?php echo json_encode($getMedia->profileMediaImage); ?>;
    /* Show uploaded video */
    var videoArray = <?php echo json_encode($getMedia->profileMediaVideo); ?>;
    /*Show uploaded voice note.*/
    var audioArray = <?php echo json_encode($getMedia->profileMediaAudio); ?>;
    
    var articleContent = "{{ $article }}";

    var uploadCaptionImageUrl = "{{route('upload-caption-image')}}";
    var updateMediaImageUrl = "{{route('update-media-image')}}";
    var uploadMediaVideoUrl = "{{route('upload-media-video')}}";
    var updateMediaVideoUrl = "{{route('update-media-video')}}";
    var uploadVoiceNoteUrl = "{{route('upload-profile-voice-note')}}";
    var removeGraveSitePhotoUrl = "{{route('remove-grave-site-photo')}}";
    var uploadProfileImageUrl = "{{route('upload-profile-image')}}";
    var uploadProfileBannerImageUrl = "{{route('upload-profile-banner-image')}}";
    var uploadArticleImageUrl = "{{route('upload-article-image')}}";
</script>

<!-- For image converter -->
<script src="{{ url('assets/js/heic2any.js') }}"></script>

<script src="{{ url('assets/js/edit-profile/edit-profile-image.js').'?'.time() }}"></script>
<script src="{{ url('assets/js/edit-profile/edit-profile-video.js') }}"></script>
<script src="{{ url('assets/js/edit-profile/edit-profile-audio.js') }}"></script>
<script src="{{ url('assets/js/edit-profile/edit-profile-stories-article.js') }}"></script>
<script src="{{ url('assets/js/edit-profile/edit-profile-gravesite.js') }}"></script>

<script src="{{ url('assets/js/edit-profile-cropper.js').'?'.time() }}"></script> 
<!-- Google address api  -->
<script src="https://maps.googleapis.com/maps/api/js?key={{config('constants.addressApiKey')}}&libraries=places&callback=initAutocomplete"></script>
<!-- google address js -->
<script src="{{ url('assets/js/edit-profile-google-address.js') }}"></script>
<script src="{{ url('assets/js/edit-profile/edit-profile.js?'.time()) }}"></script>
