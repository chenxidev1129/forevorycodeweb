@extends('admin.layouts.app')
@section('content')

@section('title', __('message.transactions'))
<link rel="stylesheet" href="{{ url('assets/css/intlTelInput.css') }}" type="text/css">
<link rel="stylesheet" href="{{ url('assets/css/dataTables.bootstrap4.min.css') }}" type="text/css">
    <!-- Main -->
    <main class="main-content profileDetails">
        <div class="adminPageContent">

            <!-- admin page title -->
            <section class="adminPageTitle ">
                <h1 class="font-nbd h22 profileName">{{ $userDetail->first_name }} {{ $userDetail->last_name }}</h1>
            </section>

            <!-- account info -->
            <section class="accountInfo">
                <h2 class="font-nbd h20 mb-3">Account Info</h2>
                <div class="table-responsive">
                    <table class="table commonTable mb-0">
                        <thead>
                            <tr>
                                <!-- <th scope="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkAllProfile" id="checkAllProfile">
                                        <label class="custom-control-label" for="checkAllProfile"></label>
                                    </div>
                                </th> -->
                                <th scope="col">Name</th>
                                <th scope="col">Phone No.</th>
                                <th scope="col">Email Address</th>
                                <th scope="col">Address</th>
                                <th scope="col">Zip Code</th>
                                <th scope="col">Country</th>
                                <th scope="col">State</th>
                                <th scope="col">City</th>
                                <th scope="col">Profiles</th>
                                <th scope="col">Status</th>
                                <th scope="col">date joined</th>
                                <th scope="col">action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <!-- <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkAllChild" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td> -->
                                <td>{{ $userDetail->first_name }} {{ @$userDetail->last_name }}</td>
                                <td>{{ $userDetail->phone_number }}</td>
                                <td>{{ $userDetail->email }}</td>
                                <td class="description">{{ $userDetail->address }}</td>
                                <td>{{ $userDetail->zip_code }}</td>
                                <td>{{ $userDetail->country }}</td>
                                <td>{{ $userDetail->state }}</td>
                                <td>{{ $userDetail->city }}</td>
                                <td>{{ $userDetail->profile_count }}</td>
                                @if($userDetail->status == 'active')
                                <td><span class="active status">Active</span></td>
                                @else
                                <td><span class="inactive status">Inactive</span></td>
                                @endif
                                <td>{{ getConvertedDate($userDetail->created_at, 1) }}</td>
                                <td>
                                    @php  $message = ($userDetail->status =='active') ? "Are you sure you want to Deactivate?" : "Are you sure you want to Activate?"; 
                                    $status = ($userDetail->status=='active')?"inactive":"active";
                                    $url = route('admin/update-accout-status',[$userDetail->id]);
                                    @endphp
                                    <ul class="list-inline action">
                                        <li class="list-inline-item">
                                            <div class="switch switch-sm d-inline-block pr-2">
                                                <label>
                                                    <input type="checkbox"  class="check" id="categoryCustomSwitch" onchange="updateStatus($(this), '{{$message}}' ,'{{$url}}', '{{$status}}')"  @if($userDetail->status == 'active'){{'checked'}}  @endif>
                                                    <span class="lever"></span>
                                                </label>
                                                    <span class="lever"></span>
                                                </label>
                                            </div> 
                                        </li>
                                        <li class="list-inline-item"><span>|</span></li>
                                        <li class="list-inline-item"><a href="javascript:void(0);" onclick="editAccount('{{$userDetail->id}}')">Edit</a></li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        
            <!-- details content -->
            <section class="detailsContent">
                <h2 class="font-nbd h20">Profile Details</h2>
                <nav>
                    <div class="nav nav-pills" id="nav-tab" role="tablist">
                        <a class="nav-link myAccountsTab active" id="nav-myAccounts-tab" data-toggle="tab" href="#nav-myAccounts" role="tab" aria-controls="nav-myAccounts" aria-selected="true">My Accounts</a>
                        <a class="nav-link profileTransection" id="nav-transactions-tab" data-toggle="tab" href="#nav-transactions" role="tab" aria-controls="nav-transactions" aria-selected="false">Transactions</a>
                        <a class="nav-link" id="nav-activity-tab" data-toggle="tab" href="#nav-activity" role="tab" aria-controls="nav-activity" aria-selected="false">Activity</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-myAccounts" role="tabpanel" aria-labelledby="nav-myAccounts-tab">
                        <div class="myAccounts">
                            <div class="row" id="loadUserProfile">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-transactions" role="tabpanel" aria-labelledby="nav-transactions-tab">
                        <div class="adminPageTitle">
                        
                        </div>
                        <div class="table commonTable" id="loadProfileTransection">
                                
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-activity" role="tabpanel" aria-labelledby="nav-activity-tab">

                        <div class="filter_section" id="filterSection">
                            <form action="">
                                <div class="form_field ">
                                    <div class="form-group ml-auto">
                                        <select class="selectpicker form-control" id="filterSubscription">
                                            <option value="year">Yearly</option>
                                            <option value="month">Monthly</option>
                                            <option value="week">Weekly</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="activity">
                            <div class="activity_outer">
                                <canvas id="myChart"  height="110"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section> 
           
        </div>       
    </main>
    <input type="hidden" id="userId" value="{{$userDetail->id}}">
    <!-- edit account -->
    <div class="modal fade editAccount" id="editAccount" data-backdrop="static" aria-labelledby="editAccountLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h28" id="editAccountLabel">@lang('message.edit_account_model')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon-close"></em>
                    </button>
                </div>
                <div class="modal-body" id="loadEditAccountForm">
                
                </div>
            </div>
        </div>
    </div>
    <!-- Header -->

@endsection
    
@section('js')
    <script src="{{ url('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/js/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/js/intlTelInput.js') }}"></script>
    <script src="{{ url('assets/js/chart.js') }}"></script>
    <!-- Custome js to change status -->
    <script src="{{ url('assets/admin/js/custom.js') }}"></script>
    <script>
    
    var urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('isRedirect')) {
        if(urlParams.get('isRedirect') == 1) {
            $(".profileDetails .detailsContent #nav-tab a.profileTransection").trigger( "click" );
            loadProfileTransection();
        }
    } else {
        loadUserProfile();
    }

    /* load edit account form. */
    function editAccount(id) {
        $('#editAccount').modal('show');
        $.ajax({
            type: "GET",
            data: {id:id},
            url:  "{{ url('/admin/load-edit-account') }}",
            success: function (data) {
                if(data.success){
                    $('#loadEditAccountForm').html(data.html);
                    
                }else{
                    _toast.error(data.message);
                    $('#loadEditAccountForm').modal('hide');
                }
            },
        });
    }

    
   
    /* load edit account form. */
    function loadUserProfile() {
        //$('#editAccount').modal('show');
        var userId = $("#userId").val();
        $.ajax({
            type: "GET",
            data: { userId: userId },
            url:  "{{ url('/admin/load-user-profile') }}",
            success: function (data) {
                if(data.success){
                    
                    $('#loadUserProfile').html(data.html);
                    /* progressively */ 
                    progressively.init();
                }else{
                    _toast.error(data.message);
                    $('#loadUserProfile').modal('hide');
                }
            },
        });
    }

    /* load profile transaction. */
    function loadProfileTransection() {
        var userId = $("#userId").val();
        $.ajax({
            type: "GET",
            data: { userId: userId },
            url:  "{{ url('admin/profile-transection-list') }}",
            success: function (data) {
                $('#loadProfileTransection').html(data);
                //To remove html from datatabel checkbox
                $('th:first-child').hover(function(e){

                    $(this).attr('data-title', $(this).attr('title'));
                    $(this).removeAttr('title');

                },
                function(e){
                    $(this).attr('title', $(this).attr('data-title'));

                });
            },error: function (err) {
            
                var errors = jQuery.parseJSON(err.responseText);
                _toast.error(errors.message)
            },
        });
    }

    // Get account profiles list.
    $(document).on("click","a.myAccountsTab",function() {
        loadUserProfile();
    });

    // Get profile transection list.
    $(document).on("click","a.profileTransection",function() {
        loadProfileTransection();
    });

    /* Check and uncheck all profile list */
    $(document).on('change', '.checkAllProfile', function(){
        if($(this).prop("checked")) {
            //check all 
            $(".checkAllChild").prop("checked", true);
        } else {
            //uncheck all
            $(".checkAllChild").prop("checked", false);
        }                
    });


    $(document).on('change', '.checkAllChild', function(){

        if($('.checkAllChild:checked').length == $('.checkAllChild').length){
            //if the length is same then untick 
            $(".checkAllProfile").prop("checked", true);
        }else {
            //vise versa
            $(".checkAllProfile").prop("checked", false);            
        }
    });

    /* Start for activity graph */
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

    $("#nav-activity-tab").click(function () {
        $('#filterSubscription').selectpicker('refresh');
        getSubscriptionData(myChart);
    });

    /* For graph filter */
    $("#filterSubscription").change(function () {
        var selectedValue = $(this).val();
        if(selectedValue){
            getSubscriptionData(myChart,selectedValue);
        }
    });

    /* Get Subscriptions Data of ACTIVATION - getSubscriptionData,limit='year' */
    function getSubscriptionData(myChart,limit='year') {
        var timezone =  Intl.DateTimeFormat().resolvedOptions().timeZone;
        $.ajax({
            type: "GET",
            url: "{{ url('/admin/get-account-activity-data') }}",
            data: {'limit':limit,'timezone':timezone,'id':'{{$userDetail->id}}'},
            success: function(response) {
                if (response.success) {
                    var graph = response.data;
                    myChart.data.labels = graph.labels;
                    myChart.data.datasets[0].data = graph.subscriptions;
                    myChart.update();
                } else {
                    _toast.error(response.message);
                }
            }
        });
    }
    </script>
@endsection