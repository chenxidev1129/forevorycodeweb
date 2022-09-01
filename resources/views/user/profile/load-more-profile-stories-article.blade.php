@if(!empty($getProfileDetail))
   @foreach($getProfileDetail as $rowArticle)
    <li class="media">
        <div class="media-body">
            <h5 class="h20 font-nbd mb-0">{{ ucfirst( $rowArticle->title) }} </h5>
            <span class="date font-bd h15">{{ getConvertedDate( $rowArticle->created_at ,2) }}</span>
            <p class="h13 by">By @if(!empty($getProfileDetail[0]->profile->user)){{ $getProfileDetail[0]->profile->user->first_name }} @if(!empty($getProfileDetail[0]->profile->user->last_name)){{ $getProfileDetail[0]->profile->user->last_name }}@endif  @endif</p>
            @if(!empty($rowArticle->text))
            <a href="{{ url('read-more-stories-article/'.$rowArticle->id) }}" class="readMore font-bd theme-link h15">Read More<em class="icon-read-more-right"></em></a>
            @endif
        </div>
        <img src="{{ getUploadMedia($rowArticle->image) }}"
            class="img-fluid " alt="Article">
    </li>
    @endforeach
@endif    