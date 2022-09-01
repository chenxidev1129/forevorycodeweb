@extends('admin.layouts.app')
@section('content')
@section('title', __('message.dashboard'))
    <!-- Main -->
    <main class="main-content dashboard">
        <div class="adminPageContent">
            <!-- admin page title -->
            <section class="adminPageTitle d-flex align-items-center justify-content-between">
                <h1 class="font-nbd h22">@lang('message.dashboard_heading')</h1>
            </section>
            <div class="filter_section collapse d-lg-block" id="filterSection">
                <form action="">
                    <div class="form_field d-flex flex-wrap align-items-center">
                        <div class="form-group">
                            <label>Date Range</label>
                            <select class="selectpicker form-control" id="filterSubscription">
                                <option value="year">Yearly</option>
                                <option value="month">Monthly</option>
                                <option value="week">Weekly</option>
                                <option value="dateFilter">Custom Filter</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Plan</label>
                            <select class="selectpicker form-control" id="filterSubscriptionPlan">
                                <option value="all">All</option>
                                @if(!empty($getSubscriptionPlan) && count($getSubscriptionPlan)>0)
                                    @foreach($getSubscriptionPlan as $row)
                                    <option value="{{ $row->id }}">{{ $row->plan }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group d-none subscriotionDateFilter">
                            <label>&nbsp;</label>
                            <div class="iconGroup">
                                <input type="text" class="form-control datetimepicker-input"  id="StartDate" placeholder="From Date" />
                                <i class="icon-calender"></i>
                            </div>
                        </div>
                        <div class="form-group d-none subscriotionDateFilter">
                            <label>&nbsp;</label>
                            <div class="iconGroup">
                                <input type="text" class="form-control datetimepicker-input"  id="EndDate" placeholder="To Date" />
                                <i class="icon-calender"></i>
                            </div>
                        </div>

                        <div class="btn_clumn d-none subscriotionDateFilter">
                            <label class="d-block">&nbsp;</label>
                            <button id="searchSubscriotion" type="button"
                                class="btn btn-primary-light ripple-effect d-inline-flex align-items-center justify-content-center mr-1"
                                data-toggle="tooltip" data-placement="top" title="Search">
                                <i class="icon-search"></i>
                            </button>
                            <button id="resetSearch" type="button"
                                class="btn btn-outline-danger ripple-effect d-inline-flex align-items-center justify-content-center btnReset"
                                data-toggle="tooltip" data-placement="top" title="Reset">
                                <i class="icon-reload"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            
            <!-- activity -->
            <section class="activity">
                <h5 class="h20 font-nbd">Activity</h5>
                <div class="activity_wrap">
                    <div class="activity_outer">
                        <canvas id="myChart" height="110"></canvas>
                    </div>
                </div>
            </section>

            <!-- activity profile -->
            <section class="activityProfile">
                <div class="row">
                    <div class="col-sm-3 ">
                        <div class="firstColumn h-100 d-flex flex-column">
                            <h6 class="h20 font-nbd">Active Profiles</h6>
                            <div class="staticBox">
                                <h2 id="activeProfileCount">{{getNumberFormat(@$activeProfileCount)}}</h2>
                                <!-- <p class="mb-0 text-success">+9.7%/vLY</p> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="d-flex flex-column secondColumn">
                            <div class="secondColumn_box">
                                <h6 class="h20 font-nbd">Active Visitors</h6>
                                <div class="staticBox">
                                    <h3 id="activeVisitorCount">{{getNumberFormat(@$activeVisitorCount)}}</h3>
                                    <!-- <p class="mb-0 text-success">+3.02%/vLY</p> -->
                                </div>
                            </div>
                            <div class="secondColumn_box">
                                <h6 class="h20 font-nbd">Visitors As Profile Holders</h6>
                                <div class="staticBox">
                                    <h3 id="visitorProfileCount">{{getNumberFormat(@$visitorProfileCount)}}</h3>
                                    <!-- <p class="mb-0 text-success">+1.02%/vLY</p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="thirdColumn h-100 d-flex flex-column">
                            <h6 class="h20 font-nbd">New Accounts</h6>
                            <div class="staticBox">
                                <h2 id="newAccountCount">{{getNumberFormat(@$newAccountCount)}}</h2>
                                <!-- <p class="mb-0 text-success">+10.7%/vLY</p> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="fourColumn h-100 d-flex flex-column">
                            <h6 class="h20 font-nbd">Unsubscribed Accounts</h6>
                            <div class="staticBox">
                                <h2 id="unSubscribeCount">{{getNumberFormat(@$unsubscribedCount)}}</h2>
                                <!-- <p class="mb-0 text-danger">-1.2%/vLY</p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>       
    </main>
@endsection

@section('js')

    <script src="{{ url('assets/js/chart.js') }}"></script>
    <script src="{{ url('assets/js/moment.min.js') }}"></script>
    <script src="{{ url('assets/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery-ui.min.js') }}"></script>
    <script>
    
    
    /* For graph */
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                data: [],
                borderColor: [
                    '#1F78B4',
                ],
                borderWidth: 2,
                pointBackgroundColor:'#1F78B4',
                label: 'Subscription',
            }]
        },
        options: {
            respoinse:true,
                plugins: {
                legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)'
                    }
                }
            }
        }
    });

    /* For graph filter */
    $("#filterSubscription ,#filterSubscriptionPlan").change(function () {
        var selectedValue = $("#filterSubscription").val();
        var subscriptionPlan = $("#filterSubscriptionPlan").val();
        if(selectedValue){
            if(selectedValue == 'dateFilter') {
                $("#EndDate").val('');
                $("#StartDate").val('');
                
                $("#EndDate").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy',
                    yearRange: '2020:+0',
                    maxDate: new Date(),
                    onClose: function (selectedDate) {
                        $("#StartDate").datepicker("option", "maxDate", selectedDate);
                    }
                });
                $("#StartDate").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy',
                    yearRange: '2020:+0',
                    maxDate: new Date(),
                    onClose: function (selectedDate) {
                        $("#EndDate").datepicker("option", "minDate", selectedDate);
                    }
                });
                /* show dates */
                $('.subscriotionDateFilter').removeClass('d-none');
            } else {
                /* hide dates */
                $('.subscriotionDateFilter').addClass('d-none');
                getSubscriptionData(myChart,selectedValue,subscriptionPlan);
            }
        }
    });

    /* For graph date filter */
    $("#searchSubscriotion").click(function () {
        var selectedValue = $("#filterSubscription").val();
        var subscriptionPlan = $("#filterSubscriptionPlan").val();
        var startDate = $('#StartDate').val();
        var endDate = $('#EndDate').val();
        if(startDate && endDate) {
            getSubscriptionData(myChart,selectedValue,subscriptionPlan,startDate,endDate)
        } else {
            if(!startDate) {
                _toast.error('Please select start date.');
            } else if(!endDate) {
                _toast.error('Please select end date.');
            } else {
                _toast.error('Please select start and end date.');
            }
        }
    });

    $("#resetSearch").click(function () {
        /* clear form data */
        $('#filterSection form')[0].reset();
        /* refresh selectpicker data */
        $('#filterSubscription').selectpicker('refresh');
        /* call default data */
        getSubscriptionData(myChart);
        /* hide date range filter */
        $('.subscriotionDateFilter').addClass('d-none');
        /* remove date range filter */
        $("#EndDate, #StartDate").datetimepicker('destroy');
    });
   
    getSubscriptionData(myChart);

    /* Get Subscriptions Data of ACTIVATION - getSubscriptionData,limit='year' */
    function getSubscriptionData(myChart,limit='year',subscriptionPlan='all',start='',end='') {
        var timezone =  Intl.DateTimeFormat().resolvedOptions().timeZone;
        $.ajax({
            type: "GET",
            url: "{{ url('/admin/get-graph-activity-data') }}",
            data: {'limit':limit,'subscriptionPlan':subscriptionPlan, 'timezone':timezone,'startDate':start,'endDate':end},
            success: function(response) {
                if (response.success) {
                    var graph = response.data.graphData;
                    myChart.data.labels = graph.labels;
                    myChart.data.datasets[0].data = graph.subscriptions;
                    myChart.update();
                    if(response.data.activeProfileCount){
                        $("#activeProfileCount").text(response.data.activeProfileCount);
                    }else{
                        $("#activeProfileCount").text(0);
                    }
                    if(response.data.activeVisitorCount){
                        $("#activeVisitorCount").text(response.data.activeVisitorCount);
                    }else{
                        $("#activeVisitorCount").text(0);
                    }
                    if(response.data.visitorProfileCount){
                        $("#visitorProfileCount").text(response.data.visitorProfileCount);
                    }else{
                        $("#visitorProfileCount").text(0);
                    }
                    if(response.data.unsubscribedCount){
                        $("#unSubscribeCount").text(response.data.unsubscribedCount);
                    }else{
                        $("#unSubscribeCount").text(0);
                    }
                    
                } else {
                    _toast.error(response.message);
                }
            }
        });
    }
    </script>
@endsection