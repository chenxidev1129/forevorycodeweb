
@if(!empty($getNotification) && count($getNotification) > 0)
<ul class="list-unstyled">
    @foreach($getNotification as $row)
        @php $now = \Carbon\Carbon::now();

        $end_date = \Carbon\Carbon::parse($row->created_at);

        $lengthOfAd = $end_date->diffInDays($now);
        
        @endphp
        <li>
            <h4 class="font-sm"> @if(in_array($row->type, ['declined'])) 
                Payment 
                @else
                Account 
                @endif
            </h4>
            <p class="mb-0">{{ucfirst($row->message)}}</p>
            <span>@if($lengthOfAd >= 1) {{ getConvertedDate($row->created_at)}} @else {{\Carbon\Carbon::parse($row->created_at)->diffForHumans()}} @endif</span>
        </li>
    @endforeach  
</ul>
@else
<div class="alert alert-danger" role="alert">
  No record found!
</div>
@endIf
@if(!empty($getNotification) && count($getNotification) > 0)
    {{$getNotification->links()}}
@endif