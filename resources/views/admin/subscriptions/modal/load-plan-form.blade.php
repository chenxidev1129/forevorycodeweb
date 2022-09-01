<form action="{{ route('subscriptions.update', $getPlan->id) }}" method="patch" id="editPlanForm">
    @csrf
    <input type="hidden" name="id" value="@if(!empty($getPlan->id)) {{ $getPlan->id  }} @endif" >
    <input type="hidden" name="slug" value="@if(!empty($getPlan->slug)) {{ $getPlan->slug  }} @endif" >
    
    <div class="form-group">
        <label>@lang('message.name_label')</label>
        <select class="selectpicker form-control" disabled="">
            <option @if(!empty($getPlan->slug) && $getPlan->slug == 'month') selected  @endif >{{ ucfirst($getPlan->plan) }}</option>
            <option @if(!empty($getPlan->slug) && $getPlan->slug == 'free_trial') selected @endif >{{ ucfirst($getPlan->plan) }}</option>
            <option @if(!empty($getPlan->slug) && $getPlan->slug == 'annual') selected @endif >{{ ucfirst($getPlan->plan) }}</option>
            <option @if(!empty($getPlan->slug) && $getPlan->slug == 'life_time') selected @endif >{{ ucfirst($getPlan->plan) }}</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Stripe @lang('message.price_label')</label>
        <input type="text" name="price" class="form-control" id="price" value="{{$getPlan->price}}" placeholder="@lang('message.price_placeholder')" @if(!empty($getPlan->slug) && $getPlan->slug == 'free_trial') readonly  @endif>
    </div>

    <div class="form-group">
        <label> @lang('message.days_label') </label>
        <input type="text" name="days" class="form-control" id="days" value="@if(!empty($getPlan->days)){{$getPlan->days}}@endif" placeholder=" @lang('message.days_placeholder')" onkeypress="return event.charCode >= 49 && event.charCode <= 57" @if(!empty($getPlan->slug) && $getPlan->slug != 'free_trial') readonly  @endif >
   
    </div>
    
    <div class="text-right mt-5 submitBtn">
        <a href="javascript:void(0);" class="btn btn-outline-primary ripple-effect mr-2"  data-dismiss="modal">@lang('message.cancel_title')</a>
        <button type="button" onclick="editPlan()" id="submitPlanButton"  class="btn btn-primary ripple-effect">@lang('message.update_title')</button>
    </div>
</form>
{!! JsValidator::formRequest('App\Http\Requests\SubscriptionPlan','#editPlanForm') !!}
<script>

    $(document).ready(function() {  
    
        //Refresh selectpicker.
        $(".selectpicker").selectpicker();
    });

</script>