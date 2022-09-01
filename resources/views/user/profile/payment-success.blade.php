@extends('user.layouts.app')
@section('content')
@section('title', __('message.card_valid'))

    <!-- Main -->
    <main class="main-content cardValidInvalid ">
        <section class="cardValidInvalid_wrap cardValid  text-center">
            <img src="{{ url('assets/images/valid.png') }}" alt="Valid">
        	<h1 class="h34 font-nbd ">@lang('message.verification_completed')</h1>
            <p class="font-md">@lang('message.verification_success')<br>
            @lang('message.all_set')</p>
            <a href="{{ route('view-profile' ,['profile_id' => $profileId]) }}" class="btn ripple-effect">@lang('message.edit_profile')</a>
        </section>
    </main>
    <script>
        addMixpanelEvent('Profiles created');
        addMixpanelEvent('Subscription purchased');
    </script>
@endsection