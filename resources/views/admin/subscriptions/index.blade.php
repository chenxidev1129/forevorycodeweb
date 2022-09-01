@extends('admin.layouts.app')
@section('content')
@section('title', __('message.subscriptions'))

<link rel="stylesheet" href="{{ url('assets/css/dataTables.bootstrap4.min.css') }}" type="text/css">
        <!-- Main -->
    <main class="main-content subscriptions">
        <div class="adminPageContent">
            <!-- admin page title -->
            <section class="adminPageTitle">
                <h1 class="font-nbd h22">@lang('message.subscriptions_heading')</h1>
            </section>

            <!-- table -->
            <div class="table commonTable">
               {{ $dataTable->table() }}
            </div>

            
        </div>       
    </main>

    <!-- edit plan -->
    <div class="modal fade editPlan" id="editPlan" data-backdrop="static" aria-labelledby="editPlanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h28" id="editPlanLabel">@lang('message.edit_plan_model')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon-close"></em>
                    </button>
                </div>
                <div class="modal-body" id="loadPlanForm">
  
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ url('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/js/bootbox.min.js') }}"></script>
    <script>
    
    // load subscription edit form.
    function loadAccountForm(id) {

        $('#editPlan').modal('show');
        $.ajax({
            type: "GET",
            data: {id:id},
            url:  "{{ url('/admin/subscriptions/create') }}",
            beforeSend: function() {
                $('#loadPlanForm').html('');
            },
            success: function (data) {
                if(data.success){
                    $('#loadPlanForm').html(data.html);
                }else{
                    _toast.error(data.message);
                    $('#loadPlanForm').modal('hide');
                }
            },
        });
    }

     // Edit subscription plan
     function editPlan(){
       
       var form = $('#editPlanForm');
       var method = form.attr('method');
       var btn = $('#submitPlanButton');
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
                            window.location.href = "{{ url('admin/subscriptions') }}";
                        }, 1000)
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

    </script>
    {{ $dataTable->scripts() }}

@endsection