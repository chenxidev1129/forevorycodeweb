<div class="rightSidebar_head d-flex align-items-center justify-content-between">
    <h2 class="h34 font-nbd mb-0">Start {{@$free_trial->days}} Day Free Trial</h2>
    <a href="javascript:void(0);" class="rightSidebar_closeIcon"><em class="icon-close"></em></a>
</div>
<div class="rightSidebar_body" id="rightSidebarSubscriptioWindoe">
    <form action="{{ route('get-subscription') }}" id="userSubscriptionForm" method="post">
        @csrf
        <input type="hidden" id="subscriptionPrice" name="subscription_price" value="">
        <input type="hidden" name="lat" id="lat">   
        <input type="hidden" name="lng"  id="lng">  
        <input type="hidden" id="country_sortname">
        <div class="saprateRow">
               <h3 class="h28 font-nbd mb-2 mb-sm-3">Payment Method</h3>
                <div class="form-group">
                    <label >@lang('message.select_card_label')</label>
                    <select class="selectpicker form-control" name="card_type" id="cardSelect" data-size="4" title="Select Card">
                        <option selected value="addNewCard">@lang('message.add_new_card')</option>
                        @if(!empty($userSaveCard))
                          @foreach($userSaveCard as $cardRow)
                          <option value="{{ $cardRow->id}}">{{ $cardRow->card_type}}****{{ $cardRow->last_digit}}</option> 
                          @endforeach
                        @endif
                    </select>
                </div>
                <!-- <div class="form-group cardFields master">
                    <label>Card CVC</label>
                    <input type="text"  maxlength="4" class="form-control" placeholder="123 or 1234">
                </div> -->
            </div>
            <div class="saprateRow addNewCard">
                <h3 class="h28 font-nbd mb-2 mb-sm-3">@lang('message.card_detail')</h3>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>@lang('message.email_address_label')</label>
                            <input type="email" name="email" class="form-control" placeholder="@lang('message.email_address_placeholder')" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>@lang('message.card_holder_label')</label>
                            <input type="text" name="card_holder" class="form-control" placeholder="@lang('message.card_holder_placeholder')" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>@lang('message.card_number_label')</label>
                            <input type="text" name="card_number" class="form-control creditCardText" placeholder="1234 1234 1234 1234" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>@lang('message.card_expiration_date')</label>
                            <input class="form-control" id="exp_date" name="exp_date" maxlength='5' placeholder="MM/YY" type="text" onkeyup="formatString(event);" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>@lang('message.card_cvv_label')</label>
                            <input type="number" name="card_cvv" maxlength="4" class="form-control" placeholder="123 or 1234" autocomplete="off" onKeyPress="if(this.value.length==4) return false;">
                        </div>
                    </div>
                </div>
            </div>
        <div class="saprateRow addNewCard">
            <h3 class="h28 font-nbd mb-2 mb-sm-3">@lang('message.billing_info_heading')</h3>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>@lang('message.address_label')</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="@lang('message.address_placeholder')" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>@lang('message.country_label')</label>
                        <input type="text" name="country" id="country" class="form-control" placeholder="@lang('message.country_placeholder')" autocomplete="off" readonly>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>@lang('message.state_label')</label>
                        <input type="text" name="state" id="state" class="form-control" placeholder="@lang('message.state_placeholder')" autocomplete="off" readonly>
                    </div>
                </div>                    
                <div class="col-sm-6">
                    <div class="form-group">
                            <label>@lang('message.city_label')</label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="@lang('message.city_placeholder')" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>@lang('message.postal_code_label')</label>
                        <input type="text" name="zip_code" id="zipCode" class="form-control" placeholder="@lang('message.postal_code_placeholder')" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="saprateRow">
            <h3 class="h28 font-nbd mb-2 mb-sm-3">@lang('message.subscription_heading')</h3>
            <div class="form-group">
                <label>@lang('message.subscription_type_label')</label>
                <select data-live-search="true" data-live-search-style="startsWith" id="subscriptionType" class="selectpicker form-control resetSubsType" name="subscription_id" title="@lang('message.subscription_type_placeholder')">
                    @if(!empty($subscriptionPlan))
                        @foreach($subscriptionPlan as $plan)
                            <option value="{{ $plan->id }}">@if($plan->slug == 'life_time'){{$plan->plan}} (Free trial not applicable)@else{{$plan->plan}}@endif</option>
                        @endforeach
                    @endif    
                </select>
            </div>
        </div>
        
        <div class="saprateRow">
            <!-- <h3 class="h28 font-nbd mb-2 mb-sm-3">@lang('message.billing_info_heading')</h3> -->
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="terms_condition" value="yes" class="custom-control-input" id="billInfo">
                <label class="custom-control-label" for="billInfo">@lang('message.subscription_privacy_text')</label>
            </div>
            <p class="termsLink">@lang('message.view_text') <a target="_blank" href="{{config('constants.termConditionsUrl')}}" >@lang('message.legal_term_text')</a></p>
        </div>
        <div class="rightSidebar_bottom">
            <ul class="list-unstyled d-flex align-items-center justify-content-between flex-wrap">
                <li class="info">
                    <label class="h17">@lang('message.summary_label')</label>
                    <p class="h15 font-bd mb-0" id="showBasicPlan">@lang('message.basic_plan_text') - $0</p>
                </li>
                <li class="info">
                    <label class="h17">@lang('message.total_label')</label>
                    <p class="h15 font-bd mb-0" id="totalPrice">$0</p>
                </li>   
            </ul>
            <div class="d-flex align-items-center justify-content-center mt-3">
                <a href="javascript:void(0);" class="btn btn-outline-primary ripple-effect closeFreeTrialOnCancel">@lang('message.cancel_title')</a>
                @if(!empty(getUserDetail()) && getUserDetail()->profile_status == 1)
                <button type="button"  onclick="submitSubscription()" id="submitSubscriptionButton"  class="btn btn-primary ripple-effect">@lang('message.start_free_trial_title')</button>
                @else
                <button type="button" onclick="openAccountModel()" class="btn btn-primary ripple-effect">@lang('message.start_free_trial_title')</button>
                @endif
            </div>
        </div>
    </form>
</div>
{!! JsValidator::formRequest('App\Http\Requests\SubscriptionRequest','#userSubscriptionForm') !!} 
<!-- google address js -->
<script src="{{ url('assets/js/google-address.js') }}"></script>
<!-- Google address api  -->
<script src="https://maps.googleapis.com/maps/api/js?key={{config('constants.addressApiKey')}}&libraries=places&callback=initAutocomplete"></script>

<script>
    var subscriptionPlanUrl = "{{route('get-subscription-plan-price')}}";
    var redirectUrl = '{{ route("view-profile", ":profile_id") }}';
    var editAccountUrl = "{{ route('edit-account')}}";
</script>
<script src="{{ url('assets/js/user/subscription/subscription.js') }}"></script>