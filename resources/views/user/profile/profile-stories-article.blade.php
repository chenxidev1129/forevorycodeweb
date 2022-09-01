<div class="row row-l">
    <div class="col-sm-6">
        <div class="stories_top">
            @if(!empty($getProfileDetail) && count($getProfileDetail) > 0 )
            <img src="{{ getUploadMedia($getProfileDetail[0]->image) }}"
                class="img-fluid " alt="Article">
                @if(!empty($getProfileDetail[0]->title))    
                   <h3 class="h28 font-nbd">{{ ucfirst($getProfileDetail[0]->title) }}</h3>
                @endif    
                <p class="h13 by">By @if(!empty($getProfileDetail[0]->profile->user)){{ $getProfileDetail[0]->profile->user->first_name }} @if(!empty($getProfileDetail[0]->profile->user->last_name)){{ $getProfileDetail[0]->profile->user->last_name }}@endif  @endif</p>
                @if(!empty($getProfileDetail[0]->text))    
                    <a href="{{ url('read-more-stories-article/'.$getProfileDetail[0]->id) }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a>
                 @endif       
            @endif
        </div>
    </div>
    <div class="col-sm-6">
        <div class="stories_list">
            <ul class="list-unstyled">
         
            @for($i = 1 ; $i <= 4; $i++)
                @if(!empty($getProfileDetail) && !empty($getProfileDetail[$i]))
                <li class="media">
                    <div class="media-body">
                        @if(!empty($getProfileDetail[$i]->title))
                            <h5 class="h20 font-nbd mb-0">{{ ucfirst($getProfileDetail[$i]->title) }} </h5>
                        @endif    
                        <span class="date font-bd h15">{{ getConvertedDate($getProfileDetail[$i]->created_at , 2) }}</span>
                        <p class="h13 by">By @if(!empty($getProfileDetail[0]->profile->user)){{ $getProfileDetail[0]->profile->user->first_name }} @if(!empty($getProfileDetail[0]->profile->user->last_name)){{ $getProfileDetail[0]->profile->user->last_name }}@endif  @endif</p>
                        @if(!empty($getProfileDetail[$i]->text))
                            <a href="{{ url('read-more-stories-article/'.$getProfileDetail[$i]->id) }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a>
                        @endif        
                    </div>
                    <img src="{{ getUploadMedia($getProfileDetail[$i]->image) }}"
                        class="img-fluid " alt="Article">
                </li>
                @else
                <li class="media">
                    <div class="media-body">
                        <h5 class="h20 font-nbd mb-0">First trip to Myrtle Beach
                            for dad’s 35th Birthday </h5>
                        <span class="date font-bd h15">September 5, 2020</span>
                        <p class="h13 by">By Christine Sarris</p>
                        
                    </div>
                    <img src="{{ url('assets/images/photo-default.jpg') }}"
                        class="img-fluid " alt="Article">
                </li>
                @endif
            @endfor            
            </ul>
            <div class="text-center moreStories" id="moreStories" onclick="loadMoreStoriesArticle()">
                <a href="javascript:void(0);"
                    class="btn btn-outline-primary ripple-effect">Read All</a>
            </div>
        </div>
    </div>
</div>