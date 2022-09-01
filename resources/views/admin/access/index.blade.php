@extends('admin.layouts.app')
@section('content')
@section('title', __('message.access'))

<link rel="stylesheet" href="{{ url('assets/css/dataTables.bootstrap4.min.css') }}" type="text/css">
<!-- Main -->
<main class="main-content access">
    <div class="adminPageContent">
        <!-- admin page title -->
        <section class="adminPageTitle d-sm-flex align-items-sm-end justify-content-sm-between">
            <div class="adminPageTitle_left">
                <h1 class="font-nbd h22">@lang('message.access')</h1>
                <!-- <div class="search">
                    <input type="text" class="form-control" placeholder="Search">
                    <button type="button" class="search_btn btn"><em class="icon-search"></em></button>
                </div> -->
            </div>
            <div class="adminPageTitle_right mt-3 mt-sm-0">
                <a href="javascript:void(0);" onclick="loadAccountForm()" class="btn btn-sm btn-outline-primary-light">@lang('message.add_account_model')</a>
            </div>
        </section>

        <!-- table -->
        <!-- <div class="table-res ponsive">
            <div class="table commonTable">
            {{ $dataTable->table() }}
            </div>
        </div> -->

        <div class="table commonTable" id="accessAccountDatatable">
            {{ $dataTable->table() }}
            </div>

        
    </div>       
</main>

<!-- add account -->
<div class="modal fade addAccount" id="addAccount" data-backdrop="static" aria-labelledby="addAccountLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h28 modelTitle" id="addAccountLabel">@lang('message.add_account_model')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon-close"></em>
                </button>
            </div>
            <div class="modal-body" id='loadAccountForm'>
 
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

    // load product add form.
    function loadAccountForm(id='') {
        if(id != ''){
            $('.modelTitle').html('@lang("message.edit_account_model")');
        }else{
            $('.modelTitle').html('@lang("message.add_account_model")');
        }
        $('#addAccount').modal('show');
        $.ajax({
            type: "GET",
            data: {id:id},
            url:  "{{ url('/admin/access/create') }}",
            beforeSend: function(){
	            $("#loadAccountForm").html('');
	        },
            success: function (data) {
                if(data.success){
                    $('#loadAccountForm').html(data.html);
                }else{
                    _toast.error(data.message);
                    $('#loadAccountForm').modal('hide');
                }
            },
        });
    }


    // save access account.
    function saveAccoutAccess(){
       
        var form = $('#AddAccountForm');
        var method = form.attr('method');
        var btn = $('#AddAccountSubmit');
        if (form.valid()) {
            btn.prop('disabled', true);
            $.ajax({
                url: form.attr('action'),
                type: method, 
                data: form.serialize(),
                dataType: 'JSON',
                success: function (data)
                {
                    if (data.success) {
                        _toast.success(data.message);
                        setTimeout(function() {
                                window.location.href = "{{url('admin/access')}}";
                            }, 500)
                    } else {
                        _toast.error(data.message) 
                        btn.prop('disabled', false);
                    }
                  
                }, error: function (err) {
                    btn.prop('disabled', false);
                    var errors = jQuery.parseJSON(err.responseText);
                    if (err.status === 422) {
                        $.each(errors.errors, function(key, val) {
                            $("#" + key + "-error").text(val);
                        });
                    } else {
                        _toast.error(errors.message)
                    }
                },
            });
       }
     };
          
    // Function to update status active to inactive.
    function updateStatus(obj,message,url,status) {
          
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
                obj.prop( "disabled", true );

                if(result){
                updateAccoutStatus(url,status) 
                }else{

                    if (obj.prop("checked") == true) {
                        obj.prop("checked", false)
                    } else {
                        obj.prop("checked", true)
                    }  
                    obj.prop( "disabled", false );
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
                success: function (data) {
                    if (data.success) {
                    _toast.success(data.message)
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                        
                    } else {
                     _toast.error(data.message)
                    }
                }, error: function (err) {
                    _toast.error(err.message)
            }
        })
    }    



</script>
 {{ $dataTable->scripts() }}

@endsection
