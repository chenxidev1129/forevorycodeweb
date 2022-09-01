@if(!empty($getUserProfile) && !empty($getUserProfile->profile))
    @foreach($getUserProfile->profile as $profileRow)
    <div class="col">
        <div class="profile">
            <div>
                <a href="{{ route('admin/view-profile', [$profileRow->id])}}">
                    @if(!empty($profileRow->profile_image)) 
                        <img src="{{ getUploadMedia($profileRow->profile_image) }}" class="img-fluid" alt="@if(!empty($profileRow->profile_name)){{ $profileRow->profile_name }}@else Ralph “Raphy” Sarris @endif">
                    @else 
                        <img src="{{ url('assets/images/view-profile/ralph.png') }}" class="img-fluid" alt="@if(!empty($profileRow->profile_name)){{ $profileRow->profile_name }}@else Ralph “Raphy” Sarris @endif">
                    @endif
                </a>
            </div>
            
            <a href="{{ route('admin/view-profile', [$profileRow->id])}}" class="h20 font-nbd text-capitalize">@if(!empty($profileRow->profile_name)){{ $profileRow->profile_name }}@else Ralph “Raphy” Sarris @endif</a>
            <p class="h17 mb-0">@if(!empty($profileRow->date_of_birth)){{ getConvertedDate($profileRow->date_of_birth, 1) }}@else 10/7/1941 @endif - @if(!empty($profileRow->date_of_death)){{ getConvertedDate($profileRow->date_of_death, 1) }}@else 09/01/2020 @endif  <br>  @if(!empty($profileRow->short_description)){{$profileRow->short_description}}@else Beloved Dad and Grandfather @endif </p>
            <span class="status  @if($profileRow->status == 'active') active @else inactive @endif">{{ ucfirst($profileRow->status) }}</span>
        </div>
    </div>
    @endforeach
@endif