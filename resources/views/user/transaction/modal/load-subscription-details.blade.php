<div class="subscribe">

    <div class="row">
        <div class="col-6 col-lg-4 card-row">
            <span class="h17 font-nbd theme-color mb-1 d-block">@lang('message.profile_name_text')</span>
            <h6 class="font-nbd h20">{{ @ucFirst($getSubscriptionDetail->profile->profile_name) }}</h6>
        </div>
        @if(!empty($getDefaultPlan->plan) && $getDefaultPlan->slug != 'life_time')
        <div class="col-6 col-lg-4 card-row">
            <span class="h17 font-nbd theme-color mb-1 d-block">@if($getSubscriptionDetail->status == 'expired') @lang('message.expiration_date') @else @lang('message.next_schedule_payment_text') @endif  </span>
            <h6 class="font-nbd h20">@if(!empty($getSubscriptionDetail->end_date)) {{ getConvertedDate($getSubscriptionDetail->end_date, 1) }} @endif<span>- ${{ round(@$getSubscriptionDetail->subscription_price) }}</span></h6>
        </div>
        @else
        <div class="col-6 col-lg-4 card-row">
            <span class="h17 font-nbd theme-color mb-1 d-block">Legacy Plan Payment</span>
            <h6 class="font-nbd h20"><span>${{ @$getSubscriptionDetail->subscription->price }}</span></h6>
        </div>
        @endif
        <div class="col-6 col-lg-4 card-row">
            <span class="h17 font-nbd theme-color mb-1 d-block">@lang('message.current_plan_text')</span>
            <h6 class="font-nbd h20">@if(@$getDefaultPlan->plan && $getDefaultPlan->slug != 'life_time') {{$getDefaultPlan->plan}} / @endif {{ @$getSubscriptionDetail->subscription->plan }}</h6>
        </div>
    </div>
    
    @if(!empty($getDefaultPlan->plan) && $getDefaultPlan->slug != 'life_time')
    <div class="subscribe_btn d-sm-flex align-items-sm-center justify-content-center">
        <a href="javascript:void(0);" class="btn btn-primary ripple-effect" onclick="viewPlan('{{ $getSubscriptionDetail->id }}')"
            data-dismiss="modal">@if($getSubscriptionDetail->stripe_status == 'canceled') Buy Now @else  @lang('message.switch_plan_text') @endif</a>
        @if($getSubscriptionDetail->stripe_status !== 'canceled')    
        <a href="javascript:void(0);" class="btn btn-outline-primary ripple-effect"
            data-dismiss="modal" onclick="showConfirmMessage('{{ $getSubscriptionDetail->id }}', 'Are you sure you want to cancel?')"> @lang('message.cancel_subscription_title')</a>
        @endif    
    </div>
    @else
    <div class="subscribe_btn d-sm-flex align-items-sm-center justify-content-center">
    The lifetime plan cannot be cancelled or switched, please contact admin@forevory.com for further assistance.
    </div>
    @endif
   
</div>