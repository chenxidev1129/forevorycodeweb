@extends('user.layouts.app')
@section('content')
@section('title', __('message.card_invalid'))

    <!-- Main -->
    <main class="main-content cardValidInvalid ">
        <section class="cardValidInvalid_wrap cardInvalid  text-center">
            <img src="{{ url('assets/images/invalid.png') }}" alt="Invalid">
        	<h1 class="h34 font-nbd ">@lang('message.verification_failed')</h1>
            <p class="font-md">@lang('message.verification_message')</p>
            <a href="{{ route('view-profile' ,['profile_id' => 0]) }}" class="btn ripple-effect">@lang('message.update_title')</a>
        </section>
    </main>

@endsection