<div class="text-right mb-3">
    <a href="javascript:void(0);" class="btn btn-primary ripple-effect ml-auto addPayment"> Add a payment method</a>
</div>    
<div class="manageCard position-relative">
    @php $i= 1; @endphp
    @if(!empty($getSaveCard) && count($getSaveCard) > 0) 
        @foreach($getSaveCard as $savedCardRow)
        <div class="media">
            <div class="media_img">
                @if($savedCardRow->card_type == 'Visa')
                <img src="{{ asset('assets/images/visa.svg')}}" alt="{{ $savedCardRow->card_type  }}"
                    class="img-fluid" />
                @elseif($savedCardRow->card_type == 'MasterCard')
                <img src="{{ asset('assets/images/master-card.svg')}}" alt="{{ $savedCardRow->card_type  }}"
                    class="img-fluid" />
                @elseif($savedCardRow->card_type == 'American Express')
                <img src="{{ asset('assets/images/american-express.svg')}}" alt="{{ $savedCardRow->card_type  }}"
                    class="img-fluid" />
                @else
                <img src="{{ asset('assets/images/credit-card.png')}}" alt="other-card"
                    class="img-fluid" />
                @endif
            
            </div>
            <div class="media_body w-100 position-relative">
                <div class="media_header d-md-flex align-items-center">
                    <h6 class="mb-0 text-uppercase h17">{{ $savedCardRow->card_type }} <span>****{{ $savedCardRow->last_digit }} </span> </h6>
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"  @if($savedCardRow->is_default != 1) onclick="makeDefaultCard('{{$savedCardRow->id}}', 'Are you sure, you want to make the card default ?')" @endif><a href="javascript:void(0)"
                                class="btn btn-sm btn-primary ripple-effect edit">@if($savedCardRow->is_default != 1) Make Default @else Default @endif</a></li>
                            
                        <li class="list-inline-item" @if($savedCardRow->is_default ==1 && count($getSaveCard) > 1) onclick="deleteDefaultConfirmation('Please make any other payment method as default to deleting this card')" @else onclick="deleteCard('{{$savedCardRow->id}}', 'Are you sure, you want to delete this card ?')" @endif><a href="javascript:void(0)"
                                class="btn btn-sm btn-sm btn-danger ripple-effect">Delete</a>
                        </li>
                    
                    </ul>
                </div>
                <div class="media_desc">
                    <span class="h15 d-block mt-md-n2"> @if($savedCardRow->is_default == 1) (Default) @endif</span>
                    <h4 class="font-md h20 text-capitalize my-1">{{ $savedCardRow->card_name }}</h4>
                    <p class="h13 font-md mb-0">Exp {{ $savedCardRow->exp_month }}/{{ $savedCardRow->exp_year }}</p>
                </div>
            </div>
        </div>
        @if(count($getSaveCard) > $i)
            <div class="divider"></div>
        @endif
            @php $i++; @endphp
        @endforeach
    @else

        <div class="alert alert-danger" role="alert">
            No save card found
        </div>
    @endif
    
    <div class="cardDetail">
        <div class="divider"></div>
        <div class="cardDetail_frm">
            <form action="{{ route('add-card') }}" method="post" id="addCardForm">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Card Holder's Name</label>
                            <input type="text" name="card_holder" class="form-control" placeholder="Card Holder's Name">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Credit or Debit Card Number</label>
                            <input name="card_number" type="text" class="form-control creditCardText"
                                placeholder="Card Number">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Expiration Date</label>
                            <input name="exp_date" class="form-control" maxlength="5" placeholder="MM/YY"
                                type="text" onkeyup="formatString(event);">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Security Code</label>
                            <div class="inputLable position-relative">
                                <input type="number" name="card_cvv" class="form-control"
                                    placeholder="Security Code"  onKeyPress="if(this.value.length==4) return false;">
                                <img src="{{ asset('assets/images/security-code.svg')}}"
                                    alt="master-card" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" id="address" name="address" class="form-control" placeholder="Address"  autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" name="country" id="country"  class="form-control" placeholder="Country"  autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" id="state" class="form-control" placeholder="State"  autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="City"  autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Postal Code</label>
                            <input type="text" name="zip_code" id="zipCode" class="form-control" placeholder="Postal Code"  autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" onclick="addCard()" id="addCardButton" class="btn btn-primary ripple-effect mr-3">Add
                    card</button>
                    <a href="javascript:void(0);"
                        class="btn btn-outline-primary ripple-effect cancel"> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\AddCardRequest','#addCardForm') !!}
<!-- google address js -->
<script src="{{ url('assets/js/google-address.js') }}"></script>
<!-- Google address api  -->
<script src="https://maps.googleapis.com/maps/api/js?key={{config('constants.addressApiKey')}}&libraries=places&callback=initAutocomplete"></script>
<!-- Add card js -->
<script src="{{ url('assets/js/user/add-card.js') }}"></script> 