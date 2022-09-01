@if(!empty($getProfileDetail) && count($getProfileDetail->profileMediaVideo) > 0 )
<div class="row row-xs"> 
    @if(!empty($getProfileDetail) && count($getProfileDetail->profileMediaVideo) > 0 )
    <div class="col-sm-6 photos_left">
        <div class="inner" style="background-image:url('{{ getUploadMedia($getProfileDetail->profileMediaVideo[0]->thumbnail) }}')">
            <a href="{{ getUploadMedia($getProfileDetail->profileMediaVideo[0]->media) }}"  data-fancybox="mediaVideo0" data-caption='<h1 class="h34 font-nbd mb-24">{{ $getProfileDetail->profile_name }} - Videos</h1><h3 class="h22 font-nbd">{{ ucfirst(@$getProfileDetail->profileMediaVideo[0]->caption) }}</h3><p class="h15 font-bd">{{ getConvertedDate($getProfileDetail->profileMediaVideo[0]->created_at , 2)}}</p>' class="videoFancy videoPlay videoPlay-lg rounded-circle">
                <em class="icon-play-button"></em>
            </a>
        
            @if(!empty($getProfileDetail->profileMediaVideo[0]->caption))
            <div class="des des-lg">
                <h5 class="h22 font-nbd text-white">{{ $getProfileDetail->profileMediaVideo[0]->caption }}</h5>
            </div>
            @endif
        
        </div>
    </div>
    @endif
   
    <div class="col-sm-6 photos_right">
        <div class="row row-xs position-relative">
        @php $count = (!empty($getProfileDetail) && !empty($getProfileDetail->profileMediaVideo)) ? count($getProfileDetail->profileMediaVideo) : 4;
                $count = ($count > 4 ) ? $count : 4;
        @endphp
        @for($i = 1 ; $i <= $count;  $i++)
            @if($i <= 4)
            <div class="col-6">
                 @if(!empty($getProfileDetail) && !empty($getProfileDetail->profileMediaVideo[$i]))
                <div class="inner" style="background-image:url('{{ getUploadMedia($getProfileDetail->profileMediaVideo[$i]->thumbnail) }}')">
                    <a href="{{ getUploadMedia($getProfileDetail->profileMediaVideo[$i]->media) }}" data-fancybox="mediaVideo{{$i}}" data-caption='<h1 class="h34 font-nbd mb-24">{{ $getProfileDetail->profile_name }} - Videos</h1><h3 class="h22 font-nbd">{{ ucfirst(@$getProfileDetail->profileMediaVideo[$i]->caption) }}</h3><p class="h15 font-bd">{{ getConvertedDate($getProfileDetail->profileMediaVideo[$i]->created_at , 2)}}</p>' class="videoFancy videoPlay videoPlay-lg rounded-circle">
                        <em class="icon-play-button"></em>
                    </a>
                </div>
                @else
                <div class="inner" style="background-image:url('{{ url('assets/images/photo-default.jpg') }}')">
                        <a href="javascript:void(0);" class="videoPlay videoPlay-sm rounded-circle">
                            <em class="icon-play-button"></em>
                        </a>
                    </div>
                @endif
            </div>
            @else

                @if(!empty($getProfileDetail) && !empty($getProfileDetail->profileMediaVideo[$i]))
                <div class="col-6" style="display: none">
                    <div class="inner" style="background-image:url('{{ getUploadMedia($getProfileDetail->profileMediaVideo[$i]->thumbnail) }}')">
                        <a href="{{ getUploadMedia($getProfileDetail->profileMediaVideo[$i]->media) }}" data-fancybox="mediaVideo{{$i}}" data-caption='<h1 class="h34 font-nbd mb-24">{{ $getProfileDetail->profile_name }} - Videos</h1><h3 class="h22 font-nbd">{{ ucfirst(@$getProfileDetail->profileMediaVideo[$i]->caption) }}</h3><p class="h15 font-bd">{{ getConvertedDate($getProfileDetail->profileMediaVideo[$i]->created_at , 2)}}</p>' class="videoFancy videoPlay videoPlay-lg rounded-circle">
                            <em class="icon-play-button"></em>
                        </a>
                    </div>
                </div>
                @endif
           
            @endif

        @endfor    
        
        </div>
    </div>
</div>
@else

    <div class="row row-xs"> 
        <div class="col-sm-6 photos_left">
            <div class="inner" style="background-image:url('{{ url('assets/images/view-profile/photo01.jpg') }}')">
                <a href="{{url('assets/videos/view-profile/demo.mp4')}}"  data-fancybox="mediaVideo1" data-caption='<h1 class="h34 font-nbd mb-24">Ralph “Raphy” Sarris - Videos</h1><h3 class="h22 font-nbd">Vacation at Myrtle Beach with Dad and Mom August 1973</h3><p class="h15 font-bd">November 11, 2020</p>' class="videoFancy videoPlay videoPlay-lg rounded-circle">
                    <em class="icon-play-button"></em>
                </a>
                <div class="des des-lg">
                    <h5 class="h22 font-nbd text-white">Dad’s Dad (Daniel Sarris) and his General James Smith sending letters via carrier pigeon.</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 photos_right">
            <div class="row row-xs position-relative">
                <div class="col-6">
                    <div class="inner" style="background-image:url('{{ url('assets/images/view-profile/photo02.jpg') }}')">
                        <a href="{{url('assets/videos/view-profile/army.mp4')}}" data-fancybox="mediaVideo2" data-caption='<h1 class="h34 font-nbd mb-24">Ralph “Raphy” Sarris - Videos</h1><h3 class="h22 font-nbd">Trip to Myrtle Beach with the family in 1977</h3><p class="h15 font-bd">November 11, 2020</p>' class="videoFancy videoPlay videoPlay-sm rounded-circle">
                            <em class="icon-play-button"></em>
                        </a>
                    </div>
                </div>
                <div class="col-6">
                    <div class="inner" style="background-image:url('{{ url('assets/images/view-profile/photo03.jpg') }}')">
                        <a href="javascript:void(0);" class="videoPlay videoPlay-sm rounded-circle">
                            <em class="icon-play-button"></em>
                        </a>
                    </div>
                </div>
                <div class="col-6">
                    <div class="inner" style="background-image:url('{{ url('assets/images/view-profile/photo04.jpg') }}')">
                        <a href="javascript:void(0);" class="videoPlay videoPlay-sm rounded-circle">
                            <em class="icon-play-button"></em>
                        </a>
                    </div>
                </div>
                <div class="col-6 ">
                    <div class="inner" style="background-image:url('{{ url('assets/images/view-profile/photo05.jpg') }}')">
                        <a href="javascript:void(0);" class="videoPlay videoPlay-sm rounded-circle">
                            <em class="icon-play-button"></em>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<script>

    $("a.viewAllPillVideos").on('click', function() {
    /* Get profile media video and show in fancybox */
    $.fancybox.open( $('.videoFancy'), {
        buttons : [ 
            'fullScreen',
            'close'
        ],

        infobar: false,
        clickSlide: false,
        buttons: ['thumbs', 'close'],
        btnTpl: {
            arrowLeft: '<button data-fancybox-prev class="fancybox-button sliderIcon fancybox-button--arrow_left" title="">' +
                '<em class="icon-left-arrow-small"></em>' +
                "</button>",

            arrowRight: '<button data-fancybox-next class="fancybox-button sliderIcon fancybox-button--arrow_right" title="">' +
                '<em class="icon-right-arrow-small"></em>' +
                "</button>"
        },
    });
});

</script>
