@if(!empty($getProfileGuestBook))
    @foreach($getProfileGuestBook as $rowGuestBook)
    <li>
        <div class="guestBook_user d-flex">
            <div class="profile overflow-hidden rounded-circle">
                @if(!empty($rowGuestBook->image))
                <img src="{{ getUploadMedia($rowGuestBook->image) }}" alt="guest-img">
                @else
                <img src="{{ url('assets/images/user-default.jpg') }}" alt="guest-img">
                @endif
            </div>
            <h6 class="font-bd">{{ $rowGuestBook->first_name }} @if(!empty($rowGuestBook->last_name)){{ $rowGuestBook->last_name }}@endif <span class="font-rg">@lang('message.sign_the_guest_book')</span> <br> <i class="mb-0 font-rg">{{ getConvertedDate($rowGuestBook->created_at, 2) }}</i></h6>
        </div>
    </li>
    @endforeach
@endif 