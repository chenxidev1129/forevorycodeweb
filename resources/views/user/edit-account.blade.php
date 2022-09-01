@extends('user.layouts.app')
@section('content')
@section('title', __('message.edit_account'))
<link rel="stylesheet" href="{{ url('assets/css/intlTelInput.css') }}" type="text/css">

<!-- Main -->
<main class="main-content loginPage changePasswordPage">
	<section class="loginWrap">
		<div class="loginWrap_outer">
			<div class="loginWrap_inner">
				<div class="text-center">
					<h1 class="h34 font-nbd">@lang('message.edit_account_heading')</h1> </div>
				<form action="{{ route('edit-account-detail') }}" method="post" id="editAccountDetail" enctype="multipart/form-data"> @csrf
					<input type="hidden" name="id" value="{{ $getUser->id }}">
					<input type="hidden" name="country_code" value="{{ @$getUser->country_code }}" id="country_code">
					<!-- hidden filed for get intlTelInput ios2 county  -->
					<input type="hidden" name="country_iso_code" value="{{ @$getUser->country_iso_code }}" id="country_iso_code">
					<input type="hidden" name="country_short_name" value="{{ @$getUser->country_short_name }}" id="country_sortname">
					<input type="hidden" name="lat" value="{{@$getUser->lat}}" id="lat">
					<input type="hidden" name="lng" value="{{@$getUser->lng}}" id="lng">
					<input type="hidden" value='@json($getUser->phone_number)' id="phoneNumber">
					<div class="row">
						<div class="col-12">
							<div class="form-group text-center">
								<div class="uploadProfile  position-relative rounded-circle overflow-hidden mx-auto show">
									<div class="upload__img  mx-auto text-center"> @if(!empty($getUser->image)) <img id="show-image-preview" src="{{ getUploadMedia($getUser->image) }}" alt="Profile Image"> @else <img id="show-image-preview" src="{{ url('assets/images/user-default.jpg') }}" alt="Profile Image"> @endif </div>
									<label class="rounded-circle mb-0"> <em class="icon-camera"></em>
										<input type="file" class="d-none" id="upload_image" name="upload_image" onchange="readUrlForCropper(this);" accept="image/png, image/jpg, image/jpeg"> </label>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group ">
								<label>@lang('message.first_name_label')</label>
								<input type="text" name="first_name" class="form-control text-capitalize" value="{{ @$getUser->first_name }}" placeholder="@lang('message.first_name_placeholder')"> </div>
						</div>
						<div class="col-sm-6">
							<div class="form-group ">
								<label>@lang('message.last_name_label')</label>
								<input type="text" name="last_name" class="form-control text-capitalize" value="{{ @$getUser->last_name }}" placeholder="@lang('message.last_name_placeholder')"> </div>
						</div>
						<div class="col-sm-6">
							<div class="form-group ">
								<label>@lang('message.email_label')</label>
								<input type="email" name="email" class="form-control" value="{{ @$getUser->email }}" placeholder="@lang('message.email_placeholder')"> </div>
						</div>
						<div class="col-sm-6">
							<div class="form-group ">
								<label>@lang('message.phone_number_label')</label>
								<input id="phone" class="form-control us" name="phone_number" type="tel" placeholder="@lang('message.phone_number_placeholder')" aria-describedby="phone-error"> <span id="phone-error" class="help-block error-help-block"></span> </div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>@lang('message.address_label')</label>
								<input type="text" name="address" id="address" class="form-control" value="{{ @$getUser->address }}" placeholder="@lang('message.address_placeholder')" autocapitalize="off"> </div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>@lang('message.zip_code_label')</label>
								<input type="text" name="zip_code" id="zipCode" class="form-control" value="{{ @$getUser->zip_code }}" placeholder="@lang('message.zip_code_placeholder')"> </div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>@lang('message.country_label')</label>
								<input type="text" name="country" id="country" value="{{ @$getUser->country }}" class="form-control" placeholder="@lang('message.country_placeholder')" readonly> </div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>@lang('message.state_label')</label>
								<input type="text" name="state" id="state" value="{{ @$getUser->state }}" class="form-control" placeholder="@lang('message.state_placeholder')" readonly> </div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>@lang('message.city_label')</label>
								<input type="text" name="city" id="city" value="{{ @$getUser->city }}" class="form-control" placeholder="@lang('message.city_placeholder')"> </div>
						</div>
					</div>
					<div id="set_crop_image_input">
						<!--Set Cropper Image-->
					</div>
					<div class="form-group">
						<button type="button" onclick="editAccountDetail()" id="editAccountButton" class="btn btn-primary ripple-effect w-100">@lang('message.save_changes_button')</button>
					</div>
					<!--cropper image modal-->
					<div class="modal fade modalCrop" tabindex="-1" id="cropper-modal" data-backdrop="static" data-keyboard="false" aria-labelledby="cropper-modal" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content shadow-none">
								<div class="modal-header">
									<h5 class="modal-title">@lang('message.add_image')</h5>
									<a href="javascript:void(0);" onclick="cropperResetBtn()" class="close" aria-label="Close"> <em class="icon ni ni-cross"></em> </a>
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
										<button type="button" class="btn btn-light ripple-effect mr-2" onclick="cropperResetBtn()" id="cropper-reset-btn"> @lang('message.reset_title')</button>
										<button type="button" class="btn ripple-effect btn-primary" onclick="saveCropperImage()" id="cropper-image-btn">@lang('message.save_title')</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
</main>
@endsection
@section('js')  
    {!! JsValidator::formRequest('App\Http\Requests\UserSignUpRequest','#editAccountDetail') !!}
    <script src="{{ url('assets/js/intlTelInput.js') }}"></script>
    <script src="{{ url('assets/js/custom.js') }}"></script>
    <!-- google address js -->
    <script src="{{ url('assets/js/google-address.js') }}"></script>
    <!-- Google address api  -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{config('constants.addressApiKey')}}&libraries=places&callback=initAutocomplete"></script>
    
    <script>
		var profileUrl = "{{ route('profile') }}";
	</script>
	  <script src="{{ url('assets/js/user/edit-account/edit-account.js') }}"></script>
@endsection