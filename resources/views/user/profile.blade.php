@extends('user.layouts.app')
@section('content')
@section('title', 'Index')
<!-- Main -->
<main class="main-content homePage">
	<!-- profile -->
	<section class="profile p-30">
		<div class="profile_top">
			<div class="container">
				<h1 class="h34 font-nbd title">@lang('message.profile')</h1>
				<div class="row">
					<div class="col-sm-4 col-lg-3">
						<form >
							<div class="profile_upload">
								<a href="{{ route('view-profile' ,['profile_id' => 0]) }}" style="text-decoration: none">
								<label class="d-flex align-items-center justify-content-center ripple-effect">
									<img src="{{ url('assets/images/user-icon.svg') }}" class="icon" alt="User-icon">
								
								</label>
								</a>
								<h6 class="h20 font-nbd">@lang('message.create_profile')</h6>
								<p class="h17 mb-0">@lang('message.create_loved_one_profile')</p>
							</div>
						</form>
					</div>

					@if(!empty($profiles))
						@foreach($profiles as $profile)
						
						    @if(!empty($profile->profile_image))
					
							<div class="col-sm-4 col-lg-3">
								<div class="guest">
								<a href="{{ route('view-profile' ,['profile_id' => $profile->id]) }}" style="text-decoration: none">
									<img data-progressive="{{ getUploadMedia($profile->profile_image) }}" class="img-fluid progressive__img progressive--not-loaded" alt="Esther-howard">
								</a>
									<h5 class="font-nbd text-capitalize h20">@if(!empty($profile->profile_name)){{ $profile->profile_name }} @endif</h5>
									<p class="h17 mb-0">@if(!empty($profile->date_of_birth)){{ getConvertedDate($profile->date_of_birth, 1) }} @endif - @if(!empty($profile->date_of_death)){{ getConvertedDate($profile->date_of_death, 1) }} @endif <br> @if(!empty($profile->short_description)){{ $profile->short_description }} @endif</p>
								</div>
							</div>
							@else
							<div class="col-sm-4 col-lg-3">
								<div class="guest">
								<a href="{{ route('view-profile' ,['profile_id' => $profile->id]) }}" style="text-decoration: none">
									<img data-progressive="{{ url('assets/images/view-profile/ralph.png') }}" class="img-fluid progressive__img progressive--not-loaded" alt="Esther-howard">
								</a>
								<h5 class="font-nbd text-capitalize h20">@if(!empty($profile->profile_name)){{ $profile->profile_name }} @endif</h5>
									<p class="h17 mb-0">@if(!empty($profile->date_of_birth)){{ getConvertedDate($profile->date_of_birth, 1) }} @endif - @if(!empty($profile->date_of_death)){{ getConvertedDate($profile->date_of_death, 1) }} @endif <br> @if(!empty($profile->short_description)){{ $profile->short_description }} @endif</p>
								</div>
							</div>
							<!-- <div class="col-sm-4 col-lg-3">
								<div class="profile_upload">
									<a href="{{ route('view-profile' ,['profile_id' => $profile->id]) }}" style="text-decoration: none">
										<label class="d-flex align-items-center justify-content-center ripple-effect">
											<img src="{{ url('assets/images/user-icon.svg') }}" class="icon" alt="User-icon">
										</label>
									</a>
									<h6 class="h20 font-nbd">@lang('message.update_profile')</h6>
									<p class="h17 mb-0">@lang('message.update_loved_one_profile')</p>
								</div>
							</div> -->
							@endif

						
						@endforeach
					@endif

				</div>
			</div>
		</div>
		<div class="profile_bottom">
			<div class="container">
				<hr>
				<h2 class="h34 font-nbd">@lang('message.guest_book_you_signed')</h2>

				@if(!empty($getUserSignedBook) && count($getUserSignedBook) > 0 )
				<div class="row">
					@foreach($getUserSignedBook as $signedBookRow)
						<div class="col-sm-4 col-lg-3">
							<div class="guest">
								<img data-progressive="@if(!empty($signedBookRow->profile_image)){{ getUploadMedia($signedBookRow->profile_image) }}@else {{ url('assets/images/view-profile/ralph.png') }} @endif" class="img-fluid progressive__img progressive--not-loaded" alt="Leslie-alexander">
								<h5 class="font-nbd text-capitalize h20">{{ $signedBookRow->profile_name }}</h5>
								<p class="h17 mb-0">@if(!empty($signedBookRow->date_of_birth)){{ getConvertedDate($signedBookRow->date_of_birth, 1) }}@else{{'08/10/1950'}}@endif - @if(!empty($signedBookRow->date_of_death)){{ getConvertedDate($signedBookRow->date_of_death, 1) }}@else{{'01/01/1970'}}@endif <br>  @if(!empty($signedBookRow->short_description)){{$signedBookRow->short_description}}@else{{'Best Brother'}}@endif</p>
							</div>
						</div>
					
					@endforeach
				</div>
				@else
				<div class="guest">
					<h5 class="font-nbd text-capitalize h20">@lang('message.you_have_not_sign_guest_book')</h5>
					<p class="">@lang('message.guest_sign_your_guest_book_directly') <br> @lang('message.guest_need_to_sign_in_or_create_account')</p>
				</div>			
				@endif
			</div>
		</div>
	</section>
</main>
@endsection
   
