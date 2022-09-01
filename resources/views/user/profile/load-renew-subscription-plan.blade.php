<div class="row plan text-center">
@if(!empty($getAllPlan))
    @foreach($getAllPlan as $getPlanRow)
    <div class="col-4">
        <div class="card card--current">
            <div class="plan_head">
                <h3 class="font-nbd h28">{{ @$getPlanRow->plan }} @lang('message.plan_heading')</h3>
            </div>
            <div class="plan_body">
                <h4 class="h17 font-md">@if($getPlanRow->slug == 'month') (${{ @$getPlanRow->price }} * @lang('message.months_text')) - ${{ @$getPlanRow->price*12 }} @lang('message.per_year_text') @elseif(($getPlanRow->slug == 'annual')) (${{ @$getPlanRow->price }} * @lang('message.months_text')) - ${{ @$getPlanRow->price }} @lang('message.per_year_text') @else ${{ @$getPlanRow->price }} for a lifetime @endif </h4>
            </div>
            <div class="plan_footer mt-auto">
                <a href="{{ route('checkout-detail', ['planId'=>$getPlanRow->id, 'subscriptionId'=> $getCurrentSubscriptionDetail->id, 'type' => 'renewPlan'])}}" class="btn btn-primary ripple-effect">Buy Now</a>
            </div>
        </div>
    </div>
    @endforeach
@endif
</div>