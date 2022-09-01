@if(!empty($getProfileDetail) && count($getProfileDetail->profileMediaImage) > 0 )
    <div class="row row-xs">
        @if(!empty($getProfileDetail) && count($getProfileDetail->profileMediaImage) > 0 )
        <div class="col-sm-6 photos_left">
            <a href="{{ getUploadMedia($getProfileDetail->profileMediaImage[0]->media) }}"  data-fancybox="mediaImages0" data-caption='<h1 class="h34 font-nbd mb-24">{{ $getProfileDetail->profile_name }} - Photos</h1><h3 class="h22 font-nbd">{{ ucfirst(@$getProfileDetail->profileMediaImage[0]->caption) }}</h3><p class="h15 font-bd">{{ getConvertedDate($getProfileDetail->profileMediaImage[0]->created_at , 2)}}</p>' class="inner fancybox" style="background-image:url('{{ getUploadMedia($getProfileDetail->profileMediaImage[0]->media) }}')">
                                
                @if(!empty($getProfileDetail->profileMediaImage[0]->caption))
                <div class="des des-lg"> 
                    <h5 class="h22 font-nbd text-white">{{ ucfirst($getProfileDetail->profileMediaImage[0]->caption) }}</h5>
                    <span class="text-white">{{ getConvertedDate($getProfileDetail->profileMediaImage[0]->created_at , 2)}}</span>
                </div>
                @endif
                    
            </a>
        </div>
        @endif

        <div class="col-sm-6 photos_right">
            <div class="row row-xs position-relative">
                @php $count = (!empty($getProfileDetail) && !empty($getProfileDetail->profileMediaImage)) ? count($getProfileDetail->profileMediaImage) : 4;
                $count = ($count > 4 ) ? $count : 4;
                @endphp
                @for($i = 1 ; $i <= $count;  $i++)
                
                    @if($i <= 4)
                    <div class="col-6">
                        @if(!empty($getProfileDetail) && !empty($getProfileDetail->profileMediaImage[$i]))
                        
                        <a  href="{{ getUploadMedia($getProfileDetail->profileMediaImage[$i]->media)}}" data-fancybox="mediaImages{{$i}}" data-caption='<h1 class="h34 font-nbd mb-24">{{ $getProfileDetail->profile_name }} - Photos</h1><h3 class="h22 font-nbd">{{ ucfirst(@$getProfileDetail->profileMediaImage[$i]->caption) }}</h3><p class="h15 font-bd">{{ getConvertedDate($getProfileDetail->profileMediaImage[$i]->created_at , 2)}}</p>' class="inner fancybox" style="background-image:url('{{ getUploadMedia($getProfileDetail->profileMediaImage[$i]->media)}}')">
                            <div class="des des-sm">
                                <h6 class="h14 font-nbd text-white">{{ ucfirst($getProfileDetail->profileMediaImage[$i]->caption) }}</h6>
                                <span class="text-white">{{ getConvertedDate($getProfileDetail->profileMediaImage[$i]->created_at , 2)}}</span>
                            </div>
                        </a>
                        @else
                        <a href="javascript:void(0);" class="inner" style="background-image:url('{{ url('assets/images/photo-default.jpg') }}')"></a>
                        @endif
                    </div>    
                    @else
                        @if(!empty($getProfileDetail) && !empty($getProfileDetail->profileMediaImage[$i]))
                            <div class="col-6" style="display: none">
                                <a  href="{{ getUploadMedia($getProfileDetail->profileMediaImage[$i]->media)}}" data-fancybox="mediaImages{{$i}}" data-caption='<h1 class="h34 font-nbd mb-24">{{ $getProfileDetail->profile_name }} - Photos</h1><h3 class="h22 font-nbd">{{ ucfirst(@$getProfileDetail->profileMediaImage[$i]->caption) }}</h3><p class="h15 font-bd">{{ getConvertedDate($getProfileDetail->profileMediaImage[$i]->created_at , 2)}}</p>' class="inner fancybox" style="background-image:url('{{ getUploadMedia($getProfileDetail->profileMediaImage[$i]->media)}}')">
                                    <div class="des des-sm">
                                        <h6 class="h14 font-nbd text-white">{{ ucfirst($getProfileDetail->profileMediaImage[$i]->caption) }}</h6>
                                        <span class="text-white">{{ getConvertedDate($getProfileDetail->profileMediaImage[$i]->created_at , 2)}}</span>
                                    </div>
                                </a>
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
        <a href="{{url('assets/images/view-profile/photo01-lg.jpg')}}" class="inner fancybox"  data-fancybox="mediaImages1" data-caption='<h1 class="h34 font-nbd mb-24">Ralph “Raphy” Sarris - Photos</h1><h3 class="h22 font-nbd">Dad’s Dad (Daniel Sarris) and his General James Smith sending letters via carrier pigeon.</h3><p class="h15 font-bd">November 11, 2020</p>' class="inner fancybox" style="background-image:url('{{ url('assets/images/view-profile/photo01.jpg') }}')">
            <div class="des des-lg">
                <h5 class="h22 font-nbd text-white">Dad’s Dad (Daniel Sarris) and his General James Smith sending letters via carrier pigeon.</h5>
            </div>
        </a>
        </div>
        
        <div class="col-sm-6 photos_right">
            <div class="row row-xs position-relative">
                <div class="col-6">
                    <a href="{{url('assets/images/view-profile/photo02-lg.jpg')}}" data-fancybox="mediaImages2" data-caption='<h1 class="h34 font-nbd mb-24">Ralph “Raphy” Sarris - Photos</h1><h3 class="h22 font-nbd">Trip to Myrtle Beach with the family in 1977</h3><p class="h15 font-bd">November 11, 2020</p>' class="inner fancybox" style="background-image:url('{{ url('assets/images/view-profile/photo02.jpg') }}')">
                        <div class="des des-sm">
                            <h6 class="h14 font-nbd text-white">Trip to Myrtle Beach with the family in 1977</h6>
                            <span class="text-white">November 11, 2020</span>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                <a href="{{url('assets/images/view-profile/photo03-lg.jpg')}}" data-fancybox="mediaImages3" data-caption='<h1 class="h34 font-nbd mb-24">Ralph “Raphy” Sarris - Photos</h1><h3 class="h22 font-nbd">Vacation at Myrtle Beach with Dad and Mom August 1973.</h3><p class="h15 font-bd">November 11, 2020</p>' class="inner fancybox" style="background-image:url('{{ url('assets/images/view-profile/photo03.jpg') }}')"></a>
                </div>
                <div class="col-6">
                    <a href="javascript:void(0);" class="inner" style="background-image:url('{{ url('assets/images/view-profile/photo04.jpg') }}')"></a>
                </div>
                <div class="col-6 position-relative">
                    <a href="javascript:void(0);" class="inner" style="background-image:url('{{ url('assets/images/view-profile/photo05.jpg') }}')">
                        <div class="des des-sm">
                            <h6 class="h14 font-nbd text-white">Trip to Myrtle Beach with the family in 1977</h6>
                            <span class="text-white">November 11, 2020</span>
                        </div>
                    </a>    
                </div>
            </div>
        </div>
    </div>
 @endif

<script>

    /* Show photos preview */ 
    $("a.viewAllPillPhotos").on('click', function() {
        /* Get profile media images and show in fancybox */
        $.fancybox.open( $('.fancybox'), {
            buttons : [ 
                'zoom',
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
    
    $.fancybox.close();    
</script>