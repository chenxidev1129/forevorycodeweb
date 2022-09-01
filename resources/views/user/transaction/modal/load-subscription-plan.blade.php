<div class="row plan text-center">
@if(!empty($getCurrentSubscriptionDetail) && $getCurrentSubscriptionDetail->stripe_status !== 'canceled')
    <input type="hidden" value="{{ @$getCurrentSubscriptionDetail->id }}" id="subscriptionId">
    <div class="col-6 col-lg-4">
        <div class="card card--current">
            <div class="plan_head">
                <h3 class="font-nbd h28">{{ @$getCurrentSubscriptionDetail->subscription->plan }} @lang('message.plan_heading')</h3>
                <p>Current plan</p>
            </div>
            <div class="plan_body">
            <h4 class="h17 font-md">(${{ round(@$getCurrentSubscriptionDetail->subscription_price) }} * @lang('message.months_text')) - @if($getCurrentSubscriptionDetail->subscription->slug == 'annual') ${{ round(@$getCurrentSubscriptionDetail->subscription_price) }} @else ${{ @$getCurrentSubscriptionDetail->subscription_price*12 }} @endif @lang('message.per_year_text')</h4>
            </div>
        </div>
    </div>
    @if(!empty($getSubscriptionDetail) && count($getSubscriptionDetail) > 0  )
    @foreach($getSubscriptionDetail as $subscriptionDetailRow)

    <div class="col-6 col-lg-4">
        <div class="card text-center">
            <div class="plan_head">
                <h3 class="font-nbd h28">{{ @$subscriptionDetailRow->plan }} @lang('message.plan_heading')</h3>
                <p>@lang('message.other_plan') </p>
            </div>
            <div class="plan_body">
                <h4 class="h17 font-md">@if($subscriptionDetailRow->slug == 'month') (${{ @$subscriptionDetailRow->price }} * @lang('message.months_text')) - ${{ @$subscriptionDetailRow->price*12 }} @lang('message.per_year_text')  @elseif($subscriptionDetailRow->slug == 'annual') (${{ @$subscriptionDetailRow->price }} * @lang('message.months_text')) - ${{ @$subscriptionDetailRow->price }} @lang('message.per_year_text') @else ${{ @$subscriptionDetailRow->price }} for a lifetime  @endif </h4>
            </div>
            <div class="plan_footer mt-auto">
                <button type="button"  data-dismiss="modal" class="btn btn-primary ripple-effect switchPlanButton" @if($getDefaultPlan->slug  == 'free_trial') onclick="switchPlan({{@$subscriptionDetailRow->id }})" @else onclick="switchPlanMessage({{@$subscriptionDetailRow->id }})" @endif >@lang('message.switch_to_this_text')</button>
            </div>
        </div>
    </div>
    @endforeach
    @endif

@else
    @if(!empty($getAllPlan))
    @foreach($getAllPlan as $getPlanRow)

            <div class="col-6 col-lg-4">
                <div class="card">
                    <div class="plan_head">
                        <h3 class="font-nbd h28">{{ @$getPlanRow->plan }} @lang('message.plan_heading')</h3>
                    </div>
                    <div class="plan_body">
                        <h4 class="h17 font-md">@if($getPlanRow->slug == 'month') (${{ @$getPlanRow->price }} * @lang('message.months_text')) - ${{ @$getPlanRow->price*12 }} @lang('message.per_year_text') @elseif($getPlanRow->slug == 'annual') (${{ @$getPlanRow->price }} * @lang('message.months_text')) - ${{ @$getPlanRow->price }} @lang('message.per_year_text') @else ${{ @$getPlanRow->price }} for a lifetime  @endif </h4>
                    </div>
                    @if($getDefaultPlan->slug  == 'free_trial')
                    @php $message = __('message.switch_plan_during_free_trial'); @endphp
                    @else
                    @php $message = __('message.switch_plan_during_active_plan'); @endphp
                    @endif
                    <div class="plan_footer mt-auto">
                        <a href="@if($getCurrentSubscriptionDetail->status =='expired'){{ route('checkout-detail', ['planId'=>$getPlanRow->id, 'subscriptionId'=> $getCurrentSubscriptionDetail->id ,'type' =>'buyNewPlan'])}} @else # @endif" @if($getCurrentSubscriptionDetail->status =='active') onclick="buyNowRedirect('{{$getPlanRow->id}}', '{{$getCurrentSubscriptionDetail->id}}', '{{$message }}')" @endif  class="btn btn-primary ripple-effect">Buy Now</a>
                    </div>
                </div>
            </div>

    @endforeach
    @endif
@endif
</div>

<script>
    var switchPlanUrl = "{{ route('switch-subscription') }}";
</script>
<script src="{{ url('assets/js/user/subscription/switch-plan.js') }}"></script>
