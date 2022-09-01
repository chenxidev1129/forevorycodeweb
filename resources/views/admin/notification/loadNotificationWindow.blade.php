@if(!empty($getNotification) && count($getNotification) > 0)
    @foreach($getNotification as $row)
    @php $now = \Carbon\Carbon::now();

    $end_date = \Carbon\Carbon::parse($row->created_at);

    $lengthOfAd = $end_date->diffInDays($now);

    @endphp
    <div class="notificationsList">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 font-bd">
                @if(in_array($row->type, ['declined'])) 
                Payment Alert
                @else
                Account Alert
                @endif
            </h6>
            <span class="time">@if($lengthOfAd >= 1) {{ getConvertedDate($row->created_at)}} @else {{\Carbon\Carbon::parse($row->created_at)->diffForHumans()}} @endif</span>
        </div>
        <p class="my-2">{{ucfirst($row->message)}}</p>
        <div class="text-right">
            @if($row->type == 'cancelled' || $row->type == 'declined' || $row->type == 'upgrade')
                @php $rediractUrl = url('admin/profile-details/'.getProfileUserId($row->profile_id).'?isRedirect=1'); @endphp
            @else
                @php $rediractUrl = url('admin/view-profile/'.$row->profile_id); @endphp
            @endif
            <a href="javascript:void(0);" data-notification="{{$row->id}}" class="notificationDismiss font-sm">Dismiss</a>
            <a href="javascript:void(0);" data-notification="{{$row->id}}" data-url="{{$rediractUrl}}" class="notificationView font-sm">View</a>
        </div>
    </div>
    @endforeach

@else
<div>
  No record found!
</div>      
@endIf

<script>
    /* On Dismiss notification */
    $('.notificationsList .notificationDismiss').click(function() {
        event.stopPropagation();
        
        var notificationId = $(this).attr('data-notification');
        page_num_load = 1;

        $.ajax({
            type: "GET",
            url:  "{{ url('/admin/delete-notification/') }}",
            data: "limit=3&page=" + page_num_load + "&notificationId=" + notificationId,
            success: function (data) {
                if(data.success){
                    if(data.total > 0){
                        $('#adminMenu .nav-right .notifications #dropdownMenuLink').addClass('received');
                    }else{
                        $('#adminMenu .nav-right .notifications #dropdownMenuLink').removeClass('received');
                    }
                    loadNotifications()
                }
            }
        });
    });

    /* On View notification */
    $('.notificationsList .notificationView').click(function() {
        var redirectUrl = $(this).attr('data-url');
        var notificationId = $(this).attr('data-notification');
        
        $.ajax({
            type: "GET",
            url:  "{{ url('/admin/delete-notification/') }}",
            data: "notificationId=" + notificationId,
            success: function (data) {
                if(data.success){
                    window.location.href = redirectUrl;
                }
            }
        });
    });

</script>