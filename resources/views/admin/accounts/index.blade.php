@extends('admin.layouts.app')
@section('content')
@section('title', __('message.accounts'))

<link rel="stylesheet" href="{{ url('assets/css/dataTables.bootstrap4.min.css') }}" type="text/css">

<!-- Main -->
<main class="main-content accounts">
    <div class="adminPageContent">
        <!-- admin page title -->
        <section class="adminPageTitle">
            <h1 class="font-nbd h22">@lang('message.account_heading')</h1>
        </section>

        <!-- table -->
        <div class="table commonTable">
            {{ $dataTable->table() }}
        </div>
    </div>       
</main>

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
@endsection

@section('js')

    <script src="{{ url('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/js/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/admin/js/custom.js') }}"></script>
    

    <script>
    $('th:first-child').hover(function(e){

        $(this).attr('data-title', $(this).attr('title'));
        $(this).removeAttr('title');

    },
    function(e){
        $(this).attr('title', $(this).attr('data-title'));

    });
    /* load edit account form */
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

    function updateAccountStatusModel(obj,message,url,status) {
        
        bootbox.confirm({
        message: message,
            centerVertical:true,
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn btn-primary ripple-effect'
                },
                cancel: {
                    label: 'No',
                    className: 'btn btn-outline-primary ripple-effect'
                }
            },
            callback: function (result) {
                //obj.prop( "disabled", true );
                if(result){
                    updateAccoutStatus(url,status) 
                }else{

                    if (obj.prop("checked") == true) {
                        obj.prop("checked", false)
                    } else {
                        obj.prop("checked", true)
                    }  
                   // obj.prop( "disabled", false );
                }
            }
        });
             
    }   

    // Common function to update status.
    function updateAccoutStatus(url, status){
        $.ajax({
            type: "GET",
            url: url,
            data: {status: status},
            success: function (result) {
                if (result.success) {
                   
                    var url = '{{ route("admin/update-accout-status", ":id") }}';
                    url = url.replace(':id', result.id);
                   
                    var className = $("#"+result.id+'status').attr('class');
                    $("#"+result.id+'status').removeClass(className).addClass(status+' '+'status');
                    $("#"+result.id+'status').html(status);
                   
                    if(status == 'active'){
                        var updateStatus = 'inactive';
                        var message = "Are you sure you want to Deactivate?";
                    }else{
                        var updateStatus = 'active';
                        var message = "Are you sure you want to Activate?";
                    }

                    $("#categoryCustomSwitch"+result.id).attr('onchange', 'updateAccountStatusModel($(this),"'+message+'", "'+url+'", "'+updateStatus+'");');

                    _toast.success(result.message)
                    
                } else {
                    _toast.error(result.message)
                }
            }, error: function (err) {
                _toast.error(err.message)
            }
        })
    } 
    </script>
    {{ $dataTable->scripts() }}

@endsection
