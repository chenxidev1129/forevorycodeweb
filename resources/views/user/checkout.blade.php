@extends('user.layouts.app')
@section('content')
@section('title', 'checkout')

<!-- Main -->
<main class="main-content checkout">
    <!-- profile info -->
    <section class="checkoutSec p-30">
        <div class="container">
            <div class="addPayment text-right mb-3">
                <a href="javascript:void(0)"> <em class="icon-plus"></em> @lang('message.add_payment_method')</a>
            </div>
            <input type="hidden" value="{{$getUserSelectPlan->id}}" id="planId">
            <input type="hidden" value="{{$subscriptionId}}" id="subscriptionId">
            <input type="hidden" value="{{$type}}" id="planType">
            
            <div class="wrapper">
                <div class="card border-0">
                    <div class="card_header font-bd">
                        <span> 1 </span> @lang('message.order_summary')
                    </div>
                    <div class="card_body">
                        <p class="mb-0 h17">
                        @if($getUserSelectPlan->slug == 'month') {{ @$getUserSelectPlan->plan }} @lang('message.plan_heading') (${{ @$getUserSelectPlan->price }} * @lang('message.months_text')) - ${{ @$getUserSelectPlan->price*12 }}  @lang('message.per_year_text') @elseif($getUserSelectPlan->slug == 'annual') {{ @$getUserSelectPlan->plan }} @lang('message.plan_heading') (${{ @$getUserSelectPlan->price }} * @lang('message.months_text')) - ${{ @$getUserSelectPlan->price }} @lang('message.per_year_text') @else ${{ @$getUserSelectPlan->price }} for a lifetime @endif
                        </p>
                    </div>
                </div>
                <div class="card border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="card_header font-bd">
                            <span> 2 </span> @lang('message.payment')
                        </div>
                        
                    </div>
                    <div class="card_body card_body--payment">
                        <div class="checkout_payment">
                        @if(!empty($getUserSavedCard))
                            @foreach($getUserSavedCard as $cardRow)
                            <div class="cardNumber d-flex">
                                <div class="cardNumber_left d-flex">
                                    <div class="form-group mb-0">
                                        <label for="">@lang('message.card_number') <span class="mandatory">*</span></label>
                                        <div class="d-flex align-items-center">
                                            <div class="custom-control custom-radio">
                                            <input type="radio" value="{{ $cardRow->id }}" id="customRadio" name="card_id" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio"></label>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                @if($cardRow->card_type == 'Visa')
                                                <img src="{{ asset('assets/images/visa.svg')}}" alt="{{ $cardRow->card_type  }}"
                                                alt="master-card" width="40" class="mr-2" />
                                                @elseif($cardRow->card_type == 'MasterCard')
                                                <img src="{{ asset('assets/images/master-card.svg')}}" alt="{{ $cardRow->card_type  }}"
                                                alt="master-card" width="40" class="mr-2" />
                                                @elseif($cardRow->card_type == 'American Express')
                                                <img src="{{ asset('assets/images/american-express.svg')}}" alt="{{ $cardRow->card_type  }}"
                                                alt="master-card" width="40" class="mr-2" />
                                                @else
                                                <img src="{{ asset('assets/images/credit-card.png')}}" alt="other-card"
                                                alt="master-card" width="40" class="mr-2" />
                                                @endif
                                                
                                                {{ $cardRow->card_type }} - &nbsp; <span> {{ $cardRow->last_digit }}</span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="alert alert-danger" role="alert">
                            @lang('message.no_save_card_found')
                            </div>
                        @endif
                        </div>
                        <!-- add card -->
                        <div class="cardDetail">
                            <div class="cardDetail_frm">
                                <form action="{{ route('add-card') }}" method="post" id="addCardForm">
                                @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>@lang('message.card_holder_label')</label>
                                                <input type="text" name="card_holder" class="form-control" placeholder="@lang('message.card_holder_placeholder')">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>@lang('message.credit_or_debit_card_number')</label>
                                                <input type="text" name="card_number" class="form-control creditCardText" placeholder="@lang('message.card_number_placeholder')">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>@lang('message.card_expiration_date')</label>
                                                <input class="form-control" name="exp_date" maxlength="5" placeholder="MM/YY"
                                                    type="text" onkeyup="formatString(event);">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>@lang('message.security_code_label')</label>
                                                <div class="inputLable position-relative">
                                                    <input type="number" name="card_cvv" class="form-control"
                                                        placeholder="@lang('message.security_code_placeholder')" onKeyPress="if(this.value.length==4) return false;">
                                                    <img src="{{ asset('assets/images/security-code.svg')}}"
                                                        alt="master-card" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>@lang('message.address_label')</label>
                                                <input type="text" id="address" name="address" class="form-control" placeholder="@lang('message.address_placeholder')"  autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>@lang('message.country_label')</label>
                                                <input type="text" name="country" id="country"  class="form-control" placeholder="@lang('message.country_placeholder')"  autocomplete="off" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>@lang('message.state_label')</label>
                                                <input type="text" name="state" id="state" class="form-control" placeholder="@lang('message.state_placeholder')"  autocomplete="off" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>@lang('message.city_label')</label>
                                                <input type="text" name="city" id="city" class="form-control" placeholder="@lang('message.city_placeholder')"  autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>@lang('message.postal_code_label')</label>
                                                <input type="text" name="zip_code" id="zipCode" class="form-control" placeholder="Postal Code" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                    <button type="button" onclick="addCard()" id="addCardButton" class="btn btn-primary ripple-effect mr-3">@lang('message.add_card_button')</button>
                                        <a href="javascript:void(0);"
                                            class="btn btn-outline-primary ripple-effect cancel"> @lang('message.cancel_title')
                                        </a>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-0">
                    <div class="card_header font-bd">
                        <span> 3 </span> @lang('message.review_order')
                    </div>
                    <div class="card_body">
                        <div class="checkout_review h-17">
                            <ul>
                                <li>New subscription cost -  @if($getUserSelectPlan->slug == 'month') ${{ @$getUserSelectPlan->price*12 }} per year @elseif($getUserSelectPlan->slug == 'annual')  ${{ @$getUserSelectPlan->price }} per year @else ${{ @$getUserSelectPlan->price }} for a lifetime @endif</li>
                            </ul>
                            <div class="notice h-17">
                                <p>@lang('message.new_plan_will_be_activated_after_expiry_of_current_active_plan')</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" placeOrder text-center">
                <button type="button" onclick="planCheckout()" id="planCheckoutButton" class="btn btn-primary ripple-effect">@lang('message.place_order_button')</button>
                </div>

            </div>
        </div>
    </section>
</main>

@endsection

@section('js')
<!-- google address js -->
<script src="{{ url('assets/js/google-address.js') }}"></script>
<!-- Google address api  -->
<script src="https://maps.googleapis.com/maps/api/js?key={{config('constants.addressApiKey')}}&libraries=places&callback=initAutocomplete"></script>
{!! JsValidator::formRequest('App\Http\Requests\AddCardRequest','#addCardForm') !!}
<script>
    var buySubscriptionUrl = "{{ route('buy-subscription') }}";
    var transectionListUrl = "{{ route('transactions') }}";
    var profileRedirectUrl = '{{ route("view-profile", ":profile_id") }}';
</script>   
<script src="{{ url('assets/js/user/add-card.js') }}"></script> 
<script src="{{ url('assets/js/subscription/new-plan-checkout.js') }}"></script>
@endsection