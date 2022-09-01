@extends('user.layouts.app')
@section('content')
@section('title', __('message.transactions'))
<link rel="stylesheet" href="{{ url('assets/css/dataTables.bootstrap4.min.css') }}" type="text/css">
    <!-- Main -->
    <main class="main-content transactions">
        <section class="transactionsContent p-30">
            <div class="container">
                <!-- admin page title -->
                <div class="adminPageTitle">
                    <h1 class="font-nbd h22">@lang('message.transactions_heading')</h1>
                    <!-- <div class="search">
                        <input type="text" class="form-control" placeholder="Search">
                        <button type="button" class="search_btn btn"><em class="icon-search"></em></button>
                    </div> -->
                </div>
                <div class="wrapper">
                    <div class="customTabs">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="transaction-history-tab" data-toggle="pill"
                                    href="#transaction-history" role="tab" aria-controls="transaction-history"
                                    aria-selected="true">@lang('message.transection_history_headeing')</a>
                            </li>
                            <li class="nav-item loadSubscriptionInfo" role="presentation">
                                <a class="nav-link" id="subscription-info-tab" data-toggle="pill"
                                    href="#subscription-info" role="tab" aria-controls="subscription-info"
                                    aria-selected="false">Subscription Info</a>
                            </li>
                            <li class="nav-item loadManagePayment" role="presentation">
                                <a class="nav-link" id="manage-payment-tab" data-toggle="pill" href="#manage-payment"
                                    role="tab" aria-controls="manage-payment" aria-selected="false">@lang('message.manage_payment_heading')</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <!-- transaction-history -->
                        <div class="tab-pane fade show active" id="transaction-history" role="tabpanel"
                            aria-labelledby="transaction-history-tab">
                            <!-- table -->
                            <div class="table commonTable" id="loadTransectionList">
                                
                            </div>
                        </div>
                        <!--subscription-info  -->
                        <div class="tab-pane fade" id="subscription-info" role="tabpanel"
                            aria-labelledby="subscription-info-tab">
                            <!-- table -->
                            <div class="table commonTable" id="showSubscriptionInFoTable">
                                
                            </div>
                        </div>
                        <!-- manage-payment -->
                        <div class="tab-pane fade" id="manage-payment" role="tabpanel"
                            aria-labelledby="manage-payment-tab">
                           <div id="showManagePayment"></div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <!-- view all prayers -->
    <div class="modal fade viewDetail" id="viewDetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="viewDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title h34 font-nbd" id="viewDetailLabel">@lang('message.subscription_detail_heading') </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon-close"></em>
                    </button>
                </div>
                <div class="modal-body pt-0" id="showSubscriptionDetailData">
              
                </div>
            </div>
        </div>
    </div>

    <!-- view all plans -->
    <div class="modal fade viewPlan" id="viewPlan" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="viewPlanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title h34 font-nbd" id="viewPlanLabel">@lang('message.subscription_plans')</h5>

                    <button type="button" id="viewPlanButton" class="close" data-dismiss="modal" aria-label="Close" >
                        <em class="icon-close"></em>
                    </button>
                </div>
                <div class="modal-body pt-0" id="loadSubscriptionPlan">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Cancel Subscription -->
    <div class="modal fade switchSub" id="switchSub" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="switchSubLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 ">
                    <p class="text-center" id="switchSubLabel">Please be aware that changing the subscription in the middle of the billing cycle will take place effective immediately and no refund will be processed for the previous plan. Would you like to proceed? </p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon-close"></em>
                    </button>
                </div>
                <div class="modal-body pt-0">

                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary ripple-effect mr-2" id="switchPlanMessage">Yes</button>
                        <button type="button" class="btn btn-outline-primary ripple-effect"
                            data-dismiss="modal"> No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Header -->
@section('js')
<script src="{{ url('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/js/bootbox.min.js') }}"></script>
<script>
    var loadPaymentMethodListUrl = "{{ url('load-manage-payment') }}";
    var setDefaultCardUrl = "{{ url('make-card-default') }}";
    var deleteSaveCardUrl = "{{ url('delete-card') }}";
    var subscriptionListUrl = "{{ url('subscription-listing') }}";
    var transectionListUrl = "{{ url('transection-listing') }}";
    var viewSubscriptionDetailUrl = "{{ url('subscription-detail') }}";
    var cancelSubscriptionUrl = "{{ url('cancel-subscription') }}";
    var planCheckoutUrl = "{{url('checkout-detail')}}";
    var viewSubscriptionPlanUrl = "{{ url('view-subscription-plan') }}";

</script>
<script src="{{ url('assets/js/user/transection/transection.js') }}"></script>
@endsection