<h2 class="h34 font-nbd">@lang('message.gravesite_details')</h2>
<div class="map" id="map">
    @if(!empty($getGraveSiteDetail->lat) && !empty($getGraveSiteDetail->lang))
    <iframe src = "https://maps.google.com/maps?q={{$getGraveSiteDetail->lat}},{{$getGraveSiteDetail->lang}}&hl=es;z=14&amp;output=embed" width="100%" height="100%" allowfullscreen="" loading="lazy" title="Gravesite Details"></iframe>
    @else
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d394.1937950246738!2d-122.42336835477595!3d37.77713998838866!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1622450604892!5m2!1sen!2sin" width="100%" height="100%" allowfullscreen="" loading="lazy" title="Gravesite Details"></iframe> 
    @endif    
</div>
<div class="locationList">
    <div class="row">
        <div class="col-sm-4">
            <h4 class="h22 font-nbd">@lang('message.location')</h4>
            <p class="" id="showGraveSiteAddress">
                @if(!empty($getGraveSiteDetail->address))
                    {{ $getGraveSiteDetail->address }}

                    @if(!empty($getGraveSiteDetail->zip_code))
                        @if (strpos($getGraveSiteDetail->address, $getGraveSiteDetail->zip_code) === false)
                            {{ $getGraveSiteDetail->zip_code }}
                        @endif
                    @endif

                @else
                    TX 78702 Gravesite Location Row 5 Plot 7 Cordoza Road, 909 Navasota St, Texas State Cemetery, Austin
                @endif
            </p>
            <p class="mb-0">
                @if(!empty($getGraveSiteDetail->note)){{ $getGraveSiteDetail->note }}@endif
            </p>
        </div>
        <div class="col-sm-4">
            <h4 class="h22 font-nbd">@lang('message.gravesite_prayers')</h4>
            <p class="mb-0">@lang('message.gravesite_prayers_text')</p>
            <a href="javascript:void(0);" onclick="viewAllPrayers()" class="btn btn-outline-primary ripple-effect">@lang('message.view_all_prayers')</a>
        </div>
        <div class="col-sm-4">
            <h4 class="h22 font-nbd">@lang('message.headstone_image')</h4>
            <p class="mb-0">@lang('message.headstone_image_text')</p>
           
            <a href=" @if(!empty($getGraveSiteDetail->image)){{ getUploadMedia($getGraveSiteDetail->image) }}@else{{ getUploadMedia('headstone.jpg') }}@endif" data-fancybox="graveImage"  class="btn btn-outline-primary ripple-effect viewLocation">@lang('message.view_image')</a>

        </div>
    </div>
</div>